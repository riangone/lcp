#!/usr/bin/env python3
"""
AI在庫管理実験：3層アーキテクチャ（Functional Core / Deterministic Shell / Non-deterministic Edge）

実行方法:
    python inventory_ai.py

アクセス:
    http://localhost:8000
"""

import asyncio
import json
import blake3
from datetime import datetime
from enum import Enum
from typing import Optional, List, Tuple
from dataclasses import dataclass, asdict

# ========================
# FastAPI 関連
# ========================
from fastapi import FastAPI, Request, Form
from fastapi.responses import HTMLResponse
from fastapi.templating import Jinja2Templates
import uvicorn

# ========================
# モデル定義（models.py 相当）
# ========================

@dataclass
class InventoryState:
    """在庫状態（純粋データ）"""
    current_stock: int
    safety_stock: int
    max_capacity: int

    def validate_invariants(self) -> bool:
        """不変条件チェック（副作用ゼロ）"""
        return (
            self.current_stock >= 0 and
            self.current_stock <= self.max_capacity and
            self.safety_stock >= 0
        )

@dataclass
class OrderProposal:
    """発注提案（AIが生成）"""
    suggested_quantity: int
    reasoning: str
    confidence: float
    temperature: float = 0.7

    def is_valid(self, max_order: int) -> bool:
        """提案の妥当性チェック（副作用ゼロ）"""
        return 0 <= self.suggested_quantity <= max_order

    def to_dict(self):
        return asdict(self)

@dataclass
class ProposalSnapshot:
    """AI提案のスナップショット（Stabilize済み）"""
    id: str
    proposal: OrderProposal
    ai_model: str
    created_at: datetime
    decision_trace: str

    @staticmethod
    def stabilize(proposal: OrderProposal, ai_model: str, trace: str) -> 'ProposalSnapshot':
        """スナップショット化（JSON正規化 + ハッシュ）"""
        normalized = json.dumps(
            proposal.to_dict(),
            sort_keys=True,
            separators=(',', ':')
        )
        hash_id = blake3.blake3(normalized.encode()).hexdigest()
        
        return ProposalSnapshot(
            id=hash_id,
            proposal=proposal,
            ai_model=ai_model,
            created_at=datetime.now(),
            decision_trace=trace
        )

class ApprovalStatus(str, Enum):
    PENDING = "pending"
    APPROVED = "approved"
    REJECTED = "rejected"

@dataclass
class OrderExecution:
    """実行された発注"""
    snapshot_id: str
    approved_quantity: int
    approved_by: str
    executed_at: datetime
    new_stock_level: int


# ========================
# Functional Core（core.py 相当）
# ========================

class InventoryCore:
    """
    純粋関数型コア（副作用ゼロ）
    全ての入力は引数、出力は戻り値のみ
    """
    
    @staticmethod
    def calculate_new_stock(
        current_stock: int,
        order_quantity: int,
        max_capacity: int
    ) -> Optional[int]:
        """
        発注後の在庫を計算（不変条件を守る）
        """
        new_stock = current_stock + order_quantity
        
        # 不変条件：在庫は0以上、最大容量以下
        if new_stock < 0 or new_stock > max_capacity:
            return None
        
        return new_stock
    
    @staticmethod
    def validate_proposal(
        proposal: OrderProposal,
        current_state: InventoryState
    ) -> Tuple[bool, str]:
        """
        提案を検証（副作用ゼロ）
        """
        # 提案自体の妥当性
        if not proposal.is_valid(current_state.max_capacity):
            return False, "発注数が最大容量を超えています"
        
        # 発注後の在庫を計算
        new_stock = InventoryCore.calculate_new_stock(
            current_state.current_stock,
            proposal.suggested_quantity,
            current_state.max_capacity
        )
        
        if new_stock is None:
            return False, "発注後の在庫が不正な値になります"
        
        # 安全在庫を下回らないか
        if new_stock < current_state.safety_stock:
            return False, "安全在庫を確保できません"
        
        return True, "承認可能"
    
    @staticmethod
    def auto_approve(proposal: OrderProposal, current_state: InventoryState) -> bool:
        """
        自動承認の判断（信頼度とリスクで判断）
        """
        is_valid, _ = InventoryCore.validate_proposal(proposal, current_state)
        
        # 信頼度80%以上 かつ バリデーションOK
        return is_valid and proposal.confidence >= 0.8


