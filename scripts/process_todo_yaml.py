#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
为 TODO app.yaml 添加日文和韩文翻译
"""

import re

TRANSLATIONS = {
    "任务管理": {"ja": "タスク管理", "ko": "작업 관리"},
    "Tasks": {"ja": "タスク", "ko": "작업"},
    "添加任务": {"ja": "タスクを追加", "ko": "작업 추가"},
    "Add Task": {"ja": "タスクを追加", "ko": "작업 추가"},
    "编辑": {"ja": "編集", "ko": "편집"},
    "Edit": {"ja": "編集", "ko": "편집"},
    "删除": {"ja": "削除", "ko": "삭제"},
    "Delete": {"ja": "削除", "ko": "삭제"},
    "搜索任务...": {"ja": "タスクを検索...", "ko": "작업 검색..."},
    "Search tasks...": {"ja": "タスクを検索...", "ko": "작업 검색..."},
    "ID": {"ja": "ID", "ko": "ID"},
    "Id": {"ja": "ID", "ko": "ID"},
    "标题": {"ja": "タイトル", "ko": "제목"},
    "Title": {"ja": "タイトル", "ko": "제목"},
    "描述": {"ja": "説明", "ko": "설명"},
    "Description": {"ja": "説明", "ko": "설명"},
    "状态": {"ja": "ステータス", "ko": "상태"},
    "Status": {"ja": "ステータ스", "ko": "상태"},
    "优先级": {"ja": "優先度", "ko": "우선순위"},
    "Priority": {"ja": "優先度", "ko": "우선순위"},
    "截止日期": {"ja": "締切日", "ko": "마감일"},
    "Due Date": {"ja": "締切日", "ko": "마감일"},
    "创建时间": {"ja": "作成日時", "ko": "생성 일시"},
    "Created At": {"ja": "作成日時", "ko": "생성 일시"},
    "完成时间": {"ja": "完了日時", "ko": "완료 일시"},
    "Completed At": {"ja": "完了日時", "ko": "완료 일시"},
    "操作": {"ja": "アクション", "ko": "작업"},
    "Actions": {"ja": "アクション", "ko": "작업"},
    "新建": {"ja": "新規", "ko": "새로 만들기"},
    "New": {"ja": "新規", "ko": "새로 만들기"},
    "保存": {"ja": "保存", "ko": "저장"},
    "Save": {"ja": "保存", "ko": "저장"},
    "取消": {"ja": "キャンセル", "ko": "취소"},
    "Cancel": {"ja": "キャンセル", "ko": "취소"},
    "待处理": {"ja": "保留中", "ko": "보류 중"},
    "Pending": {"ja": "保留中", "ko": "보류 중"},
    "进行中": {"ja": "進行中", "ko": "진행 중"},
    "In Progress": {"ja": "進行中", "ko": "진행 중"},
    "已完成": {"ja": "完了", "ko": "완료"},
    "Done": {"ja": "完了", "ko": "완료"},
    "低": {"ja": "低", "ko": "낮음"},
    "Low": {"ja": "低", "ko": "낮음"},
    "中": {"ja": "中", "ko": "중간"},
    "Medium": {"ja": "中", "ko": "중간"},
    "高": {"ja": "高", "ko": "높음"},
    "High": {"ja": "高", "ko": "높음"},
    "项目管理": {"ja": "プロジェクト管理", "ko": "프로젝트 관리"},
    "Projects": {"ja": "プロジェクト", "ko": "프로젝트"},
    "添加项目": {"ja": "プロジェクトを追加", "ko": "프로젝트 추가"},
    "Add Project": {"ja": "プロジェクトを追加", "ko": "프로젝트 추가"},
    "搜索项目...": {"ja": "プロジェクトを検索...", "ko": "프로젝트 검색..."},
    "Search projects...": {"ja": "プロジェクトを検索...", "ko": "프로젝트 검색..."},
    "项目名称": {"ja": "プロジェクト名", "ko": "프로젝트 이름"},
    "Project Name": {"ja": "プロジェクト名", "ko": "프로젝트 이름"},
    "开始日期": {"ja": "開始日", "ko": "시작일"},
    "Start Date": {"ja": "開始日", "ko": "시작일"},
    "结束日期": {"ja": "終了日", "ko": "종료일"},
    "End Date": {"ja": "終了日", "ko": "종료일"},
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
    print("处理 TODO app.yaml...")
    process_file('/home/ubuntu/ws/lcp/Projects/todo/app.yaml')
    print("完成!")
