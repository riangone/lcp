#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
为 YAML 文件中的多语言定义添加日文 (ja) 和韩文 (ko) 翻译
"""

import re
import os

# 翻译字典 - 常用术语
COMMON_TRANSLATIONS = {
    # CRM 相关
    "CRM 客户关系管理": {"ja": "CRM 顧客関係管理", "ko": "CRM 고객 관계 관리"},
    "Complete 360° customer view, sales pipeline, quote and order tracking": {"ja": "360°顧客ビュー、販売パイプライン、見積もりと注文の追跡", "ko": "완전한 360° 고객 뷰, 판매 파이프라인, 견적 및 주문 추적"},
    "Today's Business Overview": {"ja": "今日のビジネス概要", "ko": "오늘의 비즈니스 개요"},
    "Total Customers": {"ja": "総顧客数", "ko": "총 고객 수"},
    "Active Opportunities": {"ja": "アクティブな商談", "ko": "활성 기회"},
    "To Follow Up": {"ja": "フォローアップ待ち", "ko": "팔로우업 필요"},
    "Win Rate": {"ja": "成約率", "ko": "승률"},
    "Sales Funnel": {"ja": "販売ファネル", "ko": "판매 퍼널"},
    "Track opportunity conversion at each stage": {"ja": "各段階での商談転換率を追跡", "ko": "각 단계에서 기회 전환 추적"},
    "Opportunity Funnel": {"ja": "商談ファネル", "ko": "기회 퍼널"},
    "Complete conversion path from lead to close": {"ja": "リードからクローズまでの完全な転換パス", "ko": "리드에서 클로즈까지 완전한 전환 경로"},
    "Prospecting": {"ja": "見込み顧客発掘", "ko": "잠재 고객 발굴"},
    "Qualification": {"ja": "適格性確認", "ko": "자격 확인"},
    "Proposal": {"ja": "提案", "ko": "제안"},
    "Negotiation": {"ja": "交渉", "ko": "협상"},
    "Closed Won": {"ja": "成約", "ko": "성공"},
    "Quick Actions": {"ja": "クイックアクション", "ko": "빠른 작업"},
    "My Customers": {"ja": "私の顧客", "ko": "내 고객"},
    "My Opportunities": {"ja": "私の商談", "ko": "내 기회"},
    "Create Quote": {"ja": "見積もり作成", "ko": "견적 생성"},
    "Follow Ups": {"ja": "フォローアップ", "ko": "팔로우업"},
    "Sales Report": {"ja": "販売レポート", "ko": "판매 보고서"},
    "Team Management": {"ja": "チーム管理", "ko": "팀 관리"},
    "Targets": {"ja": "目標", "ko": "목표"},
    "Performance": {"ja": "パフォーマンス", "ko": "성과"},
    "Business Modules": {"ja": "ビジネスモジュール", "ko": "비즈니스 모듈"},
    "Customer, opportunity, quote and order management": {"ja": "顧客、商談、見積もり、注文管理", "ko": "고객, 기회, 견적 및 주문 관리"},
    "Customer Management": {"ja": "顧客管理", "ko": "고객 관리"},
    "360° customer view with complete info": {"ja": "完全な情報を持つ 360°顧客ビュー", "ko": "완전한 정보와 함께 360° 고객 뷰"},
    "Contact Management": {"ja": "連絡先管理", "ko": "연락처 관리"},
    "Customer contacts and decision makers": {"ja": "顧客連絡先と意思決定者", "ko": "고객 연락처 및 의사 결정자"},
    "Opportunities": {"ja": "商談", "ko": "기회"},
    "Sales pipeline and opportunity tracking": {"ja": "販売パイプラインと商談追跡", "ko": "판매 파이프라인 및 기회 추적"},
    "Quote Management": {"ja": "見積もり管理", "ko": "견적 관리"},
    "Quick quotes and price approval": {"ja": "クイック見積もりと価格承認", "ko": "빠른 견적 및 가격 승인"},
    "Order Management": {"ja": "注文管理", "ko": "주문 관리"},
    "Sales orders and contract tracking": {"ja": "販売注文と契約追跡", "ko": "판매 주문 및 계약 추적"},
    "Product Management": {"ja": "製品管理", "ko": "제품 관리"},
    "Product catalog, price and inventory": {"ja": "製品カタログ、価格、在庫", "ko": "제품 카탈로그, 가격 및 재고"},
    "Tasks & Reminders": {"ja": "タスクとリマインダー", "ko": "작업 및 알림"},
    "Important follow-ups, never miss an opportunity": {"ja": "重要なフォローアップ、機会を逃さない", "ko": "중요한 팔로우업, 기회를 놓치지 않음"},
    "Today's Tasks": {"ja": "今日のタスク", "ko": "오늘의 작업"},
    "Recent Activities": {"ja": "最近のアクティビティ", "ko": "최근 활동"},
    "Latest team activities and business progress": {"ja": "最新のチームアクティビティとビジネス進捗", "ko": "최신 팀 활동 및 비즈니스 진행 상황"},
    "Activity Log": {"ja": "アクティビティログ", "ko": "활동 로그"},
    "Business activities in last 30 days": {"ja": "過去 30 日間のビジネスアクティビティ", "ko": "지난 30 일 동안의 비즈니스 활동"},
    "Data Management": {"ja": "データ管理", "ko": "데이터 관리"},
    "Manage all business data models": {"ja": "すべてのビジネスデータモデルを管理", "ko": "모든 비즈니스 데이터 모델 관리"},
    "Data Models": {"ja": "データモデル", "ko": "데이터 모델"},
    "CRM Documentation": {"ja": "CRM ドキュメント", "ko": "CRM 문서"},
    "API Reference": {"ja": "API リファレンス", "ko": "API 참조"},
    "User Manual": {"ja": "ユーザーマニュアル", "ko": "사용자 매뉴얼"},
    "About": {"ja": "について", "ko": "정보"},
    
    # Chinook 相关
    "Chinook Music Store": {"ja": "Chinook ミュージックストア", "ko": "Chinook 음악 상점"},
    "Explore the world of music - artists, albums, and tracks": {"ja": "音楽の世界を探検 - アーティスト、アルバム、トラック", "ko": "음악의 세계 탐험 - 아티스트, 앨범, 트랙"},
    "Music Navigation": {"ja": "ミュージックナビゲーション", "ko": "음악 탐색"},
    "Artists": {"ja": "アーティスト", "ko": "아티스트"},
    "Manage artist info": {"ja": "アーティスト情報を管理", "ko": "아티스트 정보 관리"},
    "Albums": {"ja": "アルバム", "ko": "앨범"},
    "Browse album collection": {"ja": "アルバムコレクションを閲覧", "ko": "앨범 컬렉션 탐색"},
    "Tracks": {"ja": "トラック", "ko": "트랙"},
    "Manage music tracks": {"ja": "音楽トラックを管理", "ko": "음악 트랙 관리"},
    "Media Types": {"ja": "メディアタイプ", "ko": "미디어 유형"},
    "View media formats": {"ja": "メディアフォーマットを表示", "ko": "미디어 형식 보기"},
    "Music Library": {"ja": "ミュージックライブラリ", "ko": "음악 라이브러리"},
    "Manage your music collection": {"ja": "音楽コレクションを管理", "ko": "음악 컬렉션 관리"},
    "Advanced Management": {"ja": "高度な管理", "ko": "고급 관리"},
    "Multi-table views": {"ja": "マルチテーブルビュー", "ko": "다중 테이블 뷰"},
    "Multi-Table Pages": {"ja": "マルチテーブルページ", "ko": "다중 테이블 페이지"},
    "Library Statistics": {"ja": "ライブラリ統計", "ko": "라이브러리 통계"},
    "Projects": {"ja": "プロジェクト", "ko": "프로젝트"},
    "Low-Code": {"ja": "ローコード", "ko": "로우코드"},
    "More Projects": {"ja": "その他のプロジェクト", "ko": "더 많은 프로젝트"},
    "Explore other features": {"ja": "その他の機能を探検", "ko": "다른 기능 탐색"},
    "Available Projects": {"ja": "利用可能なプロジェクト", "ko": "사용 가능한 프로젝트"},
    "TODO Project": {"ja": "TODO プロジェクト", "ko": "TODO 프로젝트"},
    "Task and Project Management": {"ja": "タスクとプロジェクト管理", "ko": "작업 및 프로젝트 관리"},
    "My Journal": {"ja": "マイジャーナル", "ko": "내 일기장"},
    "Personal Journal System": {"ja": "パーソナルジャーナルシステム", "ko": "개인 일기장 시스템"},
    "Artist, Album, Track Management": {"ja": "アーティスト、アルバム、トラック管理", "ko": "아티스트, 앨범, 트랙 관리"},
    "Music Library Management": {"ja": "ミュージックライブラリ管理", "ko": "음악 라이브러리 관리"},
    "Multi-table Relationships": {"ja": "マルチテーブルリレーションシップ", "ko": "다중 테이블 관계"},
    "Read-only Views": {"ja": "読み取り専用ビュー", "ko": "읽기 전용 뷰"},
    "E-commerce Order System": {"ja": "E コマース注文システム", "ko": "이커머스 주문 시스템"},
    "Product, Order, Customer": {"ja": "製品、注文、顧客", "ko": "제품, 주문, 고객"},
    "Product Management": {"ja": "製品管理", "ko": "제품 관리"},
    "Order Processing": {"ja": "注文処理", "ko": "주문 처리"},
    "Inventory Tracking": {"ja": "在庫追跡", "ko": "재고 추적"},
    "CRM": {"ja": "CRM", "ko": "CRM"},
    "Customer, Opportunity, Order": {"ja": "顧客、商談、注文", "ko": "고객, 기회, 주문"},
    "Customer 360° View": {"ja": "顧客 360°ビュー", "ko": "고객 360° 뷰"},
    "Sales Pipeline": {"ja": "販売パイプライン", "ko": "판매 파이프라인"},
    "Quote and Order": {"ja": "見積もりと注文", "ko": "견적 및 주문"},
    "Music Docs": {"ja": "ミュージックドキュメント", "ko": "음악 문서"},
    
    # Ecommerce 相关
    "E-commerce Order System": {"ja": "E コマース注文システム", "ko": "이커머스 주문 시스템"},
    "All-in-one product, order, and customer management": {"ja": "オールインワンの製品、注文、顧客管理", "ko": "올인원 제품, 주문 및 고객 관리"},
    "Quick Actions": {"ja": "クイックアクション", "ko": "빠른 작업"},
    "Products": {"ja": "製品", "ko": "제품"},
    "Manage product catalog": {"ja": "製品カタログを管理", "ko": "제품 카탈로그 관리"},
    "Orders": {"ja": "注文", "ko": "주문"},
    "Process customer orders": {"ja": "顧客注文を処理", "ko": "고객 주문 처리"},
    "Customers": {"ja": "顧客", "ko": "고객"},
    "Manage customer info": {"ja": "顧客情報を管理", "ko": "고객 정보 관리"},
    "Sales Analytics": {"ja": "販売分析", "ko": "판매 분석"},
    "View sales data": {"ja": "販売データを表示", "ko": "판매 데이터 보기"},
    "E-commerce Data": {"ja": "E コマースデータ", "ko": "이커머스 데이터"},
    "Manage your e-commerce business": {"ja": "E コマースビジネスを管理", "ko": "이커머스 비즈니스 관리"},
    "Advanced Features": {"ja": "高度な機能", "ko": "고급 기능"},
    "Multi-table management": {"ja": "マルチテーブル管理", "ko": "다중 테이블 관리"},
    "Business Overview": {"ja": "ビジネス概要", "ko": "비즈니스 개요"},
    "Low-Code Driven": {"ja": "ローコード駆動", "ko": "로우코드 기반"},
    "Other Projects": {"ja": "その他のプロジェクト", "ko": "다른 프로젝트"},
    "Explore more features": {"ja": "その他の機能を探検", "ko": "더 많은 기능 탐색"},
    "E-commerce Docs": {"ja": "E コマースドキュメント", "ko": "이커머스 문서"},
    "Support": {"ja": "サポート", "ko": "지원"},
    
    # TODO 相关
    "TODO Project Management": {"ja": "TODO プロジェクト管理", "ko": "TODO 프로젝트 관리"},
    "Efficient task and project management system": {"ja": "効率的なタスクとプロジェクト管理システム", "ko": "효율적인 작업 및 프로젝트 관리 시스템"},
    "Quick Start": {"ja": "クイックスタート", "ko": "빠른 시작"},
    "New Task": {"ja": "新規タスク", "ko": "새 작업"},
    "Add a new task": {"ja": "新しいタスクを追加", "ko": "새 작업 추가"},
    "Manage projects": {"ja": "プロジェクトを管理", "ko": "프로젝트 관리"},
    "Task Stats": {"ja": "タスク統計", "ko": "작업 통계"},
    "View task details": {"ja": "タスク詳細を表示", "ko": "작업 세부 정보 보기"},
    "Data Management": {"ja": "データ管理", "ko": "데이터 관리"},
    "Manage your task data": {"ja": "タスクデータを管理", "ko": "작업 데이터 관리"},
    "Platform Overview": {"ja": "プラットフォーム概要", "ko": "플랫폼 개요"},
    "YAML-Driven": {"ja": "YAML 駆動", "ko": "YAML 기반"},
    "Switch Project": {"ja": "プロジェクト切り替え", "ko": "프로젝트 전환"},
    "Select another project": {"ja": "別のプロジェクトを選択", "ko": "다른 프로젝트 선택"},
    "Task Management": {"ja": "タスク管理", "ko": "작업 관리"},
    "Project Tracking": {"ja": "プロジェクト追跡", "ko": "프로젝트 추적"},
    "Status Flow": {"ja": "ステータスフロー", "ko": "상태 흐름"},
    "Documentation": {"ja": "ドキュメント", "ko": "문서"},
    "GitHub": {"ja": "GitHub", "ko": "GitHub"},
    
    # Journal 相关
    "My Journal": {"ja": "マイジャーナル", "ko": "내 일기장"},
    "Record life's moments, cherish beautiful memories": {"ja": "人生の瞬間を記録し、美しい思い出を大切にする", "ko": "인생의 순간을 기록하고 아름다운 추억을 소중히"},
    "Start Writing": {"ja": "執筆開始", "ko": "글쓰기 시작"},
    "Write Entry": {"ja": "エントリー作成", "ko": "일기 작성"},
    "Write today's story": {"ja": "今日の物語を書く", "ko": "오늘의 이야기 쓰기"},
    "Categories": {"ja": "カテゴリ", "ko": "카테고리"},
    "Manage categories": {"ja": "カテゴリを管理", "ko": "카테고리 관리"},
    "Tags": {"ja": "タグ", "ko": "태그"},
    "Manage tags": {"ja": "タグを管理", "ko": "태그 관리"},
    "Mood Tracker": {"ja": "気分トラッカー", "ko": "기분 추적기"},
    "View mood entries": {"ja": "気分エントリーを表示", "ko": "기분 항목 보기"},
    "Journal Management": {"ja": "ジャーナル管理", "ko": "일기장 관리"},
    "Manage your entries and categories": {"ja": "エントリーとカテゴリを管理", "ko": "항목 및 카테고리 관리"},
    "Statistics Views": {"ja": "統計ビュー", "ko": "통계 뷰"},
    "View journal statistics": {"ja": "ジャーナル統計を表示", "ko": "일기장 통계 보기"},
    "Stats & Views": {"ja": "統計とビュー", "ko": "통계 및 뷰"},
    "Journal Overview": {"ja": "ジャーナル概要", "ko": "일기장 개요"},
    "Total Models": {"ja": "総モデル数", "ko": "총 모델 수"},
    "Journal Docs": {"ja": "ジャーナルドキュメント", "ko": "일기장 문서"},
    
    # UI Labels
    "Add Customer": {"ja": "顧客を追加", "ko": "고객 추가"},
    "Edit": {"ja": "編集", "ko": "편집"},
    "Delete": {"ja": "削除", "ko": "삭제"},
    "Search customers...": {"ja": "顧客を検索...", "ko": "고객 검색..."},
    "Company Name": {"ja": "会社名", "ko": "회사 이름"},
    "Contact Person": {"ja": "担当者", "ko": "연락처"},
    "Industry": {"ja": "業界", "ko": "산업"},
    "Level": {"ja": "レベル", "ko": "레벨"},
    "Phone": {"ja": "電話", "ko": "전화"},
    "Email": {"ja": "メール", "ko": "이메일"},
    "City": {"ja": "都市", "ko": "도시"},
    "Status": {"ja": "ステータス", "ko": "상태"},
    "Owner": {"ja": "所有者", "ko": "소유자"},
    "title": {"ja": "タイトル", "ko": "제목"},
    "create_button": {"ja": "追加", "ko": "추가"},
    "edit_button": {"ja": "編集", "ko": "편집"},
    "delete_button": {"ja": "削除", "ko": "삭제"},
    "search_placeholder": {"ja": "検索...", "ko": "검색..."},
    "new_button": {"ja": "新規", "ko": "신규"},
    "save_button": {"ja": "保存", "ko": "저장"},
    "cancel_button": {"ja": "キャンセル", "ko": "취소"},
    "Actions": {"ja": "アクション", "ko": "작업"},
    "ID": {"ja": "ID", "ko": "ID"},
    "Name": {"ja": "名前", "ko": "이름"},
    "Description": {"ja": "説明", "ko": "설명"},
    "Price": {"ja": "価格", "ko": "가격"},
    "Category": {"ja": "カテゴリ", "ko": "카테고리"},
    "Total": {"ja": "合計", "ko": "합계"},
    "Date": {"ja": "日付", "ko": "날짜"},
    "Created At": {"ja": "作成日時", "ko": "생성일"},
    "Updated At": {"ja": "更新日時", "ko": "업데이트일"},
}

# 通用翻译函数
def translate_text(text, lang):
    """根据文本内容返回翻译"""
    if text in COMMON_TRANSLATIONS:
        return COMMON_TRANSLATIONS[text][lang]
    
    # 如果没有找到精确匹配，返回原文本（对于专有名词等）
    # 或者尝试简单的翻译
    return text

def process_yaml_content(content):
    """处理 YAML 内容，添加 ja 和 ko 翻译"""
    lines = content.split('\n')
    result_lines = []
    i = 0
    
    while i < len(lines):
        line = lines[i]
        result_lines.append(line)
        
        # 查找 zh: 行
        zh_match = re.match(r'^(\s+)zh:\s*(.*)$', line)
        if zh_match:
            indent = zh_match.group(1)
            zh_text = zh_match.group(2).strip()
            
            # 检查下一行是否是 en:
            if i + 1 < len(lines):
                en_match = re.match(r'^(\s+)en:\s*(.*)$', lines[i + 1])
                if en_match:
                    en_text = en_match.group(2).strip()
                    
                    # 查找翻译
                    ja_text = COMMON_TRANSLATIONS.get(zh_text, {}).get('ja') or COMMON_TRANSLATIONS.get(en_text, {}).get('ja') or zh_text
                    ko_text = COMMON_TRANSLATIONS.get(zh_text, {}).get('ko') or COMMON_TRANSLATIONS.get(en_text, {}).get('ko') or zh_text
                    
                    # 添加 ja: 和 ko: 行
                    result_lines.append(f'{indent}ja: {ja_text}')
                    result_lines.append(f'{indent}ko: {ko_text}')
                    i += 1  # 跳过 en: 行
        
        i += 1
    
    return '\n'.join(result_lines)

def process_yaml_file(filepath):
    """处理单个 YAML 文件"""
    print(f"处理文件：{filepath}")
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content = process_yaml_content(content)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)
    
    print(f"  完成：{filepath}")

def main():
    # 需要处理的文件列表
    files = [
        '/home/ubuntu/ws/lcp/Projects/crm/home.yaml',
        '/home/ubuntu/ws/lcp/Projects/crm/app.yaml',
        '/home/ubuntu/ws/lcp/Projects/chinook/home.yaml',
        '/home/ubuntu/ws/lcp/Projects/chinook/app.yaml',
        '/home/ubuntu/ws/lcp/Projects/ecommerce/home.yaml',
        '/home/ubuntu/ws/lcp/Projects/ecommerce/app.yaml',
        '/home/ubuntu/ws/lcp/Projects/todo/home.yaml',
        '/home/ubuntu/ws/lcp/Projects/todo/app.yaml',
        '/home/ubuntu/ws/lcp/Projects/journal/home.yaml',
        '/home/ubuntu/ws/lcp/Projects/journal/app.yaml',
        '/home/ubuntu/ws/lcp/Definitions/app.yaml',
        '/home/ubuntu/ws/lcp/Definitions/todo_app.yaml',
    ]
    
    for filepath in files:
        if os.path.exists(filepath):
            process_yaml_file(filepath)
        else:
            print(f"文件不存在：{filepath}")
    
    print("\n所有文件处理完成！")

if __name__ == '__main__':
    main()
