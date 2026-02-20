#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
为 Journal app.yaml 添加日文和韩文翻译
"""

import re

TRANSLATIONS = {
    "日记管理": {"ja": "ジャーナル管理", "ko": "일기장 관리"},
    "Entries": {"ja": "エントリー", "ko": "일기"},
    "添加日记": {"ja": "日記を追加", "ko": "일기 추가"},
    "Add Entry": {"ja": "エントリーを追加", "ko": "일기 추가"},
    "编辑": {"ja": "編集", "ko": "편집"},
    "Edit": {"ja": "編集", "ko": "편집"},
    "删除": {"ja": "削除", "ko": "삭제"},
    "Delete": {"ja": "削除", "ko": "삭제"},
    "搜索日记...": {"ja": "日記を検索...", "ko": "일기 검색..."},
    "Search entries...": {"ja": "エントリーを検索...", "ko": "일기 검색..."},
    "ID": {"ja": "ID", "ko": "ID"},
    "Id": {"ja": "ID", "ko": "ID"},
    "标题": {"ja": "タイトル", "ko": "제목"},
    "Title": {"ja": "タイトル", "ko": "제목"},
    "内容": {"ja": "内容", "ko": "내용"},
    "Content": {"ja": "内容", "ko": "내용"},
    "日期": {"ja": "日付", "ko": "날짜"},
    "Date": {"ja": "日付", "ko": "날짜"},
    "心情": {"ja": "気分", "ko": "기분"},
    "Mood": {"ja": "気分", "ko": "기분"},
    "分类": {"ja": "カテゴリ", "ko": "카테고리"},
    "Category": {"ja": "カテゴリ", "ko": "카테고리"},
    "标签": {"ja": "タグ", "ko": "태그"},
    "Tags": {"ja": "タグ", "ko": "태그"},
    "公开": {"ja": "公開", "ko": "공개"},
    "Public": {"ja": "公開", "ko": "공개"},
    "私有": {"ja": "非公開", "ko": "비공개"},
    "Private": {"ja": "非公開", "ko": "비공개"},
    "新建": {"ja": "新規", "ko": "새로 만들기"},
    "New": {"ja": "新規", "ko": "새로 만들기"},
    "保存": {"ja": "保存", "ko": "저장"},
    "Save": {"ja": "保存", "ko": "저장"},
    "取消": {"ja": "キャンセル", "ko": "취소"},
    "Cancel": {"ja": "キャンセル", "ko": "취소"},
    "操作": {"ja": "アクション", "ko": "작업"},
    "Actions": {"ja": "アクション", "ko": "작업"},
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
    "分类管理": {"ja": "カテゴリ管理", "ko": "카테고리 관리"},
    "Categories": {"ja": "カテゴリ", "ko": "카테고리"},
    "添加分类": {"ja": "カテゴリを追加", "ko": "카테고리 추가"},
    "Add Category": {"ja": "カテゴリを追加", "ko": "카테고리 추가"},
    "搜索分类...": {"ja": "カテゴリを検索...", "ko": "카테고리 검색..."},
    "Search categories...": {"ja": "カテゴリを検索...", "ko": "카테고리 검색..."},
    "分类名称": {"ja": "カテゴリ名", "ko": "카테고리 이름"},
    "Category Name": {"ja": "カテゴリ名", "ko": "카테고리 이름"},
    "颜色": {"ja": "色", "ko": "색상"},
    "Color": {"ja": "色", "ko": "색상"},
    "图标": {"ja": "アイコン", "ko": "아이콘"},
    "Icon": {"ja": "アイコン", "ko": "아이콘"},
    "描述": {"ja": "説明", "ko": "설명"},
    "Description": {"ja": "説明", "ko": "설명"},
}

def get_translation(text):
    text = text.strip().strip('"\'')
    if text in TRANSLATIONS:
        return TRANSLATIONS[text]
    return None

def process_file(filepath):
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
                    
                    trans = get_translation(en_val)
                    if not trans and zh_val:
                        trans = get_translation(zh_val)
                    
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
    print("处理 Journal app.yaml...")
    process_file('/home/ubuntu/ws/lcp/Projects/journal/app.yaml')
    print("完成!")