# ========================
# Non-deterministic Edge（ai_edge.py 相当）
# ========================

class FakeAI:
    """
    実験用：本物のAPIを使わずに「揺らぎ」を再現
    """
    def __init__(self, temperature: float = 0.7):
        self.temperature = temperature
    
    async def generate_proposals(self, current_stock: int, safety_stock: int) -> List[OrderProposal]:
        """
        複数の提案を生成（揺らぎの再現）
        """
        import random
        
        base_order = max(0, safety_stock * 2 - current_stock)
        
        # temperature が高いほど揺らぎが大きい
        variance = int(base_order * self.temperature * 2)
        
        proposals = []
        
        # 堅実案（保守的）
        proposals.append(OrderProposal(
            suggested_quantity=max(0, base_order - variance // 2),
            reasoning="⚠️ 安全在庫を優先。在庫切れリスクを最小限に抑えます。",
            confidence=0.9,
            temperature=self.temperature
        ))
        
        # バランス案（標準）
        proposals.append(OrderProposal(
            suggested_quantity=base_order,
            reasoning="⚖️ バランス重視。在庫コストと欠品リスクの最適化。",
            confidence=0.8,
            temperature=self.temperature
        ))
        
        # 積極案（攻め）
        proposals.append(OrderProposal(
            suggested_quantity=base_order + variance,
            reasoning="🚀 積極補充。需要増加に備え、在庫を多めに確保。",
            confidence=0.6,
            temperature=self.temperature
        ))
        
        return proposals

async def get_ai_proposals(current_stock: int, safety_stock: int, temperature: float = 0.7) -> List[OrderProposal]:
    """
    AIから提案を取得
    """
    ai = FakeAI(temperature=temperature)
    return await ai.generate_proposals(current_stock, safety_stock)


# ========================
# メモリ内データストア（実験用）
# ========================
inventory_state = InventoryState(
    current_stock=50,
    safety_stock=100,
    max_capacity=500
)

# 承認待ちのスナップショット
pending_snapshots: dict[str, ProposalSnapshot] = {}

# 実行履歴
execution_history: list[OrderExecution] = []


# ========================
# テンプレート（文字列埋め込み）
# ========================

BASE_TEMPLATE = """
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI在庫管理実験</title>
    <script src="https://unpkg.com/htmx.org@1.9.6"></script>
    <style>
        :root { --primary: #3b82f6; --success: #10b981; --warning: #f59e0b; --danger: #ef4444; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; max-width: 800px; margin: 2rem auto; line-height: 1.6; }
        .card { border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1rem; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .btn { padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; font-weight: 500; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-success { background: var(--success); color: white; }
        .btn-warning { background: var(--warning); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 999px; font-size: 0.75rem; font-weight: 500; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .info { background: #dbeafe; color: #1e40af; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; }
        .error { background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; }
        .provenance { background: #f3f4f6; padding: 0.5rem; border-radius: 0.25rem; font-size: 0.875rem; margin-top: 0.5rem; }
        [hx-indicator] { display: none; }
        [hx-indicator].htmx-request { display: inline-block; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 0.5rem; border: 1px solid #e5e7eb; }
        h1 { color: #1f2937; margin-bottom: 0.5rem; }
        h2 { color: #374151; font-size: 1.25rem; margin-bottom: 1rem; }
        h3 { color: #4b5563; font-size: 1.125rem; margin: 1.5rem 0 1rem; }
        h4 { margin: 1rem 0 0.5rem; }
        small { color: #6b7280; }
        code { background: #f9fafb; padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-family: monospace; }
    </style>
</head>
<body>
    <h1>🧪 AI在庫管理実験（3層アーキテクチャ）</h1>
    <p><strong>Edge</strong>（AI）→ <strong>Shell</strong>（固定化）→ <strong>Core</strong>（ルール）の流れを体験</p>
    <hr>
    
    {% block content %}{% endblock %}
    
    <hr>
    <small>💡 htmx で部分更新。TypeScript不要。1ファイルで完結。</small>
</body>
</html>
"""

INDEX_TEMPLATE = """
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI在庫管理実験 - スマート発注システム</title>
    <script src="https://unpkg.com/htmx.org@1.9.6"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        :root { 
            --primary: #3b82f6; --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
            --bg: #f8fafc; --card-bg: #ffffff; --text: #1e293b; --text-light: #64748b;
        }
        * { box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; 
            max-width: 1200px; margin: 0 auto; line-height: 1.6; background: var(--bg); 
            color: var(--text); padding: 1rem;
        }
        .header { background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%); color: white; padding: 2rem; border-radius: 1rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header h1 { margin: 0; font-size: 2rem; }
        .header p { margin: 0.5rem 0 0 0; opacity: 0.9; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .card { background: var(--card-bg); border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }
        .card.highlight { border-left: 4px solid var(--primary); }
        .stat-box { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #e2e8f0; }
        .stat-box:last-child { border-bottom: none; }
        .stat-label { color: var(--text-light); font-size: 0.9rem; }
        .stat-value { font-size: 1.5rem; font-weight: bold; color: var(--primary); }
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 0.5rem; cursor: pointer; font-weight: 600; font-size: 1rem; display: inline-block; margin: 0.5rem 0.5rem 0.5rem 0; transition: all 0.2s; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .btn-primary { background: var(--primary); color: white; }
        .btn-success { background: var(--success); color: white; }
        .btn-warning { background: var(--warning); color: #000; }
        .btn-danger { background: var(--danger); color: white; }
        .badge { display: inline-block; padding: 0.4rem 0.8rem; border-radius: 999px; font-size: 0.8rem; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .info { background: #dbeafe; color: #1e40af; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; border-left: 4px solid var(--primary); }
        .error { background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; border-left: 4px solid var(--danger); }
        .success { background: #dcfce7; color: #15803d; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; border-left: 4px solid var(--success); }
        .chart-container { position: relative; height: 300px; margin: 1rem 0; }
        .confidence-bars { display: flex; gap: 0.5rem; align-items: flex-end; height: 60px; }
        .confidence-bar { flex: 1; background: linear-gradient(to top, var(--primary), #60a5fa); border-radius: 4px 4px 0 0; }
        h1 { color: var(--text); margin-bottom: 0.5rem; }
        h2 { color: var(--text); font-size: 1.25rem; margin-bottom: 1rem; }
        h3 { color: var(--text); font-size: 1.1rem; margin: 1.5rem 0 1rem; }
        h4 { margin: 1rem 0 0.5rem; }
        small { color: var(--text-light); }
        code { background: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-family: monospace; }
        [hx-indicator] { display: none; }
        [hx-indicator].htmx-request { display: inline-block; }
        .comparison-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin: 1rem 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🧪 AI在庫管理システム v2.0</h1>
        <p>Functional Core / Deterministic Shell / Non-deterministic Edge の3層アーキテクチャで実現する、スマートな発注意思決定支援</p>
    </div>

    <!-- ===== ダッシュボード ===== -->
    <div class="grid">
        <div class="card highlight">
            <h3>📊 在庫統計</h3>
            <div class="stat-box">
                <span class="stat-label">現在在庫</span>
                <span class="stat-value">{{ inventory.current_stock }}個</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">安全在庫</span>
                <span class="stat-value">{{ inventory.safety_stock }}個</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">最大容量</span>
                <span class="stat-value">{{ inventory.max_capacity }}個</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">在庫充足率</span>
                <span class="stat-value">{{ "%.1f"|format(100 * inventory.current_stock / inventory.max_capacity) }}%</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">安全在庫充足</span>
                <span class="stat-value">{% if inventory.current_stock >= inventory.safety_stock %}✅{% else %}⚠️{% endif %}</span>
            </div>
        </div>

        <div class="card">
            <h3>📈 発注履歴</h3>
            <div class="stat-box">
                <span class="stat-label">総発注回数</span>
                <span class="stat-value">{{ execution_history|length }}</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">自動承認数</span>
                <span class="stat-value">{{ execution_history|selectattr('approved_by', 'equalto', 'auto')|list|length }}</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">手動承認数</span>
                <span class="stat-value">{{ execution_history|selectattr('approved_by', 'equalto', 'human')|list|length }}</span>
            </div>
            {% if execution_history %}
                <div class="stat-box">
                    <span class="stat-label">合計発注数</span>
                    <span class="stat-value">{{ execution_history|map(attribute='approved_quantity')|sum }}個</span>
                </div>
            {% endif %}
        </div>

        <div class="card">
            <h3>🎯 推奨アクション</h3>
            {% if inventory.current_stock < inventory.safety_stock %}
                <div class="error">
                    ⚠️ 在庫が安全水準を下回っています！<br>
                    <strong>即座に発注が必要</strong>
                </div>
            {% elif inventory.current_stock < inventory.safety_stock * 1.2 %}
                <div class="info">
                    📌 在庫が減少傾向<br>
                    <strong>AIに提案を求めてください</strong>
                </div>
            {% else %}
                <div class="success">
                    ✅ 在庫水準は良好<br>
                    <strong>定期的に監視してください</strong>
                </div>
            {% endif %}
        </div>
    </div>

    <!-- ===== AIシステム ===== -->
    <div class="card">
        <h2>🤖 AIスマート提案システム（Non-deterministic Edge）</h2>
        <p style="color: var(--text-light);">AIの「揺らぎ」(temperature)を調整して、異なる戦略の提案を比較できます</p>
        
        <form hx-post="/api/ai-propose" hx-target="#proposal-container" hx-swap="innerHTML">
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 1rem;">
                    <strong>AIの「揺らぎ」具合（temperature）</strong>
                </label>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span style="font-size: 0.9rem; color: var(--text-light); min-width: 60px;">保守的 ←</span>
                    <input type="range" name="temperature" id="range-temp" min="0" max="1" step="0.1" value="0.7"
                           style="flex: 1; height: 8px; cursor: pointer;">
                    <span style="font-size: 0.9rem; color: var(--text-light); min-width: 60px;">→ 積極的</span>
                    <span id="temp-value" style="display: inline-block; width: 60px; text-align: center; font-weight: bold; font-size: 1.3rem; background: #f1f5f9; padding: 0.5rem; border-radius: 0.5rem;">0.7</span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                🚀 AIに提案をもらう
            </button>
        </form>
        <script>
            document.getElementById('range-temp').addEventListener('input', function(e) {
                document.getElementById('temp-value').textContent = e.target.value;
            });
        </script>
    </div>

    <!-- AI提案表示エリア -->
    <div id="proposal-container"></div>

    <!-- ===== 実行履歴 ===== -->
    <div class="card">
        <h2>✅ 実行履歴＆トランザクションログ（Deterministic Shell）</h2>
        {% if execution_history %}
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                    <thead>
                        <tr style="background: #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 0.75rem; text-align: left;">⏰ 時刻</th>
                            <th style="padding: 0.75rem; text-align: left;">📦 数量</th>
                            <th style="padding: 0.75rem; text-align: left;">👤 承認者</th>
                            <th style="padding: 0.75rem; text-align: left;">📊 在庫量</th>
                            <th style="padding: 0.75rem; text-align: left;">🔒 スナップショット</th>
                        </tr>
                    </thead>
                    <tbody>
                    {% for exec in execution_history %}
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 0.75rem;">{{ exec.executed_at.strftime('%H:%M:%S') }}</td>
                            <td style="padding: 0.75rem;"><strong>{{ exec.approved_quantity }}個</strong></td>
                            <td style="padding: 0.75rem;">
                                {% if exec.approved_by == 'auto' %}
                                    <span class="badge badge-success">🤖 自動</span>
                                {% else %}
                                    <span class="badge badge-warning">👤 手動</span>
                                {% endif %}
                            </td>
                            <td style="padding: 0.75rem;"><strong>{{ exec.new_stock_level }}個</strong></td>
                            <td style="padding: 0.75rem; font-family: monospace; font-size: 0.85rem; color: var(--text-light);">{{ exec.snapshot_id[:12] }}...</td>
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
            </div>
        {% else %}
            <p style="color: var(--text-light); text-align: center; padding: 2rem;">
                💡 まだ発注がありません。AIに提案をもらってから承認してください
            </p>
        {% endif %}
    </div>

    <div style="text-align: center; color: var(--text-light); margin-top: 2rem; padding: 1rem;">
        <small>💡 htmx + Chart.js で部分更新。ReactやVueより圧倒的にシンプル。</small>
    </div>
</body>
</html>
"""

AI_PROPOSAL_TEMPLATE = """
<div id="proposal-container" style="margin-top: 2rem;">
    <h2>💡 AIの提案（{{ snapshots|length }}パターン）</h2>
    <p style="color: #64748b;">temperature={{ temperature }} で生成された3つの戦略を一覧比較。クリックして詳細と検証を実行してください。</p>
    
    <div class="comparison-grid">
    {% for snapshot in snapshots %}
        <div class="card" style="border-top: 4px solid {% if loop.index == 1 %}#ef4444{% elif loop.index == 2 %}#f59e0b{% else %}#10b981{% endif %}; cursor: pointer; transition: all 0.2s; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div>
                    <h4 style="margin: 0;">
                        {% if loop.index == 1 %}⚠️ 堅実案
                        {% elif loop.index == 2 %}⚖️ バランス案
                        {% else %}🚀 積極案{% endif %}
                    </h4>
                </div>
                <span class="badge {% if snapshot.proposal.confidence >= 0.8 %}badge-success{% elif snapshot.proposal.confidence >= 0.6 %}badge-warning{% else %}badge-danger{% endif %}">
                    信頼度 {{ "%.0f"|format(snapshot.proposal.confidence * 100) }}%
                </span>
            </div>
            
            <div style="background: #f1f5f9; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0;">
                <div style="font-size: 2rem; font-weight: bold; color: #3b82f6; text-align: center;">
                    {{ snapshot.proposal.suggested_quantity }}個
                </div>
                <div style="text-align: center; color: #64748b; font-size: 0.9rem; margin-top: 0.5rem;">
                    発注提案数量
                </div>
            </div>
            
            <div style="margin: 1rem 0;">
                <small style="color: #64748b;"><strong>根拠:</strong></small>
                <p style="margin: 0.5rem 0 0 0; line-height: 1.5;">{{ snapshot.proposal.reasoning }}</p>
            </div>
            
            <div style="background: #f8fafc; padding: 0.75rem; border-radius: 0.5rem; border-left: 3px solid #3b82f6; margin: 1rem 0;">
                <small>
                    <div><strong>📝 AIモデル:</strong> {{ snapshot.ai_model }}</div>
                    <div><strong>🔒 ID:</strong> <code>{{ snapshot.id[:8] }}</code></div>
                </small>
            </div>
            
            <button class="btn btn-primary" style="width: 100%; margin-top: 1rem;"
                    hx-get="/api/validate/{{ snapshot.id }}"
                    hx-target="#validation-{{ snapshot.id }}"
                    hx-swap="innerHTML">
                🔍 詳細を確認 & 検証
            </button>
            
            <div id="validation-{{ snapshot.id }}"></div>
        </div>
    {% endfor %}
    </div>
</div>
"""

APPROVAL_FORM_TEMPLATE = """
<div id="validation-{{ snapshot.id }}" style="margin-top: 1.5rem; padding: 1.5rem; border-radius: 0.75rem; background: {% if is_valid %}#dcfce7{% else %}#fee2e2{% endif %}; border-left: 4px solid {% if is_valid %}#10b981{% else %}#ef4444{% endif %};">
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
        <div>
            <h4 style="margin: 0; color: {% if is_valid %}#15803d{% else %}#991b1b{% endif %};">
                {% if is_valid %}✅ 検証OK - 承認可能{% else %}❌ 検証NG - 問題あり{% endif %}
            </h4>
            <p style="margin: 0.5rem 0 0 0; color: {% if is_valid %}#15803d{% else %}#991b1b{% endif %};">
                {{ validation_message }}
            </p>
        </div>
    </div>
    
    {% if is_valid %}
        <div style="background: white; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; border-left: 3px solid #10b981;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <div style="color: #64748b; font-size: 0.9rem;">📦 発注数量</div>
                    <div style="font-size: 1.5rem; font-weight: bold; color: #3b82f6;">{{ snapshot.proposal.suggested_quantity }}個</div>
                </div>
                <div>
                    <div style="color: #64748b; font-size: 0.9rem;">🤖 信頼度</div>
                    <div style="font-size: 1.5rem; font-weight: bold; color: {% if snapshot.proposal.confidence >= 0.8 %}#10b981{% elif snapshot.proposal.confidence >= 0.6 %}#f59e0b{% else %}#ef4444{% endif %};">
                        {{ "%.0f"|format(snapshot.proposal.confidence * 100) }}%
                    </div>
                </div>
            </div>
        </div>
        
        <div style="background: white; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0;">
            <small style="color: #64748b;">
                <div><strong>🤖 自動承認判定:</strong> {% if can_auto_approve %}可能（信頼度 {{ "%.0f"|format(snapshot.proposal.confidence * 100) }}% >= 80%）{% else %}不可（信頼度が80%未満）{% endif %}</div>
            </small>
        </div>
        
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem;">
            {% if can_auto_approve %}
                <button class="btn btn-success" style="flex: 1; min-width: 200px;"
                        hx-post="/api/approve/{{ snapshot.id }}"
                        hx-vals='{"approved_by": "auto"}'
                        hx-target="#proposal-container"
                        hx-swap="outerHTML">
                    🤖 自動承認して実行
                </button>
            {% else %}
                <button class="btn btn-primary" style="flex: 1; min-width: 200px;"
                        hx-post="/api/approve/{{ snapshot.id }}"
                        hx-target="#proposal-container"
                        hx-swap="outerHTML">
                    👤 人間が承認して実行
                </button>
            {% endif %}
            
            <button class="btn btn-danger" style="flex: 1; min-width: 200px;"
                    hx-post="/api/reject/{{ snapshot.id }}"
                    hx-target="#proposal-container"
                    hx-swap="delete">
                ❌ キャンセル
            </button>
        </div>
    {% else %}
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem;">
            <button class="btn btn-danger" style="flex: 1; min-width: 200px;"
                    hx-post="/api/reject/{{ snapshot.id }}"
                    hx-target="#proposal-container"
                    hx-swap="delete">
                ❌ キャンセル
            </button>
        </div>
    {% endif %}
</div>
"""

INVENTORY_CARD_TEMPLATE = """
<div class="card highlight">
    <h2>📦 在庫状態更新（Functional Core）</h2>
    
    {% if message %}
        <div class="success" style="margin-bottom: 1.5rem;">
            {{ message }}
        </div>
    {% endif %}
    
    <div class="stat-box">
        <span class="stat-label">現在在庫</span>
        <span class="stat-value">{{ inventory.current_stock }}個</span>
    </div>
    <div class="stat-box">
        <span class="stat-label">安全在庫</span>
        <span class="stat-value">{{ inventory.safety_stock }}個</span>
    </div>
    <div class="stat-box">
        <span class="stat-label">最大容量</span>
        <span class="stat-value">{{ inventory.max_capacity }}個</span>
    </div>
    <div class="stat-box">
        <span class="stat-label">使用率</span>
        <span class="stat-value">{{ "%.1f"|format(100 * inventory.current_stock / inventory.max_capacity) }}%</span>
    </div>
    <div class="stat-box">
        <span class="stat-label">ステータス</span>
        <span class="stat-value">
            {% if inventory.current_stock < inventory.safety_stock %}
                ⚠️ 要補充
            {% elif inventory.current_stock > inventory.max_capacity * 0.8 %}
                ✅ 充分
            {% else %}
                ⚖️ 適正
            {% endif %}
        </span>
    </div>
    
    <div style="background: #f1f5f9; padding: 1rem; border-radius: 0.5rem; margin-top: 1rem; border-left: 3px solid #3b82f6;">
        <small>🔒 <strong>Functional Core層の不変条件:</strong>
            <code>{{ inventory.current_stock }} >= 0</code> ✓ 
            <code>{{ inventory.current_stock }} <= {{ inventory.max_capacity }}</code> 
            {% if inventory.current_stock >= 0 and inventory.current_stock <= inventory.max_capacity %}✓{% else %}✗{% endif %}
        </small>
    </div>
</div>
"""


# ========================
# FastAPI アプリケーション
# ========================

from jinja2 import BaseLoader, Environment, TemplateNotFound

class StringLoader(BaseLoader):
    def __init__(self):
        self.templates = {
            'base.html': BASE_TEMPLATE,
            'index.html': INDEX_TEMPLATE,
            'components/ai_proposal.html': AI_PROPOSAL_TEMPLATE,
            'components/approval_form.html': APPROVAL_FORM_TEMPLATE,
            'components/inventory_card.html': INVENTORY_CARD_TEMPLATE,
        }
    
    def get_source(self, environment, template_name):
        if template_name in self.templates:
            return self.templates[template_name], None, lambda: True
        raise TemplateNotFound(template_name)

app = FastAPI()

# カスタムローダーを使用して Environment を初期化
env = Environment(loader=StringLoader())
templates = Jinja2Templates(env=env)


# ========================
# ルート：在庫状態表示
# ========================

@app.get("/", response_class=HTMLResponse)
async def index(request: Request):
    response = templates.TemplateResponse("index.html", {
        "request": request,
        "inventory": inventory_state,
        "execution_history": execution_history,
        "history": execution_history  # 後方互換性
    })
    response.headers["Cache-Control"] = "no-cache, no-store, must-revalidate"
    response.headers["Pragma"] = "no-cache"
    response.headers["Expires"] = "0"
    return response


# ========================
# Non-deterministic Edge → Deterministic Shell
# AI提案を取得して「スナップショット化」
# ========================

@app.post("/api/ai-propose", response_class=HTMLResponse)
async def ai_propose(
    request: Request,
    temperature: float = Form(0.7)
):
    """
    AIから提案を取得し、スナップショット化（Stabilize）
    """
    global inventory_state
    
    # ===== Edge層：AI呼び出し（非決定的）=====
    proposals = await get_ai_proposals(
        inventory_state.current_stock,
        inventory_state.safety_stock,
        temperature=temperature
    )
    
    # ===== Shell層：スナップショット化（決定的）=====
    snapshots = []
    for proposal in proposals:
        # 証跡（Provenance）を生成
        trace = f"AI提案: {proposal.reasoning} (信頼度{proposal.confidence:.0%})"
        
        # スナップショット化（ハッシュID生成）
        snapshot = ProposalSnapshot.stabilize(
            proposal=proposal,
            ai_model=f"fake-ai-temp{temperature}",
            trace=trace
        )
        
        # メモリに保存（承認待ち）
        pending_snapshots[snapshot.id] = snapshot
        snapshots.append(snapshot)
    
    # htmx で部分更新：提案カードを表示
    return templates.TemplateResponse("components/ai_proposal.html", {
        "request": request,
        "snapshots": snapshots,
        "inventory": inventory_state,
        "temperature": temperature
    })


# ========================
# Shell → Core：提案を検証
# ========================

@app.get("/api/validate/{snapshot_id}", response_class=HTMLResponse)
async def validate_proposal(request: Request, snapshot_id: str):
    """
    提案を検証（Coreに委譲）
    """
    global inventory_state
    
    snapshot = pending_snapshots.get(snapshot_id)
    if not snapshot:
        return "<div class='error'>提案が見つかりません</div>"
    
    # ===== Core層：純粋関数で検証 =====
    is_valid, message = InventoryCore.validate_proposal(
        snapshot.proposal,
        inventory_state
    )
    
    # ===== Shell層：自動承認判断 =====
    can_auto_approve = InventoryCore.auto_approve(
        snapshot.proposal,
        inventory_state
    )
    
    # htmx で部分更新：検証結果を表示
    return templates.TemplateResponse("components/approval_form.html", {
        "request": request,
        "snapshot": snapshot,
        "is_valid": is_valid,
        "validation_message": message,
        "can_auto_approve": can_auto_approve
    })


# ========================
# 承認 → 実行（I/O）
# ========================

@app.post("/api/approve/{snapshot_id}", response_class=HTMLResponse)
async def approve_proposal(
    request: Request,
    snapshot_id: str,
    approved_by: str = Form("human")
):
    """
    承認して在庫を更新（副作用発生）
    """
    global inventory_state
    
    snapshot = pending_snapshots.get(snapshot_id)
    if not snapshot:
        return "<div class='error'>提案が見つかりません</div>"
    
    # ===== Core層：在庫計算 =====
    new_stock = InventoryCore.calculate_new_stock(
        inventory_state.current_stock,
        snapshot.proposal.suggested_quantity,
        inventory_state.max_capacity
    )
    
    if new_stock is None:
        return "<div class='error'>在庫計算に失敗しました</div>"
    
    # ===== Shell層：副作用（I/O）=====
    # 在庫状態を更新
    old_stock = inventory_state.current_stock
    inventory_state = InventoryState(
        current_stock=new_stock,
        safety_stock=inventory_state.safety_stock,
        max_capacity=inventory_state.max_capacity
    )
    
    # 実行履歴を保存
    execution = OrderExecution(
        snapshot_id=snapshot_id,
        approved_quantity=snapshot.proposal.suggested_quantity,
        approved_by=approved_by,
        executed_at=datetime.now(),
        new_stock_level=new_stock
    )
    execution_history.append(execution)
    
    # 承認済みスナップショットを削除
    if snapshot_id in pending_snapshots:
        del pending_snapshots[snapshot_id]
    
    # htmx で部分更新：成功メッセージ + 在庫カード更新
    return templates.TemplateResponse("components/inventory_card.html", {
        "request": request,
        "inventory": inventory_state,
        "message": f"✅ {snapshot.proposal.suggested_quantity}個を発注しました（{old_stock} → {new_stock}）"
    })


# ========================
# 承認拒否
# ========================

@app.post("/api/reject/{snapshot_id}", response_class=HTMLResponse)
async def reject_proposal(request: Request, snapshot_id: str):
    """
    承認を拒否
    """
    if snapshot_id in pending_snapshots:
        del pending_snapshots[snapshot_id]
    
    return "<div class='info'>提案をキャンセルしました</div>"


# ========================
# 実行
# ========================

if __name__ == "__main__":
    print("=" * 50)
    print("🧪 AI在庫管理実験：3層アーキテクチャ")
    print("=" * 50)
    print("📌 Functional Core    : 純粋関数（在庫計算・検証）")
    print("📌 Deterministic Shell: 副作用管理（スナップショット・実行）")
    print("📌 Non-deterministic Edge: AI提案生成")
    print("=" * 50)
    print("🌐 http://localhost:8000 にアクセス")
    print("🛑 Ctrl+C で終了")
    print("=" * 50)
    
    uvicorn.run(app, host="0.0.0.0", port=8000, log_level="info")