#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
为 app.yaml 文件中的多语言定义添加日文 (ja) 和韩文 (ko) 翻译
支持块状结构处理
"""

import re
import os

# ==================== 通用术语翻译 ====================
COMMON_TRANSLATIONS = {
    # 操作按钮
    "添加": {"ja": "追加", "ko": "추가"},
    "Add": {"ja": "追加", "ko": "추가"},
    "编辑": {"ja": "編集", "ko": "편집"},
    "Edit": {"ja": "編集", "ko": "편집"},
    "删除": {"ja": "削除", "ko": "삭제"},
    "Delete": {"ja": "削除", "ko": "삭제"},
    "创建": {"ja": "作成", "ko": "생성"},
    "Create": {"ja": "作成", "ko": "생성"},
    "保存": {"ja": "保存", "ko": "저장"},
    "Save": {"ja": "保存", "ko": "저장"},
    "取消": {"ja": "キャンセル", "ko": "취소"},
    "Cancel": {"ja": "キャンセル", "ko": "취소"},
    "搜索": {"ja": "検索", "ko": "검색"},
    "Search": {"ja": "検索", "ko": "검색"},
    "新建": {"ja": "新規", "ko": "새로 만들기"},
    "New": {"ja": "新規", "ko": "새로 만들기"},
    
    # 通用标签
    "标题": {"ja": "タイトル", "ko": "제목"},
    "Title": {"ja": "タイトル", "ko": "제목"},
    "名称": {"ja": "名称", "ko": "이름"},
    "Name": {"ja": "名称", "ko": "이름"},
    "描述": {"ja": "説明", "ko": "설명"},
    "Description": {"ja": "説明", "ko": "설명"},
    "状态": {"ja": "ステータス", "ko": "상태"},
    "Status": {"ja": "ステータス", "ko": "상태"},
    "类型": {"ja": "タイプ", "ko": "유형"},
    "Type": {"ja": "タイプ", "ko": "유형"},
    "编号": {"ja": "番号", "ko": "번호"},
    "ID": {"ja": "ID", "ko": "ID"},
    "No": {"ja": "番号", "ko": "번호"},
    
    # 时间相关
    "日期": {"ja": "日付", "ko": "날짜"},
    "Date": {"ja": "日付", "ko": "날짜"},
    "时间": {"ja": "時間", "ko": "시간"},
    "Time": {"ja": "時間", "ko": "시간"},
    "创建时间": {"ja": "作成日時", "ko": "생성 일시"},
    "Created": {"ja": "作成日", "ko": "생성일"},
    "更新时间": {"ja": "更新日時", "ko": "업데이트 일시"},
    "Updated": {"ja": "更新日", "ko": "업데이트일"},
    
    # 其他通用
    "管理": {"ja": "管理", "ko": "관리"},
    "Management": {"ja": "管理", "ko": "관리"},
    "信息": {"ja": "情報", "ko": "정보"},
    "Info": {"ja": "情報", "ko": "정보"},
    "详情": {"ja": "詳細", "ko": "세부 정보"},
    "Details": {"ja": "詳細", "ko": "세부 정보"},
    "所有": {"ja": "すべて", "ko": "모두"},
    "All": {"ja": "すべて", "ko": "모두"},
}

# ==================== CRM 专用翻译 ====================
CRM_TRANSLATIONS = {
    # Customer
    "客户管理": {"ja": "顧客管理", "ko": "고객 관리"},
    "Customers": {"ja": "顧客", "ko": "고객"},
    "添加客户": {"ja": "顧客を追加", "ko": "고객 추가"},
    "Add Customer": {"ja": "顧客を追加", "ko": "고객 추가"},
    "搜索客户...": {"ja": "顧客を検索...", "ko": "고객 검색..."},
    "Search customers...": {"ja": "顧客を検索...", "ko": "고객 검색..."},
    "公司名称": {"ja": "会社名", "ko": "회사명"},
    "Company Name": {"ja": "会社名", "ko": "회사명"},
    "联系人": {"ja": "連絡先担当者", "ko": "연락처 담당자"},
    "Contact Person": {"ja": "連絡先担当者", "ko": "연락처 담당자"},
    "行业": {"ja": "業界", "ko": "업종"},
    "Industry": {"ja": "業界", "ko": "업종"},
    "客户等级": {"ja": "顧客ランク", "ko": "고객 등급"},
    "Level": {"ja": "ランク", "ko": "등급"},
    "电话": {"ja": "電話", "ko": "전화"},
    "Phone": {"ja": "電話", "ko": "전화"},
    "邮箱": {"ja": "メール", "ko": "이메일"},
    "Email": {"ja": "メール", "ko": "이메일"},
    "城市": {"ja": "都市", "ko": "도시"},
    "City": {"ja": "都市", "ko": "도시"},
    "负责人": {"ja": "担当者", "ko": "담당자"},
    "Owner": {"ja": "担当者", "ko": "담당자"},
    "规模": {"ja": "規模", "ko": "규모"},
    "Scale": {"ja": "規模", "ko": "규모"},
    "来源": {"ja": "ソース", "ko": "소스"},
    "Source": {"ja": "ソース", "ko": "소스"},
    "网站": {"ja": "ウェブサイト", "ko": "웹사이트"},
    "Website": {"ja": "ウェブサイト", "ko": "웹사이트"},
    "地址": {"ja": "住所", "ko": "주소"},
    "Address": {"ja": "住所", "ko": "주소"},
    "国家": {"ja": "国", "ko": "국가"},
    "Country": {"ja": "国", "ko": "국가"},
    "州": {"ja": "州", "ko": "주"},
    "State": {"ja": "州", "ko": "주"},
    "邮政编码": {"ja": "郵便番号", "ko": "우편번호"},
    "Postal Code": {"ja": "郵便番号", "ko": "우편번호"},
    "传真": {"ja": "ファクス", "ko": "팩스"},
    "Fax": {"ja": "ファクス", "ko": "팩스"},
    "活跃": {"ja": "アクティブ", "ko": "활성"},
    "Active": {"ja": "アクティブ", "ko": "활성"},
    "不活跃": {"ja": "非アクティブ", "ko": "비활성"},
    "Inactive": {"ja": "非アクティブ", "ko": "비활성"},
    "丢失": {"ja": "喪失", "ko": "분실"},
    "Lost": {"ja": "喪失", "ko": "분실"},
    
    # Contact
    "联系人管理": {"ja": "連絡先管理", "ko": "연락처 관리"},
    "Contacts": {"ja": "連絡先", "ko": "연락처"},
    "添加联系人": {"ja": "連絡先を追加", "ko": "연락처 추가"},
    "Add Contact": {"ja": "連絡先を追加", "ko": "연락처 추가"},
    "搜索联系人...": {"ja": "連絡先を検索...", "ko": "연락처 검색..."},
    "Search contacts...": {"ja": "連絡先を検索...", "ko": "연락처 검색..."},
    "名": {"ja": "名", "ko": "이름"},
    "First Name": {"ja": "名", "ko": "이름"},
    "姓": {"ja": "姓", "ko": "성"},
    "Last Name": {"ja": "姓", "ko": "성"},
    "姓名": {"ja": "氏名", "ko": "성명"},
    "Full Name": {"ja": "氏名", "ko": "성명"},
    "职位": {"ja": "役職", "ko": "직함"},
    "Title": {"ja": "役職", "ko": "직함"},
    "部门": {"ja": "部門", "ko": "부문"},
    "Department": {"ja": "部門", "ko": "부문"},
    "手机": {"ja": "携帯電話", "ko": "휴대전화"},
    "Mobile": {"ja": "携帯電話", "ko": "휴대전화"},
    "所属客户": {"ja": "所属顧客", "ko": "소속 고객"},
    "Customer": {"ja": "顧客", "ko": "고객"},
    "主要联系人": {"ja": "主要連絡先", "ko": "주요 연락처"},
    "Primary": {"ja": "主要", "ko": "주요"},
    "IsPrimary": {"ja": "主要連絡先", "ko": "주요 연락처"},
    
    # Opportunity
    "销售机会": {"ja": "販売機会", "ko": "판매 기회"},
    "Opportunities": {"ja": "販売機会", "ko": "판매 기회"},
    "添加机会": {"ja": "機会を追加", "ko": "기회 추가"},
    "Add Opportunity": {"ja": "機会を追加", "ko": "기회 추가"},
    "搜索机会...": {"ja": "機会を検索...", "ko": "기회 검색..."},
    "Search opportunities...": {"ja": "機会を検索...", "ko": "기회 검색..."},
    "机会名称": {"ja": "機会名", "ko": "기회 이름"},
    "客户": {"ja": "顧客", "ko": "고객"},
    "阶段": {"ja": "段階", "ko": "단계"},
    "Stage": {"ja": "段階", "ko": "단계"},
    "成功概率": {"ja": "成功確率", "ko": "성공 확률"},
    "Probability": {"ja": "確率", "ko": "확률"},
    "预计金额": {"ja": "予想金額", "ko": "예상 금액"},
    "Amount": {"ja": "金額", "ko": "금액"},
    "预计成交日期": {"ja": "予想成約日", "ko": "예상 성사 날짜"},
    "Expected Close Date": {"ja": "予想成約日", "ko": "예상 성사 날짜"},
    "优先级": {"ja": "優先度", "ko": "우선순위"},
    "Priority": {"ja": "優先度", "ko": "우선순위"},
    "开放": {"ja": "オープン", "ko": "오픈"},
    "Open": {"ja": "オープン", "ko": "오픈"},
    "赢": {"ja": "勝利", "ko": "승리"},
    "Won": {"ja": "勝利", "ko": "승리"},
    "输": {"ja": "敗北", "ko": "패배"},
    "Closed Lost": {"ja": "敗北", "ko": "패배"},
    "关闭": {"ja": "クローズ", "ko": "종료"},
    "Closed": {"ja": "クローズ", "ko": "종료"},
    "低": {"ja": "低", "ko": "낮음"},
    "Low": {"ja": "低", "ko": "낮음"},
    "中": {"ja": "中", "ko": "중간"},
    "Medium": {"ja": "中", "ko": "중간"},
    "高": {"ja": "高", "ko": "높음"},
    "High": {"ja": "高", "ko": "높음"},
    "紧急": {"ja": "緊急", "ko": "긴급"},
    "Urgent": {"ja": "緊急", "ko": "긴급"},
    "下一步": {"ja": "次のステップ", "ko": "다음 단계"},
    "Next Step": {"ja": "次のステップ", "ko": "다음 단계"},
    "竞争者": {"ja": "競合他社", "ko": "경쟁사"},
    "Competitor": {"ja": "競合他社", "ko": "경쟁사"},
    "成交原因": {"ja": "成約理由", "ko": "성사 이유"},
    "Close Reason": {"ja": "成約理由", "ko": "성사 이유"},
    "实际成交日期": {"ja": "実際成約日", "ko": "실제 성사 날짜"},
    "Actual Close Date": {"ja": "実際成約日", "ko": "실제 성사 날짜"},
    
    # Product
    "产品管理": {"ja": "製品管理", "ko": "제품 관리"},
    "Products": {"ja": "製品", "ko": "제품"},
    "添加产品": {"ja": "製品を追加", "ko": "제품 추가"},
    "Add Product": {"ja": "製品を追加", "ko": "제품 추가"},
    "搜索产品...": {"ja": "製品を検索...", "ko": "제품 검색..."},
    "Search products...": {"ja": "製品を検索...", "ko": "제품 검색..."},
    "产品名称": {"ja": "製品名", "ko": "제품 이름"},
    "Product Name": {"ja": "製品名", "ko": "제품 이름"},
    "分类": {"ja": "カテゴリ", "ko": "분류"},
    "Category": {"ja": "カテゴリ", "ko": "분류"},
    "单价": {"ja": "単価", "ko": "단가"},
    "Unit Price": {"ja": "単価", "ko": "단가"},
    "库存": {"ja": "在庫", "ko": "재고"},
    "Stock": {"ja": "在庫", "ko": "재고"},
    "启用": {"ja": "有効", "ko": "활성화"},
    "IsActive": {"ja": "有効", "ko": "활성화"},
    "规格": {"ja": "仕様", "ko": "규격"},
    "Specification": {"ja": "仕様", "ko": "규격"},
    "单位": {"ja": "単位", "ko": "단위"},
    "Unit": {"ja": "単位", "ko": "단위"},
    "成本价": {"ja": "原価", "ko": "원가"},
    "Cost Price": {"ja": "原価", "ko": "원가"},
    "库存数量": {"ja": "在庫数", "ko": "재고 수량"},
    "Stock Quantity": {"ja": "在庫数", "ko": "재고 수량"},
    "再订购级别": {"ja": "再注文レベル", "ko": "재주문 수준"},
    "Reorder Level": {"ja": "再注文レベル", "ko": "재주문 수준"},
    "供应商": {"ja": "サプライヤー", "ko": "공급업체"},
    "Supplier": {"ja": "サプライヤー", "ko": "공급업체"},
    
    # Quote
    "报价管理": {"ja": "見積もり管理", "ko": "견적 관리"},
    "Quotes": {"ja": "見積もり", "ko": "견적"},
    "创建报价": {"ja": "見積もりを作成", "ko": "견적 생성"},
    "Create Quote": {"ja": "見積もりを作成", "ko": "견적 생성"},
    "搜索报价...": {"ja": "見積もりを検索...", "ko": "견적 검색..."},
    "Search quotes...": {"ja": "見積もりを検索...", "ko": "견적 검색..."},
    "报价单号": {"ja": "見積もり番号", "ko": "견적 번호"},
    "Quote No": {"ja": "見積もり番号", "ko": "견적 번호"},
    "报价日期": {"ja": "見積もり日", "ko": "견적 날짜"},
    "Quote Date": {"ja": "見積もり日", "ko": "견적 날짜"},
    "总金额": {"ja": "合計金額", "ko": "총 금액"},
    "Total Amount": {"ja": "合計金額", "ko": "총 금액"},
    "小计": {"ja": "小計", "ko": "소계"},
    "Subtotal": {"ja": "小計", "ko": "소계"},
    "折扣": {"ja": "割引", "ko": "할인"},
    "Discount": {"ja": "割引", "ko": "할인"},
    "税": {"ja": "税", "ko": "세금"},
    "Tax": {"ja": "税", "ko": "세금"},
    "到期日期": {"ja": "有効期限", "ko": "만료 날짜"},
    "Expiry Date": {"ja": "有効期限", "ko": "만료 날짜"},
    "草稿": {"ja": "下書き", "ko": "초안"},
    "Draft": {"ja": "下書き", "ko": "초안"},
    "已发送": {"ja": "送信済み", "ko": "전송됨"},
    "Sent": {"ja": "送信済み", "ko": "전송됨"},
    "已接受": {"ja": "承認済み", "ko": "승인됨"},
    "Accepted": {"ja": "承認済み", "ko": "승인됨"},
    "已拒绝": {"ja": "拒否済み", "ko": "거부됨"},
    "Rejected": {"ja": "拒否済み", "ko": "거부됨"},
    "已过期": {"ja": "期限切れ", "ko": "만료됨"},
    "Expired": {"ja": "期限切れ", "ko": "만료됨"},
    "备注": {"ja": "備考", "ko": "비고"},
    "Notes": {"ja": "備考", "ko": "비고"},
    "条款": {"ja": "規約", "ko": "약관"},
    "Terms": {"ja": "規約", "ko": "약관"},
    
    # Order
    "订单管理": {"ja": "注文管理", "ko": "주문 관리"},
    "Orders": {"ja": "注文", "ko": "주문"},
    "创建订单": {"ja": "注文を作成", "ko": "주문 생성"},
    "Create Order": {"ja": "注文を作成", "ko": "주문 생성"},
    "搜索订单...": {"ja": "注文を検索...", "ko": "주문 검색..."},
    "Search orders...": {"ja": "注文を検索...", "ko": "주문 검색..."},
    "订单号": {"ja": "注文番号", "ko": "주문 번호"},
    "Order No": {"ja": "注文番号", "ko": "주문 번호"},
    "订单日期": {"ja": "注文日", "ko": "주문 날짜"},
    "Order Date": {"ja": "注文日", "ko": "주문 날짜"},
    "交付日期": {"ja": "納品日", "ko": "납품 날짜"},
    "Delivery Date": {"ja": "納品日", "ko": "납품 날짜"},
    "待处理": {"ja": "保留中", "ko": "보류 중"},
    "Pending": {"ja": "保留中", "ko": "보류 중"},
    "处理中": {"ja": "処理中", "ko": "처리 중"},
    "Processing": {"ja": "処理中", "ko": "처리 중"},
    "已发货": {"ja": "発送済み", "ko": "발송됨"},
    "Shipped": {"ja": "発送済み", "ko": "발송됨"},
    "已完成": {"ja": "完了", "ko": "완료"},
    "Completed": {"ja": "完了", "ko": "완료"},
    "已取消": {"ja": "キャンセル済み", "ko": "취소됨"},
    "Cancelled": {"ja": "キャンセル済み", "ko": "취소됨"},
}

# ==================== TODO 专用翻译 ====================
TODO_TRANSLATIONS = {
    "任务管理": {"ja": "タスク管理", "ko": "작업 관리"},
    "Task Management": {"ja": "タスク管理", "ko": "작업 관리"},
    "任务": {"ja": "タスク", "ko": "작업"},
    "Task": {"ja": "タスク", "ko": "작업"},
    "项目": {"ja": "プロジェクト", "ko": "프로젝트"},
    "Project": {"ja": "プロジェクト", "ko": "프로젝트"},
    "添加任务": {"ja": "タスクを追加", "ko": "작업 추가"},
    "Add Task": {"ja": "タスクを追加", "ko": "작업 추가"},
    "搜索任务...": {"ja": "タスクを検索...", "ko": "작업 검색..."},
    "Search tasks...": {"ja": "タスクを検索...", "ko": "작업 검색..."},
    "任务名称": {"ja": "タスク名", "ko": "작업 이름"},
    "Task Name": {"ja": "タスク名", "ko": "작업 이름"},
    "内容": {"ja": "内容", "ko": "내용"},
    "Content": {"ja": "内容", "ko": "내용"},
    "截止日期": {"ja": "締切日", "ko": "마감일"},
    "Due Date": {"ja": "締切日", "ko": "마감일"},
    "完成": {"ja": "完了", "ko": "완료"},
    "Completed": {"ja": "完了", "ko": "완료"},
    "未完成": {"ja": "未完了", "ko": "미완료"},
    "Incomplete": {"ja": "未完了", "ko": "미완료"},
    "待处理": {"ja": "保留中", "ko": "보류 중"},
    "进行中": {"ja": "進行中", "ko": "진행 중"},
    "In Progress": {"ja": "進行中", "ko": "진행 중"},
    "已完成": {"ja": "完了", "ko": "완료"},
    "Done": {"ja": "完了", "ko": "완료"},
    "项目管理": {"ja": "プロジェクト管理", "ko": "프로젝트 관리"},
    "Projects": {"ja": "プロジェクト", "ko": "프로젝트"},
    "项目名称": {"ja": "プロジェクト名", "ko": "프로젝트 이름"},
    "Project Name": {"ja": "プロジェクト名", "ko": "프로젝트 이름"},
    "开始日期": {"ja": "開始日", "ko": "시작일"},
    "Start Date": {"ja": "開始日", "ko": "시작일"},
    "结束日期": {"ja": "終了日", "ko": "종료일"},
    "End Date": {"ja": "終了日", "ko": "종료일"},
}

# ==================== Journal 专用翻译 ====================
JOURNAL_TRANSLATIONS = {
    "日记管理": {"ja": "ジャーナル管理", "ko": "일기장 관리"},
    "Journal": {"ja": "ジャーナル", "ko": "일기장"},
    "日记": {"ja": "日記", "ko": "일기"},
    "Entry": {"ja": "エントリー", "ko": "일기"},
    "添加日记": {"ja": "日記を追加", "ko": "일기 추가"},
    "Add Entry": {"ja": "エントリーを追加", "ko": "일기 추가"},
    "搜索日记...": {"ja": "日記を検索...", "ko": "일기 검색..."},
    "Search entries...": {"ja": "エントリーを検索...", "ko": "일기 검색..."},
    "标题": {"ja": "タイトル", "ko": "제목"},
    "内容": {"ja": "内容", "ko": "내용"},
    "日期": {"ja": "日付", "ko": "날짜"},
    "心情": {"ja": "気分", "ko": "기분"},
    "Mood": {"ja": "気分", "ko": "기분"},
    "开心": {"ja": "嬉しい", "ko": "기쁨"},
    "Happy": {"ja": "嬉しい", "ko": "기쁨"},
    "平静": {"ja": "平静", "ko": "평온"},
    "Calm": {"ja": "平静", "ko": "평온"},
    "悲伤": {"ja": "悲しい", "ko": "슬픔"},
    "Sad": {"ja": "悲しい", "ko": "슬픔"},
    "焦虑": {"ja": "不安", "ko": "불안"},
    "Anxious": {"ja": "不安", "ko": "불안"},
    "愤怒": {"ja": "怒り", "ko": "분노"},
    "Angry": {"ja": "怒り", "ko": "분노"},
    "分类": {"ja": "カテゴリ", "ko": "카테고리"},
    "Category": {"ja": "カテゴリ", "ko": "카테고리"},
    "标签": {"ja": "タグ", "ko": "태그"},
    "Tag": {"ja": "タグ", "ko": "태그"},
    "公开": {"ja": "公開", "ko": "공개"},
    "Public": {"ja": "公開", "ko": "공개"},
    "私有": {"ja": "非公開", "ko": "비공개"},
    "Private": {"ja": "非公開", "ko": "비공개"},
}

# ==================== E-commerce 专用翻译 ====================
ECOMMERCE_TRANSLATIONS = {
    "电商管理": {"ja": "E コマース管理", "ko": "이커머스 관리"},
    "E-commerce": {"ja": "E コマース", "ko": "이커머스"},
    "产品": {"ja": "製品", "ko": "제품"},
    "Product": {"ja": "製品", "ko": "제품"},
    "订单": {"ja": "注文", "ko": "주문"},
    "Order": {"ja": "注文", "ko": "주문"},
    "客户": {"ja": "顧客", "ko": "고객"},
    "Customer": {"ja": "顧客", "ko": "고객"},
    "添加产品": {"ja": "製品を追加", "ko": "제품 추가"},
    "Add Product": {"ja": "製品を追加", "ko": "제품 추가"},
    "搜索产品...": {"ja": "製品を検索...", "ko": "제품 검색..."},
    "Search products...": {"ja": "製品を検索...", "ko": "제품 검색..."},
    "产品价格": {"ja": "製品価格", "ko": "제품 가격"},
    "Price": {"ja": "価格", "ko": "가격"},
    "库存量": {"ja": "在庫数", "ko": "재고 수량"},
    "Quantity": {"ja": "数量", "ko": "수량"},
    "订单状态": {"ja": "注文ステータス", "ko": "주문 상태"},
    "Order Status": {"ja": "注文ステータス", "ko": "주문 상태"},
    "客户信息": {"ja": "顧客情報", "ko": "고객 정보"},
    "Customer Info": {"ja": "顧客情報", "ko": "고객 정보"},
    "配送地址": {"ja": "配送先住所", "ko": "배송 주소"},
    "Shipping Address": {"ja": "配送先住所", "ko": "배송 주소"},
    "账单地址": {"ja": "請求先住所", "ko": "청구 주소"},
    "Billing Address": {"ja": "請求先住所", "ko": "청구 주소"},
    "支付状态": {"ja": "支払いステータス", "ko": "결제 상태"},
    "Payment Status": {"ja": "支払いステータス", "ko": "결제 상태"},
    "已支付": {"ja": "支払い済み", "ko": "결제됨"},
    "Paid": {"ja": "支払い済み", "ko": "결제됨"},
    "未支付": {"ja": "未支払い", "ko": "미결제"},
    "Unpaid": {"ja": "未支払い", "ko": "미결제"},
    "退款": {"ja": "返金", "ko": "환불"},
    "Refunded": {"ja": "返金済み", "ko": "환불됨"},
    "总计": {"ja": "合計", "ko": "총계"},
    "Total": {"ja": "合計", "ko": "총계"},
}

# ==================== Chinook 专用翻译 ====================
CHINOOK_TRANSLATIONS = {
    "音乐管理": {"ja": "音楽管理", "ko": "음악 관리"},
    "Music": {"ja": "音楽", "ko": "음악"},
    "艺术家": {"ja": "アーティスト", "ko": "아티스트"},
    "Artist": {"ja": "アーティスト", "ko": "아티스트"},
    "专辑": {"ja": "アルバム", "ko": "앨범"},
    "Album": {"ja": "アルバム", "ko": "앨범"},
    "音轨": {"ja": "トラック", "ko": "트랙"},
    "Track": {"ja": "トラック", "ko": "트랙"},
    "添加艺术家": {"ja": "アーティストを追加", "ko": "아티스트 추가"},
    "Add Artist": {"ja": "アーティストを追加", "ko": "아티스트 추가"},
    "搜索艺术家...": {"ja": "アーティストを検索...", "ko": "아티스트 검색..."},
    "Search artists...": {"ja": "アーティストを検索...", "ko": "아티스트 검색..."},
    "艺术家名称": {"ja": "アーティスト名", "ko": "아티스트 이름"},
    "Artist Name": {"ja": "アーティスト名", "ko": "아티스트 이름"},
    "添加专辑": {"ja": "アルバムを追加", "ko": "앨범 추가"},
    "Add Album": {"ja": "アルバムを追加", "ko": "앨범 추가"},
    "搜索专辑...": {"ja": "アルバムを検索...", "ko": "앨범 검색..."},
    "Search albums...": {"ja": "アルバムを検索...", "ko": "앨범 검색..."},
    "专辑标题": {"ja": "アルバムタイトル", "ko": "앨범 제목"},
    "Album Title": {"ja": "アルバムタイトル", "ko": "앨범 제목"},
    "添加音轨": {"ja": "トラックを追加", "ko": "트랙 추가"},
    "Add Track": {"ja": "トラックを追加", "ko": "트랙 추가"},
    "搜索音轨...": {"ja": "トラックを検索...", "ko": "트랙 검색..."},
    "Search tracks...": {"ja": "トラックを検索...", "ko": "트랙 검색..."},
    "音轨名称": {"ja": "トラック名", "ko": "트랙 이름"},
    "Track Name": {"ja": "トラック名", "ko": "트랙 이름"},
    "作曲家": {"ja": "作曲家", "ko": "작곡가"},
    "Composer": {"ja": "作曲家", "ko": "작곡가"},
    "时长": {"ja": "再生時間", "ko": "재생 시간"},
    "Milliseconds": {"ja": "ミリ秒", "ko": "밀리초"},
    "字节": {"ja": "バイト", "ko": "바이트"},
    "Bytes": {"ja": "バイト", "ko": "바이트"},
    "流派": {"ja": "ジャンル", "ko": "장르"},
    "Genre": {"ja": "ジャンル", "ko": "장르"},
    "媒体类型": {"ja": "メディアタイプ", "ko": "미디어 유형"},
    "Media Type": {"ja": "メディアタイプ", "ko": "미디어 유형"},
    "员工": {"ja": "従業員", "ko": "직원"},
    "Employee": {"ja": "従業員", "ko": "직원"},
    "发票": {"ja": "請求書", "ko": "송장"},
    "Invoice": {"ja": "請求書", "ko": "송장"},
    "客户": {"ja": "顧客", "ko": "고객"},
    "Customer": {"ja": "顧客", "ko": "고객"},
}


def get_translation(text, project_type):
    """获取文本的翻译"""
    # 首先在项目专用字典中查找
    project_dict = {
        "crm": CRM_TRANSLATIONS,
        "todo": TODO_TRANSLATIONS,
        "journal": JOURNAL_TRANSLATIONS,
        "ecommerce": ECOMMERCE_TRANSLATIONS,
        "chinook": CHINOOK_TRANSLATIONS,
    }.get(project_type, {})
    
    # 先查项目专用字典
    if text in project_dict:
        return project_dict[text]
    
    # 再查通用字典
    if text in COMMON_TRANSLATIONS:
        return COMMON_TRANSLATIONS[text]
    
    return None


def process_labels_block(lines, start_idx, indent, project_type):
    """处理 labels 块的多语言翻译"""
    new_lines = []
    i = start_idx
    
    # 先确定 zh 和 en 的顺序和位置
    zh_start = None
    en_start = None
    j = i
    while j < len(lines):
        line = lines[j]
        if re.match(rf'{indent}zh:\s*$', line):
            zh_start = j
        elif re.match(rf'{indent}en:\s*$', line):
            en_start = j
        # 检查是否到达块结尾
        elif re.match(rf'{indent}\S', line) and not line.startswith(f'{indent} '):
            break
        j += 1
    
    # 收集 zh 和 en 块的所有键值对
    zh_keys = {}
    en_keys = {}
    
    # 收集 zh 块
    if zh_start:
        k = zh_start + 1
        while k < len(lines):
            line = lines[k]
            if re.match(rf'{indent}\S', line) and not line.startswith(f'{indent}  '):
                break
            match = re.match(rf'{indent}  (\w+):\s*(.+)', line)
            if match:
                key = match.group(1)
                value = match.group(2).strip().strip('"\'')
                zh_keys[key] = value
            k += 1
    
    # 收集 en 块
    if en_start:
        k = en_start + 1
        while k < len(lines):
            line = lines[k]
            if re.match(rf'{indent}\S', line) and not line.startswith(f'{indent}  '):
                break
            match = re.match(rf'{indent}  (\w+):\s*(.+)', line)
            if match:
                key = match.group(1)
                value = match.group(2).strip().strip('"\'')
                en_keys[key] = value
            k += 1
    
    # 确定插入位置（在最后一个语言块之后）
    insert_pos = max(zh_start or 0, en_start or 0)
    
    # 现在重新处理，在最后一个语言块后添加 ja/ko
    i = start_idx
    while i < len(lines):
        line = lines[i]
        new_lines.append(line)
        
        # 检查是否是最后一个语言块（zh 或 en，取后出现的那个）
        is_last_lang = False
        if insert_pos == zh_start and re.match(rf'{indent}zh:\s*$', line):
            is_last_lang = True
        elif insert_pos == en_start and re.match(rf'{indent}en:\s*$', line):
            is_last_lang = True
        
        if is_last_lang:
            # 添加 ja: 和 ko: 块
            new_lines.append(f'{indent}ja:\n')
            new_lines.append(f'{indent}ko:\n')
            
            # 为每个键添加翻译
            all_keys = set(en_keys.keys()) | set(zh_keys.keys())
            for key in sorted(all_keys):
                en_value = en_keys.get(key, '')
                zh_value = zh_keys.get(key, '')
                
                # 获取翻译
                translation = get_translation(en_value, project_type)
                if not translation and zh_value:
                    translation = get_translation(zh_value, project_type)
                
                if translation:
                    ja_value = translation['ja']
                    ko_value = translation['ko']
                    new_lines.insert(len(new_lines) - 2, f'{indent}  {key}: {ja_value}\n')
                    new_lines.insert(len(new_lines) - 2, f'{indent}  {key}: {ko_value}\n')
            
            # 跳过已处理的 en 或 zh 块内容
            if insert_pos == zh_start and zh_start:
                i = zh_start + 1
                while i < len(lines):
                    if re.match(rf'{indent}\S', lines[i]) and not lines[i].startswith(f'{indent}  '):
                        break
                    i += 1
                continue
            elif insert_pos == en_start and en_start:
                i = en_start + 1
                while i < len(lines):
                    if re.match(rf'{indent}\S', lines[i]) and not lines[i].startswith(f'{indent}  '):
                        break
                    i += 1
                continue
        
        i += 1
    
    return new_lines, i


def add_translations_to_app_yaml(filepath, project_type):
    """为 app.yaml 文件添加翻译"""
    with open(filepath, 'r', encoding='utf-8') as f:
        lines = f.readlines()
    
    new_lines = []
    i = 0
    added_count = 0
    
    while i < len(lines):
        line = lines[i]
        
        # 查找 labels: 行
        if re.match(r'\s+labels:\s*$', line):
            indent_match = re.match(r'(\s+)labels:', line)
            indent = indent_match.group(1) + '  '  # labels 内容的缩进
            
            # 检查下一行是否是 zh:
            if i + 1 < len(lines) and re.match(rf'{indent}zh:\s*$', lines[i + 1]):
                # 处理这个 labels 块
                block_lines, next_i = process_labels_block(lines, i + 1, indent, project_type)
                
                # 计算添加了多少翻译
                ja_count = sum(1 for l in block_lines if re.match(rf'{indent}\w+:', l) and 'ja:' not in l)
                added_count += ja_count // 2  # 每个键有 ja 和 ko 两行
                
                new_lines.extend(block_lines)
                i = next_i
                continue
        
        new_lines.append(line)
        i += 1
    
    if new_lines != lines:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.writelines(new_lines)
    
    return added_count


def main():
    files_to_process = [
        ('Projects/crm/app.yaml', 'crm'),
        ('Projects/todo/app.yaml', 'todo'),
        ('Projects/journal/app.yaml', 'journal'),
        ('Projects/ecommerce/app.yaml', 'ecommerce'),
        ('Projects/chinook/app.yaml', 'chinook'),
        ('Definitions/app.yaml', 'crm'),  # 使用 CRM 翻译作为默认
        ('Definitions/todo_app.yaml', 'todo'),
    ]
    
    print("=" * 60)
    print("开始为 app.yaml 文件添加日文和韩文翻译")
    print("=" * 60)
    
    total_added = 0
    
    for filepath, project_type in files_to_process:
        full_path = os.path.join('/home/ubuntu/ws/lcp', filepath)
        if not os.path.exists(full_path):
            print(f"\n跳过（文件不存在）: {filepath}")
            continue
        
        print(f"\n处理：{filepath} ({project_type})")
        added = add_translations_to_app_yaml(full_path, project_type)
        print(f"  添加了 {added} 组翻译")
        total_added += added
    
    print("\n" + "=" * 60)
    print(f"完成！共添加 {total_added} 组翻译")
    print("=" * 60)


if __name__ == '__main__':
    main()
