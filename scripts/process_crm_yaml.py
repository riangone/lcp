#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
为 CRM app.yaml 添加日文和韩文翻译
"""

import re

# CRM 翻译字典
TRANSLATIONS = {
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
    "公司名称": {"ja": "会社名", "ko": "회사명"},
    "Company Name": {"ja": "会社名", "ko": "회사명"},
    "联系人": {"ja": "連絡先", "ko": "연락처"},
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
    "City": {"ja": "도시", "ko": "도시"},
    "状态": {"ja": "ステータス", "ko": "상태"},
    "Status": {"ja": "ステータ스", "ko": "상태"},
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
    "Country": {"ja": "국", "ko": "국가"},
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
    "ID": {"ja": "ID", "ko": "ID"},
    "Id": {"ja": "ID", "ko": "ID"},
    "编号": {"ja": "番号", "ko": "번호"},
    "No": {"ja": "番号", "ko": "번호"},
    "描述": {"ja": "説明", "ko": "설명"},
    "Description": {"ja": "説明", "ko": "설명"},
    "新建": {"ja": "新規", "ko": "새로 만들기"},
    "New": {"ja": "新規", "ko": "새로 만들기"},
    "保存": {"ja": "保存", "ko": "저장"},
    "Save": {"ja": "保存", "ko": "저장"},
    "取消": {"ja": "キャンセル", "ko": "취소"},
    "Cancel": {"ja": "キャンセル", "ko": "취소"},
    "操作": {"ja": "アクション", "ko": "작업"},
    "Actions": {"ja": "アクション", "ko": "작업"},
}

def get_translation(text):
    """获取翻译"""
    text = text.strip().strip('"\'')
    if text in TRANSLATIONS:
        return TRANSLATIONS[text]
    return None

def process_file(filepath):
    """处理文件"""
    with open(filepath, 'r', encoding='utf-8') as f:
        lines = f.readlines()
    
    new_lines = []
    i = 0
    added_count = 0
    
    while i < len(lines):
        line = lines[i]
        new_lines.append(line)
        
        # 查找 labels: 行 (6 个空格缩进)
        if re.match(r'      labels:\s*$', line):
            # 收集 zh 和 en 块
            zh_dict = {}
            en_dict = {}
            
            j = i + 1
            current_lang = None
            
            while j < len(lines):
                curr_line = lines[j]
                
                # 检查是否是 zh: 或 en: (8 个空格)
                if re.match(r'        zh:\s*$', curr_line):
                    current_lang = 'zh'
                elif re.match(r'        en:\s*$', curr_line):
                    current_lang = 'en'
                # 检查键值对 (10 个空格缩进)
                elif re.match(r'          \w+:', curr_line):
                    match = re.match(r'          (\w+): (.+)', curr_line)
                    if match and current_lang:
                        key = match.group(1)
                        value = match.group(2).strip()
                        if current_lang == 'zh':
                            zh_dict[key] = value
                        elif current_lang == 'en':
                            en_dict[key] = value
                # 检查是否到达块结尾 (6 个空格但不是 zh/en/ja/ko)
                elif re.match(r'      \w+:', curr_line) and not re.match(r'      (zh|en|ja|ko|styles|list|form|properties):', curr_line):
                    break
                
                j += 1
            
            # 生成 ja 和 ko 块
            if zh_dict and en_dict:
                ja_lines = ['        ja:']
                ko_lines = ['        ko:']
                
                all_keys = set(en_dict.keys()) | set(zh_dict.keys())
                for key in sorted(all_keys):
                    en_val = en_dict.get(key, '')
                    zh_val = zh_dict.get(key, '')
                    
                    trans = get_translation(en_val)
                    if not trans and zh_val:
                        trans = get_translation(zh_val)
                    
                    if trans:
                        ja_lines.append(f'          {key}: {trans["ja"]}')
                        ko_lines.append(f'          {key}: {trans["ko"]}')
                        added_count += 1
                    else:
                        # 无翻译时使用英文
                        ja_lines.append(f'          {key}: {en_val}')
                        ko_lines.append(f'          {key}: {en_val}')
                
                # 在 en 块后插入 ja 和 ko
                new_lines.append('\n'.join(ja_lines) + '\n')
                new_lines.append('\n'.join(ko_lines) + '\n')
        
        i += 1
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.writelines(new_lines)
    
    print(f"  添加了 {added_count} 个翻译项")

if __name__ == '__main__':
    print("处理 CRM app.yaml...")
    process_file('/home/ubuntu/ws/lcp/Projects/crm/app.yaml')
    print("完成!")
