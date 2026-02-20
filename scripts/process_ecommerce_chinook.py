#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
为 E-commerce 和 Chinook app.yaml 添加日文和韩文翻译
"""

import re
import sys

# E-commerce 翻译
ECOMMERCE_TRANSLATIONS = {
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
    "ID": {"ja": "ID", "ko": "ID"},
    "Id": {"ja": "ID", "ko": "ID"},
    "编辑": {"ja": "編集", "ko": "편집"},
    "Edit": {"ja": "編集", "ko": "편집"},
    "删除": {"ja": "削除", "ko": "삭제"},
    "Delete": {"ja": "削除", "ko": "삭제"},
    "新建": {"ja": "新規", "ko": "새로 만들기"},
    "New": {"ja": "新規", "ko": "새로 만들기"},
    "保存": {"ja": "保存", "ko": "저장"},
    "Save": {"ja": "保存", "ko": "저장"},
    "取消": {"ja": "キャンセル", "ko": "취소"},
    "Cancel": {"ja": "キャンセル", "ko": "취소"},
    "操作": {"ja": "アクション", "ko": "작업"},
    "Actions": {"ja": "アクション", "ko": "작업"},
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
    "客户": {"ja": "顧客", "ko": "고객"},
    "Customer": {"ja": "顧客", "ko": "고객"},
    "总金额": {"ja": "合計金額", "ko": "총 금액"},
    "Total Amount": {"ja": "合計金額", "ko": "총 금액"},
    "状态": {"ja": "ステータス", "ko": "상태"},
    "Status": {"ja": "ステータ스", "ko": "상태"},
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
    "客户管理": {"ja": "顧客管理", "ko": "고객 관리"},
    "Customers": {"ja": "顧客", "ko": "고객"},
    "添加客户": {"ja": "顧客を追加", "ko": "고객 추가"},
    "Add Customer": {"ja": "顧客を追加", "ko": "고객 추가"},
    "搜索客户...": {"ja": "顧客を検索...", "ko": "고객 검색..."},
    "Search customers...": {"ja": "顧客を検索...", "ko": "고객 검색..."},
    "公司名称": {"ja": "会社名", "ko": "회사명"},
    "Company Name": {"ja": "会社名", "ko": "회사명"},
    "联系人": {"ja": "連絡先", "ko": "연락처"},
    "Contact Person": {"ja": "連絡先担当者", "ko": "연락처 담당자"},
    "电话": {"ja": "電話", "ko": "전화"},
    "Phone": {"ja": "電話", "ko": "전화"},
    "邮箱": {"ja": "メール", "ko": "이메일"},
    "Email": {"ja": "メール", "ko": "이메일"},
    "地址": {"ja": "住所", "ko": "주소"},
    "Address": {"ja": "住所", "ko": "주소"},
    "城市": {"ja": "都市", "ko": "도시"},
    "City": {"ja": "都市", "ko": "도시"},
    "国家": {"ja": "国", "ko": "국가"},
    "Country": {"ja": "국", "ko": "국가"},
    "描述": {"ja": "説明", "ko": "설명"},
    "Description": {"ja": "説明", "ko": "설명"},
}

# Chinook 翻译
CHINOOK_TRANSLATIONS = {
    "艺术家": {"ja": "アーティスト", "ko": "아티스트"},
    "Artist": {"ja": "アーティスト", "ko": "아티스트"},
    "添加艺术家": {"ja": "アーティストを追加", "ko": "아티스트 추가"},
    "Add Artist": {"ja": "アーティストを追加", "ko": "아티스트 추가"},
    "搜索艺术家...": {"ja": "アーティストを検索...", "ko": "아티스트 검색..."},
    "Search artists...": {"ja": "アーティストを検索...", "ko": "아티스트 검색..."},
    "ID": {"ja": "ID", "ko": "ID"},
    "Id": {"ja": "ID", "ko": "ID"},
    "名称": {"ja": "名称", "ko": "이름"},
    "Name": {"ja": "名称", "ko": "이름"},
    "编辑": {"ja": "編集", "ko": "편집"},
    "Edit": {"ja": "編集", "ko": "편집"},
    "删除": {"ja": "削除", "ko": "삭제"},
    "Delete": {"ja": "削除", "ko": "삭제"},
    "新建": {"ja": "新規", "ko": "새로 만들기"},
    "New": {"ja": "新規", "ko": "새로 만들기"},
    "保存": {"ja": "保存", "ko": "저장"},
    "Save": {"ja": "保存", "ko": "저장"},
    "取消": {"ja": "キャンセル", "ko": "취소"},
    "Cancel": {"ja": "キャンセル", "ko": "취소"},
    "操作": {"ja": "アクション", "ko": "작업"},
    "Actions": {"ja": "アクション", "ko": "작업"},
    "专辑": {"ja": "アルバム", "ko": "앨범"},
    "Album": {"ja": "アルバム", "ko": "앨범"},
    "添加专辑": {"ja": "アルバムを追加", "ko": "앨범 추가"},
    "Add Album": {"ja": "アルバムを追加", "ko": "앨범 추가"},
    "搜索专辑...": {"ja": "アルバムを検索...", "ko": "앨범 검색..."},
    "Search albums...": {"ja": "アルバムを検索...", "ko": "앨범 검색..."},
    "标题": {"ja": "タイトル", "ko": "제목"},
    "Title": {"ja": "タイトル", "ko": "제목"},
    "艺术家 ID": {"ja": "アーティスト ID", "ko": "아티스트 ID"},
    "Artist ID": {"ja": "アーティスト ID", "ko": "아티스트 ID"},
    "音轨": {"ja": "トラック", "ko": "트랙"},
    "Track": {"ja": "トラック", "ko": "트랙"},
    "添加音轨": {"ja": "トラックを追加", "ko": "트랙 추가"},
    "Add Track": {"ja": "トラックを追加", "ko": "트랙 추가"},
    "搜索音轨...": {"ja": "トラックを検索...", "ko": "트랙 검색..."},
    "Search tracks...": {"ja": "トラックを検索...", "ko": "트랙 검색..."},
    "作曲家": {"ja": "作曲家", "ko": "작곡가"},
    "Composer": {"ja": "作曲家", "ko": "작곡가"},
    "时长": {"ja": "再生時間", "ko": "재생 시간"},
    "Milliseconds": {"ja": "ミリ秒", "ko": "밀리초"},
    "字节": {"ja": "バイト", "ko": "바이트"},
    "Bytes": {"ja": "バイト", "ko": "바이트"},
    "媒体类型": {"ja": "メディアタイプ", "ko": "미디어 유형"},
    "Media Type": {"ja": "メディアタイプ", "ko": "미디어 유형"},
    "流派": {"ja": "ジャンル", "ko": "장르"},
    "Genre": {"ja": "ジャンル", "ko": "장르"},
    "添加流派": {"ja": "ジャンルを追加", "ko": "장르 추가"},
    "Add Genre": {"ja": "ジャンルを追加", "ko": "장르 추가"},
    "搜索流派...": {"ja": "ジャンルを検索...", "ko": "장르 검색..."},
    "Search genres...": {"ja": "ジャンルを検索...", "ko": "장르 검색..."},
    "流派名称": {"ja": "ジャンル名", "ko": "장르 이름"},
    "Genre Name": {"ja": "ジャンル名", "ko": "장르 이름"},
    "员工": {"ja": "従業員", "ko": "직원"},
    "Employee": {"ja": "従業員", "ko": "직원"},
    "添加员工": {"ja": "従業員を追加", "ko": "직원 추가"},
    "Add Employee": {"ja": "従業員を追加", "ko": "직원 추가"},
    "搜索员工...": {"ja": "従業員を検索...", "ko": "직원 검색..."},
    "Search employees...": {"ja": "従業員を検索...", "ko": "직원 검색..."},
    "姓": {"ja": "姓", "ko": "성"},
    "Last Name": {"ja": "姓", "ko": "성"},
    "名": {"ja": "名", "ko": "이름"},
    "First Name": {"ja": "名", "ko": "이름"},
    "职位": {"ja": "役職", "ko": "직함"},
    "Title": {"ja": "役職", "ko": "직함"},
    "发票": {"ja": "請求書", "ko": "송장"},
    "Invoice": {"ja": "請求書", "ko": "송장"},
    "添加发票": {"ja": "請求書を追加", "ko": "송장 추가"},
    "Add Invoice": {"ja": "請求書を追加", "ko": "송장 추가"},
    "搜索发票...": {"ja": "請求書を検索...", "ko": "송장 검색..."},
    "Search invoices...": {"ja": "請求書を検索...", "ko": "송장 검색..."},
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
    "总计": {"ja": "合計", "ko": "총계"},
    "Total": {"ja": "合計", "ko": "총계"},
}

def get_translations(project_type):
    if project_type == 'ecommerce':
        return ECOMMERCE_TRANSLATIONS
    elif project_type == 'chinook':
        return CHINOOK_TRANSLATIONS
    return {}

def process_file(filepath, project_type):
    translations = get_translations(project_type)
    
    with open(filepath, 'r', encoding='utf-8') as f:
        lines = f.readlines()
    
    new_lines = []
    i = 0
    added_count = 0
    
    while i < len(lines):
        line = lines[i]
        new_lines.append(line)
        
        if re.match(r'      labels:\s*$', line):
            zh_dict = {}
            en_dict = {}
            
            j = i + 1
            current_lang = None
            
            while j < len(lines):
                curr_line = lines[j]
                
                if re.match(r'        zh:\s*$', curr_line):
                    current_lang = 'zh'
                elif re.match(r'        en:\s*$', curr_line):
                    current_lang = 'en'
                elif re.match(r'          \w+:', curr_line):
                    match = re.match(r'          (\w+): (.+)', curr_line)
                    if match and current_lang:
                        key = match.group(1)
                        value = match.group(2).strip()
                        if current_lang == 'zh':
                            zh_dict[key] = value
                        elif current_lang == 'en':
                            en_dict[key] = value
                elif re.match(r'      \w+:', curr_line) and not re.match(r'      (zh|en|ja|ko|styles|list|form|properties):', curr_line):
                    break
                
                j += 1
            
            if zh_dict and en_dict:
                ja_lines = ['        ja:']
                ko_lines = ['        ko:']
                
                all_keys = set(en_dict.keys()) | set(zh_dict.keys())
                for key in sorted(all_keys):
                    en_val = en_dict.get(key, '')
                    zh_val = zh_dict.get(key, '')
                    
                    trans = translations.get(en_val) or translations.get(zh_val)
                    
                    if trans:
                        ja_lines.append(f'          {key}: {trans["ja"]}')
                        ko_lines.append(f'          {key}: {trans["ko"]}')
                        added_count += 1
                    else:
                        ja_lines.append(f'          {key}: {en_val}')
                        ko_lines.append(f'          {key}: {en_val}')
                
                new_lines.insert(len(new_lines) - 1, '\n'.join(ja_lines) + '\n')
                new_lines.insert(len(new_lines) - 1, '\n'.join(ko_lines) + '\n')
        
        i += 1
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.writelines(new_lines)
    
    print(f"  添加了 {added_count} 个翻译项")

if __name__ == '__main__':
    if len(sys.argv) < 3:
        print("用法：python process_ecommerce_chinook.py <文件路径> <项目类型>")
        sys.exit(1)
    
    filepath = sys.argv[1]
    project_type = sys.argv[2]
    
    print(f"处理 {project_type} app.yaml...")
    process_file(filepath, project_type)
    print("完成!")
