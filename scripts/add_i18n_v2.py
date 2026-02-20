#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
为 YAML 文件中的多语言定义添加日文 (ja) 和韩文 (ko) 翻译
版本 2 - 更完善的处理
"""

import re
import os

# 详细翻译字典
TRANSLATIONS = {
    # ==================== CRM home.yaml ====================
    "CRM 客户关系管理": {"ja": "CRM 顧客関係管理", "ko": "CRM 고객 관계 관리"},
    "完整的客户 360°视图，销售漏斗管理，报价订单跟踪，助力业务增长": {"ja": "完全な顧客 360°ビュー、販売ファネル管理、見積もり注文追跡でビジネス成長を支援", "ko": "완전한 고객 360° 뷰, 판매 퍼널 관리, 견적 주문 추적으로 비즈니스 성장 지원"},
    "Complete 360° customer view, sales pipeline, quote and order tracking": {"ja": "360°顧客ビュー、販売パイプライン、見積もりと注文の追跡", "ko": "완전한 360° 고객 뷰, 판매 파이프라인, 견적 및 주문 추적"},
    "今日业务概览": {"ja": "今日のビジネス概要", "ko": "오늘의 비즈니스 개요"},
    "Today's Business Overview": {"ja": "今日のビジネス概要", "ko": "오늘의 비즈니스 개요"},
    "总客户数": {"ja": "総顧客数", "ko": "총 고객 수"},
    "Total Customers": {"ja": "総顧客数", "ko": "총 고객 수"},
    "进行中商机": {"ja": "進行中の商談", "ko": "진행 중인 기회"},
    "Active Opportunities": {"ja": "アクティブな商談", "ko": "활성 기회"},
    "待跟进": {"ja": "フォローアップ待ち", "ko": "팔로우업 필요"},
    "To Follow Up": {"ja": "フォローアップ待ち", "ko": "팔로우업 필요"},
    "成交率": {"ja": "成約率", "ko": "성공률"},
    "Win Rate": {"ja": "成約率", "ko": "승률"},
    "销售漏斗": {"ja": "販売ファネル", "ko": "판매 퍼널"},
    "Sales Funnel": {"ja": "販売ファネル", "ko": "판매 퍼널"},
    "实时跟踪销售机会各阶段转化情况": {"ja": "販売機会の各段階の転換状況をリアルタイムで追跡", "ko": "판매 기회 각 단계 전환 상황 실시간 추적"},
    "Track opportunity conversion at each stage": {"ja": "各段階での商談転換率を追跡", "ko": "각 단계에서 기회 전환 추적"},
    "销售机会漏斗": {"ja": "販売機会ファネル", "ko": "판매 기회 퍼널"},
    "Opportunity Funnel": {"ja": "商談ファネル", "ko": "기회 퍼널"},
    "从潜在客户到成交的完整转化路径": {"ja": "潜在顧客から成約までの完全な転換パス", "ko": "잠재 고객에서 성사까지 완전한 전환 경로"},
    "Complete conversion path from lead to close": {"ja": "リードからクローズまでの完全な転換パス", "ko": "리드에서 클로즈까지 완전한 전환 경로"},
    "初步接洽": {"ja": "初步接触", "ko": "초기 접촉"},
    "Prospecting": {"ja": "見込み顧客発掘", "ko": "잠재 고객 발굴"},
    "需求确认": {"ja": "ニーズ確認", "ko": "요구 사항 확인"},
    "Qualification": {"ja": "適格性確認", "ko": "자격 확인"},
    "方案报价": {"ja": "提案見積もり", "ko": "제안 견적"},
    "Proposal": {"ja": "提案", "ko": "제안"},
    "商务谈判": {"ja": "商務交渉", "ko": "비즈니스 협상"},
    "Negotiation": {"ja": "交渉", "ko": "협상"},
    "成交赢单": {"ja": "成約", "ko": "성공"},
    "Closed Won": {"ja": "成約", "ko": "성공"},
    "快捷操作": {"ja": "クイック操作", "ko": "빠른 작업"},
    "Quick Actions": {"ja": "クイックアクション", "ko": "빠른 작업"},
    "我的客户": {"ja": "私の顧客", "ko": "내 고객"},
    "My Customers": {"ja": "私の顧客", "ko": "내 고객"},
    "我的商机": {"ja": "私の商談", "ko": "내 기회"},
    "My Opportunities": {"ja": "私の商談", "ko": "내 기회"},
    "创建报价": {"ja": "見積もり作成", "ko": "견적 생성"},
    "Create Quote": {"ja": "見積もり作成", "ko": "견적 생성"},
    "待跟进": {"ja": "フォローアップ待ち", "ko": "팔로우업 대기"},
    "Follow Ups": {"ja": "フォローアップ", "ko": "팔로우업"},
    "销售报表": {"ja": "販売レポート", "ko": "판매 보고서"},
    "Sales Report": {"ja": "販売レポート", "ko": "판매 보고서"},
    "团队管理": {"ja": "チーム管理", "ko": "팀 관리"},
    "Team Management": {"ja": "チーム管理", "ko": "팀 관리"},
    "目标设定": {"ja": "目標設定", "ko": "목표 설정"},
    "Targets": {"ja": "目標", "ko": "목표"},
    "业绩分析": {"ja": "業績分析", "ko": "실적 분석"},
    "Performance": {"ja": "パフォーマンス", "ko": "성과"},
    "业务模块": {"ja": "ビジネスモジュール", "ko": "비즈니스 모듈"},
    "Business Modules": {"ja": "ビジネスモジュール", "ko": "비즈니스 모듈"},
    "客户管理、销售机会、报价订单全流程管理": {"ja": "顧客管理、販売機会、見積もり注文のフルプロセス管理", "ko": "고객 관리, 판매 기회, 견적 주문 전 과정 관리"},
    "Customer, opportunity, quote and order management": {"ja": "顧客、商談、見積もり、注文管理", "ko": "고객, 기회, 견적 및 주문 관리"},
    "客户管理": {"ja": "顧客管理", "ko": "고객 관리"},
    "Customer Management": {"ja": "顧客管理", "ko": "고객 관리"},
    "360°客户视图，完整客户信息": {"ja": "360°顧客ビュー、完全な顧客情報", "ko": "360° 고객 뷰, 완전한 고객 정보"},
    "360° customer view with complete info": {"ja": "完全な情報を持つ 360°顧客ビュー", "ko": "완전한 정보와 함께 360° 고객 뷰"},
    "联系人管理": {"ja": "連絡先管理", "ko": "연락처 관리"},
    "Contact Management": {"ja": "連絡先管理", "ko": "연락처 관리"},
    "客户联系人，决策链管理": {"ja": "顧客連絡先、意思決定チェーン管理", "ko": "고객 연락처, 의사 결정 체인 관리"},
    "Customer contacts and decision makers": {"ja": "顧客連絡先と意思決定者", "ko": "고객 연락처 및 의사 결정자"},
    "销售机会": {"ja": "販売機会", "ko": "판매 기회"},
    "Opportunities": {"ja": "商談", "ko": "기회"},
    "销售漏斗，商机阶段跟踪": {"ja": "販売ファネル、商談段階追跡", "ko": "판매 퍼널, 기회 단계 추적"},
    "Sales pipeline and opportunity tracking": {"ja": "販売パイプラインと商談追跡", "ko": "판매 파이프라인 및 기회 추적"},
    "报价管理": {"ja": "見積もり管理", "ko": "견적 관리"},
    "Quote Management": {"ja": "見積もり管理", "ko": "견적 관리"},
    "快速报价，价格审批流程": {"ja": "クイック見積もり、価格承認フロー", "ko": "빠른 견적, 가격 승인 프로세스"},
    "Quick quotes and price approval": {"ja": "クイック見積もりと価格承認", "ko": "빠른 견적 및 가격 승인"},
    "订单管理": {"ja": "注文管理", "ko": "주문 관리"},
    "Order Management": {"ja": "注文管理", "ko": "주문 관리"},
    "销售订单，合同执行跟踪": {"ja": "販売注文、契約履行追跡", "ko": "판매 주문, 계약 이행 추적"},
    "Sales orders and contract tracking": {"ja": "販売注文と契約追跡", "ko": "판매 주문 및 계약 추적"},
    "产品管理": {"ja": "製品管理", "ko": "제품 관리"},
    "Product Management": {"ja": "製品管理", "ko": "제품 관리"},
    "产品目录，价格库存管理": {"ja": "製品カタログ、価格在庫管理", "ko": "제품 카탈로그, 가격 재고 관리"},
    "Product catalog, price and inventory": {"ja": "製品カタログ、価格、在庫", "ko": "제품 카탈로그, 가격 및 재고"},
    "待办事项": {"ja": "やるべきこと", "ko": "할 일"},
    "Tasks & Reminders": {"ja": "タスクとリマインダー", "ko": "작업 및 알림"},
    "重要待跟进事项，不错过任何商机": {"ja": "重要なフォローアップ事項、どんな商機も逃さない", "ko": "중요한 팔로우업 사항, 어떤 기회도 놓치지 않음"},
    "Important follow-ups, never miss an opportunity": {"ja": "重要なフォローアップ、機会を逃さない", "ko": "중요한 팔로우업, 기회를 놓치지 않음"},
    "今日待办": {"ja": "今日のやるべきこと", "ko": "오늘의 할 일"},
    "Today's Tasks": {"ja": "今日のタスク", "ko": "오늘의 작업"},
    "最近活动": {"ja": "最近のアクティビティ", "ko": "최근 활동"},
    "Recent Activities": {"ja": "最近のアクティビティ", "ko": "최근 활동"},
    "团队最新动态，业务进展一目了然": {"ja": "チームの最新動向、ビジネス進捗が一目でわかる", "ko": "팀 최신 동향, 비즈니스 진행 상황 한눈에 확인"},
    "Latest team activities and business progress": {"ja": "最新のチームアクティビティとビジネス進捗", "ko": "최신 팀 활동 및 비즈니스 진행 상황"},
    "活动记录": {"ja": "アクティビティ記録", "ko": "활동 기록"},
    "Activity Log": {"ja": "アクティビティログ", "ko": "활동 로그"},
    "最近 30 天的业务活动": {"ja": "過去 30 日間のビジネスアクティビティ", "ko": "지난 30 일 동안의 비즈니스 활동"},
    "Business activities in last 30 days": {"ja": "過去 30 日間のビジネスアクティビティ", "ko": "지난 30 일 동안의 비즈니스 활동"},
    "数据管理": {"ja": "データ管理", "ko": "데이터 관리"},
    "Data Management": {"ja": "データ管理", "ko": "데이터 관리"},
    "管理所有业务数据模型": {"ja": "すべてのビジネスデータモデルを管理", "ko": "모든 비즈니스 데이터 모델 관리"},
    "Manage all business data models": {"ja": "すべてのビジネスデータモデルを管理", "ko": "모든 비즈니스 데이터 모델 관리"},
    "数据模型": {"ja": "データモデル", "ko": "데이터 모델"},
    "Data Models": {"ja": "データモデル", "ko": "데이터 모델"},
    "CRM 文档": {"ja": "CRM ドキュメント", "ko": "CRM 문서"},
    "CRM Documentation": {"ja": "CRM ドキュメント", "ko": "CRM 문서"},
    "API 参考": {"ja": "API リファレンス", "ko": "API 참조"},
    "API Reference": {"ja": "API リファレンス", "ko": "API 참조"},
    "用户手册": {"ja": "ユーザーマニュアル", "ko": "사용자 매뉴얼"},
    "User Manual": {"ja": "ユーザーマニュアル", "ko": "사용자 매뉴얼"},
    "关于系统": {"ja": "システムについて", "ko": "시스템 정보"},
    "About": {"ja": "について", "ko": "정보"},
    
    # CRM app.yaml UI Labels
    "客户管理": {"ja": "顧客管理", "ko": "고객 관리"},
    "Customers": {"ja": "顧客", "ko": "고객"},
    "添加客户": {"ja": "顧客を追加", "ko": "고객 추가"},
    "Add Customer": {"ja": "顧客を追加", "ko": "고객 추가"},
    "编辑": {"ja": "編集", "ko": "편집"},
    "Edit": {"ja": "編集", "ko": "편집"},
    "删除": {"ja": "削除", "ko": "삭제"},
    "Delete": {"ja": "削除", "ko": "삭제"},
    "搜索客户...": {"ja": "顧客を検索...", "ko": "고객 검색..."},
    "Search customers...": {"ja": "顧客を検索...", "ko": "고객 검색..."},
    "公司名称": {"ja": "会社名", "ko": "회사 이름"},
    "Company Name": {"ja": "会社名", "ko": "회사 이름"},
    "联系人": {"ja": "連絡先", "ko": "연락처"},
    "Contact Person": {"ja": "担当者", "ko": "연락처 담당자"},
    "行业": {"ja": "業界", "ko": "산업"},
    "Industry": {"ja": "業界", "ko": "산업"},
    "客户等级": {"ja": "顧客等级", "ko": "고객 등급"},
    "Level": {"ja": "レベル", "ko": "레벨"},
    "电话": {"ja": "電話", "ko": "전화"},
    "Phone": {"ja": "電話", "ko": "전화"},
    "邮箱": {"ja": "メール", "ko": "이메일"},
    "Email": {"ja": "メール", "ko": "이메일"},
    "城市": {"ja": "都市", "ko": "도시"},
    "City": {"ja": "都市", "ko": "도시"},
    "状态": {"ja": "ステータス", "ko": "상태"},
    "Status": {"ja": "ステータス", "ko": "상태"},
    "负责人": {"ja": "担当者", "ko": "담당자"},
    "Owner": {"ja": "所有者", "ko": "소유자"},
    "添加艺术家": {"ja": "アーティストを追加", "ko": "아티스트 추가"},
    "Add Artist": {"ja": "アーティストを追加", "ko": "아티스트 추가"},
    "搜索艺术家...": {"ja": "アーティストを検索...", "ko": "아티스트 검색..."},
    "Search artists...": {"ja": "アーティストを検索...", "ko": "아티스트 검색..."},
    "姓名": {"ja": "氏名", "ko": "이름"},
    "Name": {"ja": "名前", "ko": "이름"},
    "编号": {"ja": "番号", "ko": "번호"},
    "Id": {"ja": "ID", "ko": "ID"},
    "操作": {"ja": "操作", "ko": "작업"},
    "Actions": {"ja": "アクション", "ko": "작업"},
    "新增": {"ja": "新規", "ko": "신규"},
    "New": {"ja": "新規", "ko": "신규"},
    "保存": {"ja": "保存", "ko": "저장"},
    "Save": {"ja": "保存", "ko": "저장"},
    "取消": {"ja": "キャンセル", "ko": "취소"},
    "Cancel": {"ja": "キャンセル", "ko": "취소"},
    "专辑": {"ja": "アルバム", "ko": "앨범"},
    "Albums": {"ja": "アルバム", "ko": "앨범"},
    "添加专辑": {"ja": "アルバムを追加", "ko": "앨범 추가"},
    "Add Album": {"ja": "アルバムを追加", "ko": "앨범 추가"},
    "搜索专辑...": {"ja": "アルバムを検索...", "ko": "앨범 검색..."},
    "Search albums...": {"ja": "アルバムを検索...", "ko": "앨범 검색..."},
    "标题": {"ja": "タイトル", "ko": "제목"},
    "Title": {"ja": "タイトル", "ko": "제목"},
    "艺术家 ID": {"ja": "アーティスト ID", "ko": "아티스트 ID"},
    "Artist ID": {"ja": "アーティスト ID", "ko": "아티스트 ID"},
    "音轨": {"ja": "トラック", "ko": "트랙"},
    "Tracks": {"ja": "トラック", "ko": "트랙"},
    "添加音轨": {"ja": "トラックを追加", "ko": "트랙 추가"},
    "Add Track": {"ja": "トラックを追加", "ko": "트랙 추가"},
    "搜索音轨...": {"ja": "トラックを検索...", "ko": "트랙 검색..."},
    "Search tracks...": {"ja": "トラックを検索...", "ko": "트랙 검색..."},
    "名称": {"ja": "名称", "ko": "이름"},
    "专辑 ID": {"ja": "アルバム ID", "ko": "앨범 ID"},
    "Album ID": {"ja": "アルバム ID", "ko": "앨범 ID"},
    "媒体类型 ID": {"ja": "メディアタイプ ID", "ko": "미디어 유형 ID"},
    "Media Type ID": {"ja": "メディアタイプ ID", "ko": "미디어 유형 ID"},
    "流派 ID": {"ja": "ジャンル ID", "ko": "장르 ID"},
    "Genre ID": {"ja": "ジャンル ID", "ko": "장르 ID"},
    "作曲家": {"ja": "作曲家", "ko": "작곡가"},
    "Composer": {"ja": "作曲家", "ko": "작곡가"},
    "毫秒": {"ja": "ミリ秒", "ko": "밀리초"},
    "Milliseconds": {"ja": "ミリ秒", "ko": "밀리초"},
    "字节": {"ja": "バイト", "ko": "바이트"},
    "Bytes": {"ja": "バイト", "ko": "바이트"},
    "单价": {"ja": "単価", "ko": "단가"},
    "Unit Price": {"ja": "単価", "ko": "단가"},
    "员工": {"ja": "従業員", "ko": "직원"},
    "Employees": {"ja": "従業員", "ko": "직원"},
    "添加员工": {"ja": "従業員を追加", "ko": "직원 추가"},
    "Add Employee": {"ja": "従業員を追加", "ko": "직원 추가"},
    "搜索员工...": {"ja": "従業員を検索...", "ko": "직원 검색..."},
    "Search employees...": {"ja": "従業員を検索...", "ko": "직원 검색..."},
    "姓": {"ja": "姓", "ko": "성"},
    "Last Name": {"ja": "姓", "ko": "성"},
    "名": {"ja": "名", "ko": "이름"},
    "First Name": {"ja": "名", "ko": "이름"},
    "职位": {"ja": "職位", "ko": "직위"},
    "Title": {"ja": "職位", "ko": "직위"},
    "上级": {"ja": "上司", "ko": "상사"},
    "Reports To": {"ja": "報告先", "ko": "상사"},
    "出生日期": {"ja": "生年月日", "ko": "생년월일"},
    "Birth Date": {"ja": "生年月日", "ko": "생년월일"},
    "雇佣日期": {"ja": "採用日", "ko": "채용일"},
    "Hire Date": {"ja": "採用日", "ko": "채용일"},
    "地址": {"ja": "住所", "ko": "주소"},
    "Address": {"ja": "住所", "ko": "주소"},
    "州": {"ja": "州", "ko": "주"},
    "State": {"ja": "州", "ko": "주"},
    "国家": {"ja": "国", "ko": "국가"},
    "Country": {"ja": "国", "ko": "국가"},
    "邮政编码": {"ja": "郵便番号", "ko": "우편번호"},
    "Postal Code": {"ja": "郵便番号", "ko": "우편번호"},
    "传真": {"ja": "FAX", "ko": "팩스"},
    "Fax": {"ja": "FAX", "ko": "팩스"},
    "流派": {"ja": "ジャンル", "ko": "장르"},
    "Genres": {"ja": "ジャンル", "ko": "장르"},
    "添加流派": {"ja": "ジャンルを追加", "ko": "장르 추가"},
    "Add Genre": {"ja": "ジャンルを追加", "ko": "장르 추가"},
    "搜索流派...": {"ja": "ジャンルを検索...", "ko": "장르 검색..."},
    "Search genres...": {"ja": "ジャンルを検索...", "ko": "장르 검색..."},
    "媒体类型": {"ja": "メディアタイプ", "ko": "미디어 유형"},
    "Media Types": {"ja": "メディアタイプ", "ko": "미디어 유형"},
    "添加媒体类型": {"ja": "メディアタイプを追加", "ko": "미디어 유형 추가"},
    "Add Media Type": {"ja": "メディアタイプを追加", "ko": "미디어 유형 추가"},
    "搜索媒体类型...": {"ja": "メディアタイプを検索...", "ko": "미디어 유형 검색..."},
    "Search media types...": {"ja": "メディアタイプを検索...", "ko": "미디어 유형 검색..."},
    "发票": {"ja": "請求書", "ko": "송장"},
    "Invoices": {"ja": "請求書", "ko": "송장"},
    "添加发票": {"ja": "請求書を追加", "ko": "송장 추가"},
    "Add Invoice": {"ja": "請求書を追加", "ko": "송장 추가"},
    "搜索发票...": {"ja": "請求書を検索...", "ko": "송장 검색..."},
    "Search invoices...": {"ja": "請求書を検索...", "ko": "송장 검색..."},
    "客户 ID": {"ja": "顧客 ID", "ko": "고객 ID"},
    "Customer ID": {"ja": "顧客 ID", "ko": "고객 ID"},
    "发票日期": {"ja": "請求書日付", "ko": "송장 날짜"},
    "Invoice Date": {"ja": "請求書日付", "ko": "송장 날짜"},
    "账单地址": {"ja": "請求先住所", "ko": "청구 주소"},
    "Billing Address": {"ja": "請求先住所", "ko": "청구 주소"},
    "账单城市": {"ja": "請求先都市", "ko": "청구 도시"},
    "Billing City": {"ja": "請求先都市", "ko": "청구 도시"},
    "账单州": {"ja": "請求先州", "ko": "청구 주"},
    "Billing State": {"ja": "請求先州", "ko": "청구 주"},
    "账单国家": {"ja": "請求先国", "ko": "청구 국가"},
    "Billing Country": {"ja": "請求先国", "ko": "청구 국가"},
    "账单邮政编码": {"ja": "請求先郵便番号", "ko": "청구 우편번호"},
    "Billing Postal Code": {"ja": "請求先郵便番号", "ko": "청구 우편번호"},
    "总计": {"ja": "合計", "ko": "합계"},
    "Total": {"ja": "合計", "ko": "합계"},
    "发票客户视图": {"ja": "請求書顧客ビュー", "ko": "송장 고객 뷰"},
    "Invoice With Customer": {"ja": "顧客付き請求書", "ko": "고객과 함께 송장"},
    "客户": {"ja": "顧客", "ko": "고객"},
    "Customer": {"ja": "顧客", "ko": "고객"},
    "发票日期": {"ja": "請求書日付", "ko": "송장 날짜"},
    "Invoice Date": {"ja": "請求書日付", "ko": "송장 날짜"},
    "国家": {"ja": "国", "ko": "국가"},
    "Country": {"ja": "国", "ko": "국가"},
    "总计": {"ja": "合計", "ko": "합계"},
    "Total": {"ja": "合計", "ko": "합계"},
    
    # Chinook home.yaml
    "Chinook 音乐商店": {"ja": "Chinook ミュージックストア", "ko": "Chinook 음악 상점"},
    "Chinook Music Store": {"ja": "Chinook ミュージックストア", "ko": "Chinook 음악 상점"},
    "探索音乐世界，管理艺术家、专辑和音轨": {"ja": "音楽の世界を探検、アーティスト、アルバム、トラックを管理", "ko": "음악 세계 탐험, 아티스트, 앨범, 트랙 관리"},
    "Explore the world of music - artists, albums, and tracks": {"ja": "音楽の世界を探検 - アーティスト、アルバム、トラック", "ko": "음악의 세계 탐험 - 아티스트, 앨범, 트랙"},
    "音乐导航": {"ja": "ミュージックナビゲーション", "ko": "음악 탐색"},
    "Music Navigation": {"ja": "ミュージックナビゲーション", "ko": "음악 탐색"},
    "艺术家": {"ja": "アーティスト", "ko": "아티스트"},
    "Artists": {"ja": "アーティスト", "ko": "아티스트"},
    "管理艺术家信息": {"ja": "アーティスト情報を管理", "ko": "아티스트 정보 관리"},
    "Manage artist info": {"ja": "アーティスト情報を管理", "ko": "아티스트 정보 관리"},
    "专辑": {"ja": "アルバム", "ko": "앨범"},
    "Albums": {"ja": "アルバム", "ko": "앨범"},
    "浏览专辑收藏": {"ja": "アルバムコレクションを閲覧", "ko": "앨범 컬렉션 탐색"},
    "Browse album collection": {"ja": "アルバムコレクションを閲覧", "ko": "앨범 컬렉션 탐색"},
    "音轨": {"ja": "トラック", "ko": "트랙"},
    "Tracks": {"ja": "トラック", "ko": "트랙"},
    "管理音乐音轨": {"ja": "音楽トラックを管理", "ko": "음악 트랙 관리"},
    "Manage music tracks": {"ja": "音楽トラックを管理", "ko": "음악 트랙 관리"},
    "媒体类型": {"ja": "メディアタイプ", "ko": "미디어 유형"},
    "Media Types": {"ja": "メディアタイプ", "ko": "미디어 유형"},
    "查看媒体格式": {"ja": "メディアフォーマットを表示", "ko": "미디어 형식 보기"},
    "View media formats": {"ja": "メディアフォーマットを表示", "ko": "미디어 형식 보기"},
    "音乐库管理": {"ja": "ミュージックライブラリ管理", "ko": "음악 라이브러리 관리"},
    "Music Library": {"ja": "ミュージックライブラリ", "ko": "음악 라이브러리"},
    "管理您的音乐收藏": {"ja": "音楽コレクションを管理", "ko": "음악 컬렉션 관리"},
    "Manage your music collection": {"ja": "音楽コレクションを管理", "ko": "음악 컬렉션 관리"},
    "高级管理": {"ja": "高度な管理", "ko": "고급 관리"},
    "Advanced Management": {"ja": "高度な管理", "ko": "고급 관리"},
    "多表关联视图": {"ja": "マルチテーブル関連ビュー", "ko": "다중 테이블 관련 뷰"},
    "Multi-table views": {"ja": "マルチテーブルビュー", "ko": "다중 테이블 뷰"},
    "多表页面": {"ja": "マルチテーブルページ", "ko": "다중 테이블 페이지"},
    "Multi-Table Pages": {"ja": "マルチテーブルページ", "ko": "다중 테이블 페이지"},
    "音乐库统计": {"ja": "ミュージックライブラリ統計", "ko": "음악 라이브러리 통계"},
    "Library Statistics": {"ja": "ライブラリ統計", "ko": "라이브러리 통계"},
    "数据模型": {"ja": "データモデル", "ko": "데이터 모델"},
    "Data Models": {"ja": "データモデル", "ko": "데이터 모델"},
    "多表页面": {"ja": "マルチテーブルページ", "ko": "다중 테이블 페이지"},
    "Multi-Table Pages": {"ja": "マルチテーブルページ", "ko": "다중 테이블 페이지"},
    "可用项目": {"ja": "利用可能なプロジェクト", "ko": "사용 가능한 프로젝트"},
    "Projects": {"ja": "プロジェクト", "ko": "프로젝트"},
    "低代码": {"ja": "ローコード", "ko": "로우코드"},
    "Low-Code": {"ja": "ローコード", "ko": "로우코드"},
    "更多项目": {"ja": "その他のプロジェクト", "ko": "더 많은 프로젝트"},
    "More Projects": {"ja": "その他のプロジェクト", "ko": "더 많은 프로젝트"},
    "探索其他功能": {"ja": "その他の機能を探検", "ko": "다른 기능 탐색"},
    "Explore other features": {"ja": "その他の機能を探検", "ko": "다른 기능 탐색"},
    "可用项目": {"ja": "利用可能なプロジェクト", "ko": "사용 가능한 프로젝트"},
    "Available Projects": {"ja": "利用可能なプロジェクト", "ko": "사용 가능한 프로젝트"},
    "TODO 项目管理": {"ja": "TODO プロジェクト管理", "ko": "TODO 프로젝트 관리"},
    "TODO Project": {"ja": "TODO プロジェクト", "ko": "TODO 프로젝트"},
    "任务和项目管理系统": {"ja": "タスクとプロジェクト管理システム", "ko": "작업 및 프로젝트 관리 시스템"},
    "Task and Project Management": {"ja": "タスクとプロジェクト管理", "ko": "작업 및 프로젝트 관리"},
    "任务管理": {"ja": "タスク管理", "ko": "작업 관리"},
    "Task Management": {"ja": "タスク管理", "ko": "작업 관리"},
    "项目跟踪": {"ja": "プロジェクト追跡", "ko": "프로젝트 추적"},
    "Project Tracking": {"ja": "プロジェクト追跡", "ko": "프로젝트 추적"},
    "状态流转": {"ja": "ステータスフロー", "ko": "상태 흐름"},
    "Status Flow": {"ja": "ステータスフロー", "ko": "상태 흐름"},
    "我的日记本": {"ja": "マイジャーナル", "ko": "내 일기장"},
    "My Journal": {"ja": "マイジャーナル", "ko": "내 일기장"},
    "个人日记管理系统": {"ja": "パーソナルジャーナル管理システム", "ko": "개인 일기장 관리 시스템"},
    "Personal Journal System": {"ja": "パーソナルジャーナルシステム", "ko": "개인 일기장 시스템"},
    "日记记录": {"ja": "日記記録", "ko": "일기 기록"},
    "Journal Entries": {"ja": "日記エントリー", "ko": "일기 항목"},
    "分类管理": {"ja": "カテゴリ管理", "ko": "카테고리 관리"},
    "Category Management": {"ja": "カテゴリ管理", "ko": "카테고리 관리"},
    "心情追踪": {"ja": "気分トラッキング", "ko": "기분 추적"},
    "Mood Tracking": {"ja": "気分トラッキング", "ko": "기분 추적"},
    "音乐库管理": {"ja": "ミュージックライブラリ管理", "ko": "음악 라이브러리 관리"},
    "Music Library Management": {"ja": "ミュージックライブラリ管理", "ko": "음악 라이브러리 관리"},
    "多表关联": {"ja": "マルチテーブル関連", "ko": "다중 테이블 관계"},
    "Multi-table Relationships": {"ja": "マルチテーブルリレーションシップ", "ko": "다중 테이블 관계"},
    "只读视图": {"ja": "読み取り専用ビュー", "ko": "읽기 전용 뷰"},
    "Read-only Views": {"ja": "読み取り専用ビュー", "ko": "읽기 전용 뷰"},
    "电商订单系统": {"ja": "E コマース注文システム", "ko": "이커머스 주문 시스템"},
    "E-commerce Order System": {"ja": "E コマース注文システム", "ko": "이커머스 주문 시스템"},
    "产品、订单、客户管理": {"ja": "製品、注文、顧客管理", "ko": "제품, 주문, 고객 관리"},
    "Product, Order, Customer": {"ja": "製品、注文、顧客", "ko": "제품, 주문, 고객"},
    "产品管理": {"ja": "製品管理", "ko": "제품 관리"},
    "Product Management": {"ja": "製品管理", "ko": "제품 관리"},
    "订单处理": {"ja": "注文処理", "ko": "주문 처리"},
    "Order Processing": {"ja": "注文処理", "ko": "주문 처리"},
    "库存跟踪": {"ja": "在庫追跡", "ko": "재고 추적"},
    "Inventory Tracking": {"ja": "在庫追跡", "ko": "재고 추적"},
    "CRM 客户管理": {"ja": "CRM 顧客管理", "ko": "CRM 고객 관리"},
    "CRM": {"ja": "CRM", "ko": "CRM"},
    "客户、销售机会、订单管理": {"ja": "顧客、販売機会、注文管理", "ko": "고객, 판매 기회, 주문 관리"},
    "Customer, Opportunity, Order": {"ja": "顧客、商談、注文", "ko": "고객, 기회, 주문"},
    "客户 360°视图": {"ja": "顧客 360°ビュー", "ko": "고객 360° 뷰"},
    "Customer 360° View": {"ja": "顧客 360°ビュー", "ko": "고객 360° 뷰"},
    "销售漏斗": {"ja": "販売ファネル", "ko": "판매 퍼널"},
    "Sales Pipeline": {"ja": "販売パイプライン", "ko": "판매 파이프라인"},
    "报价订单": {"ja": "見積もり注文", "ko": "견적 주문"},
    "Quote and Order": {"ja": "見積もり注文", "ko": "견적 및 주문"},
    "音乐文档": {"ja": "ミュージックドキュメント", "ko": "음악 문서"},
    "Music Docs": {"ja": "ミュージックドキュメント", "ko": "음악 문서"},
    "API": {"ja": "API", "ko": "API"},
    "关于": {"ja": "について", "ko": "정보"},
    "About": {"ja": "について", "ko": "정보"},
}

def add_translations_to_multilingual_block(lines, start_idx):
    """在多语言块中添加 ja 和 ko 翻译"""
    result = []
    i = start_idx
    
    # 收集 zh 和 en 的内容
    zh_text = None
    en_text = None
    base_indent = ""
    
    # 查找当前块的 zh 和 en 值
    while i < len(lines):
        line = lines[i]
        stripped = line.strip()
        
        # 检查是否是新的多语言块开始
        if stripped and not stripped.startswith('#') and ':' in line:
            indent_match = re.match(r'^(\s*)', line)
            current_indent = indent_match.group(1) if indent_match else ""
            
            # 如果缩进比基础缩进小或相等，说明块结束
            if base_indent and len(current_indent) <= len(base_indent) and stripped != 'zh:' and stripped != 'en:':
                break
            
            if stripped.startswith('zh:'):
                zh_text = line.split(':', 1)[1].strip()
                if not base_indent:
                    base_indent = current_indent
            elif stripped.startswith('en:'):
                en_text = line.split(':', 1)[1].strip()
                if not base_indent:
                    base_indent = current_indent
            elif stripped.startswith('ja:') or stripped.startswith('ko:'):
                # 已经有 ja 或 ko，跳过
                pass
            else:
                # 其他内容，块结束
                break
        
        i += 1
    
    # 查找翻译
    ja_text = None
    ko_text = None
    
    if zh_text and zh_text in TRANSLATIONS:
        ja_text = TRANSLATIONS[zh_text].get('ja')
        ko_text = TRANSLATIONS[zh_text].get('ko')
    elif en_text and en_text in TRANSLATIONS:
        ja_text = TRANSLATIONS[en_text].get('ja')
        ko_text = TRANSLATIONS[en_text].get('ko')
    
    # 如果没有找到翻译，使用原文本
    if not ja_text:
        ja_text = zh_text or en_text or ""
    if not ko_text:
        ko_text = zh_text or en_text or ""
    
    return ja_text, ko_text, base_indent, i

def process_yaml_content(content):
    """处理 YAML 内容，添加 ja 和 ko 翻译"""
    lines = content.split('\n')
    result_lines = []
    i = 0
    
    while i < len(lines):
        line = lines[i]
        stripped = line.strip()
        
        # 检查是否是 zh: 行
        if stripped.startswith('zh:'):
            # 查找对应的 en: 行
            zh_text = line.split(':', 1)[1].strip()
            indent_match = re.match(r'^(\s*)', line)
            base_indent = indent_match.group(1) if indent_match else ""
            
            en_text = None
            has_ja = False
            has_ko = False
            
            # 检查后续行
            j = i + 1
            while j < len(lines):
                next_line = lines[j]
                next_stripped = next_line.strip()
                
                if next_stripped.startswith('en:'):
                    en_text = next_line.split(':', 1)[1].strip()
                elif next_stripped.startswith('ja:'):
                    has_ja = True
                elif next_stripped.startswith('ko:'):
                    has_ko = False
                    # 检查是否需要替换
                elif next_stripped and not next_stripped.startswith('#'):
                    # 非空非注释行，块结束
                    break
                j += 1
            
            # 如果已经有 ja 和 ko，跳过
            if has_ja and has_ko:
                result_lines.append(line)
                i += 1
                continue
            
            # 查找翻译
            ja_text = None
            ko_text = None
            
            if zh_text in TRANSLATIONS:
                ja_text = TRANSLATIONS[zh_text].get('ja')
                ko_text = TRANSLATIONS[zh_text].get('ko')
            elif en_text and en_text in TRANSLATIONS:
                ja_text = TRANSLATIONS[en_text].get('ja')
                ko_text = TRANSLATIONS[en_text].get('ko')
            
            # 如果没有找到翻译，使用原文本
            if not ja_text:
                ja_text = zh_text or en_text or ""
            if not ko_text:
                ko_text = zh_text or en_text or ""
            
            # 添加当前行
            result_lines.append(line)
            
            # 查找 en: 行并添加
            if en_text is not None:
                # 找到 en: 行的位置
                j = i + 1
                while j < len(lines):
                    next_line = lines[j]
                    next_stripped = next_line.strip()
                    
                    if next_stripped.startswith('en:'):
                        result_lines.append(next_line)
                        # 在 en: 后添加 ja: 和 ko:
                        result_lines.append(f'{base_indent}ja: {ja_text}')
                        result_lines.append(f'{base_indent}ko: {ko_text}')
                        i = j + 1
                        break
                    elif next_stripped and not next_stripped.startswith('#'):
                        # 没有找到 en:，直接在 zh: 后添加
                        result_lines.append(f'{base_indent}en: {en_text or zh_text}')
                        result_lines.append(f'{base_indent}ja: {ja_text}')
                        result_lines.append(f'{base_indent}ko: {ko_text}')
                        i = j
                        break
                    else:
                        j += 1
                else:
                    # 到达文件末尾
                    result_lines.append(f'{base_indent}en: {en_text or zh_text}')
                    result_lines.append(f'{base_indent}ja: {ja_text}')
                    result_lines.append(f'{base_indent}ko: {ko_text}')
                    i = j
            else:
                i += 1
        else:
            result_lines.append(line)
            i += 1
    
    return '\n'.join(result_lines)

def process_yaml_file(filepath):
    """处理单个 YAML 文件"""
    print(f"处理文件：{filepath}")
    
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        new_content = process_yaml_content(content)
        
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        
        print(f"  ✓ 完成：{filepath}")
        return True
    except Exception as e:
        print(f"  ✗ 错误：{filepath} - {str(e)}")
        return False

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
    
    success_count = 0
    fail_count = 0
    
    for filepath in files:
        if os.path.exists(filepath):
            if process_yaml_file(filepath):
                success_count += 1
            else:
                fail_count += 1
        else:
            print(f"  ✗ 文件不存在：{filepath}")
            fail_count += 1
    
    print(f"\n{'='*50}")
    print(f"处理完成！成功：{success_count}, 失败：{fail_count}")
    print(f"{'='*50}")

if __name__ == '__main__':
    main()
