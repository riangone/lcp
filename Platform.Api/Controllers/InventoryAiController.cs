using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Platform.Domain.Entities;
using Platform.Domain.Services;

namespace Platform.Api.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class InventoryAiController : ControllerBase
    {
        // メモリ内データストア（実験用）
        private static InventoryState _inventoryState = new InventoryState
        {
            CurrentStock = 50,
            SafetyStock = 100,
            MaxCapacity = 500
        };

        // 承認待ちのスナップショット
        private static Dictionary<string, ProposalSnapshot> _pendingSnapshots = new Dictionary<string, ProposalSnapshot>();

        // 実行履歴
        private static List<OrderExecution> _executionHistory = new List<OrderExecution>();

        /// <summary>
        /// 在庫状態を取得
        /// </summary>
        [HttpGet]
        public IActionResult GetInventoryState()
        {
            return Ok(_inventoryState);
        }
        
        /// <summary>
        /// 在庫管理AIのホームページを表示
        /// </summary>
        [HttpGet("~/inventory-ai-home")]
        public IActionResult Index()
        {
            return Redirect("/InventoryAiPage"); // 重定向到MVC控制器
        }

        /// <summary>
        /// AIから提案を取得し、スナップショット化（Stabilize）
        /// </summary>
        [HttpPost("ai-propose")]
        public async Task<IActionResult> AiPropose([FromForm] double temperature = 0.7)
        {
            // ===== Edge層：AI呼び出し（非決定的）=====
            var proposals = await AiEdgeService.GetAiProposals(
                _inventoryState.CurrentStock,
                _inventoryState.SafetyStock,
                temperature
            );

            // ===== Shell層：スナップショット化（決定的）=====
            var snapshots = new List<ProposalSnapshot>();
            foreach (var proposal in proposals)
            {
                // 証跡（Provenance）を生成
                var trace = $"AI提案: {proposal.Reasoning} (信頼度{proposal.Confidence:P})";

                // スナップショット化（ハッシュID生成）
                var snapshot = DeterministicShell.Stabilize(
                    proposal: proposal,
                    aiModel: $"fake-ai-temp{temperature}",
                    trace: trace
                );

                // メモリに保存（承認待ち）
                _pendingSnapshots[snapshot.Id] = snapshot;
                snapshots.Add(snapshot);
            }

            return Ok(new { Snapshots = snapshots, Temperature = temperature });
        }

        /// <summary>
        /// 提案を検証（Coreに委譲）
        /// </summary>
        [HttpGet("validate/{snapshotId}")]
        public IActionResult ValidateProposal(string snapshotId)
        {
            if (!_pendingSnapshots.ContainsKey(snapshotId))
            {
                return NotFound(new { Error = "提案が見つかりません" });
            }

            var snapshot = _pendingSnapshots[snapshotId];

            // ===== Core層：純粋関数で検証 =====
            var (isValid, message) = InventoryCore.ValidateProposal(
                snapshot.Proposal,
                _inventoryState
            );

            // ===== Shell層：自動承認判断 =====
            var canAutoApprove = InventoryCore.AutoApprove(
                snapshot.Proposal,
                _inventoryState
            );

            return Ok(new
            {
                Snapshot = snapshot,
                IsValid = isValid,
                ValidationMessage = message,
                CanAutoApprove = canAutoApprove
            });
        }

        /// <summary>
        /// 承認して在庫を更新（副作用発生）
        /// </summary>
        [HttpPost("approve/{snapshotId}")]
        public IActionResult ApproveProposal(string snapshotId, [FromForm] string approvedBy = "human")
        {
            if (!_pendingSnapshots.ContainsKey(snapshotId))
            {
                return NotFound(new { Error = "提案が見つかりません" });
            }

            var snapshot = _pendingSnapshots[snapshotId];

            // ===== Core層：在庫計算 =====
            var newStock = InventoryCore.CalculateNewStock(
                _inventoryState.CurrentStock,
                snapshot.Proposal.SuggestedQuantity,
                _inventoryState.MaxCapacity
            );

            if (newStock == null)
            {
                return BadRequest(new { Error = "在庫計算に失敗しました" });
            }

            // ===== Shell層：副作用（I/O）=====
            // 在庫状態を更新
            int oldStock = _inventoryState.CurrentStock;
            _inventoryState = DeterministicShell.UpdateInventory(_inventoryState, (int)newStock);

            // 実行履歴を保存
            var execution = new OrderExecution
            {
                SnapshotId = snapshotId,
                ApprovedQuantity = snapshot.Proposal.SuggestedQuantity,
                ApprovedBy = approvedBy,
                ExecutedAt = DateTime.UtcNow,
                NewStockLevel = (int)newStock
            };
            _executionHistory.Add(execution);

            // 承認済みスナップショットを削除
            _pendingSnapshots.Remove(snapshotId);

            return Ok(new
            {
                Message = $"{snapshot.Proposal.SuggestedQuantity}個を発注しました（{oldStock} → {(int)newStock}）",
                Inventory = _inventoryState,
                Execution = execution
            });
        }

        /// <summary>
        /// 承認を拒否
        /// </summary>
        [HttpPost("reject/{snapshotId}")]
        public IActionResult RejectProposal(string snapshotId)
        {
            if (_pendingSnapshots.ContainsKey(snapshotId))
            {
                _pendingSnapshots.Remove(snapshotId);
            }

            return Ok(new { Message = "提案をキャンセルしました" });
        }

        /// <summary>
        /// 実行履歴を取得
        /// </summary>
        [HttpGet("execution-history")]
        public IActionResult GetExecutionHistory()
        {
            return Ok(_executionHistory);
        }
    }
}