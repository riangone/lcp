// 日记本应用自定义脚本

window.JournalApp = window.JournalApp || {};

// 心情过滤器
JournalApp.MoodFilter = {
    init: function() {
        console.log('MoodFilter initialized');
    },
    
    // 按心情过滤日记
    byMood: function(mood) {
        const cards = document.querySelectorAll('.card-entry');
        cards.forEach(card => {
            if (mood === 'all' || card.dataset.mood === mood) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
};

// 分类过滤器
JournalApp.CategoryFilter = {
    byCategory: function(categoryId) {
        const cards = document.querySelectorAll('.card-entry');
        cards.forEach(card => {
            if (categoryId === 'all' || card.dataset.category === categoryId) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
};

// 搜索功能
JournalApp.Search = {
    search: function(query) {
        const cards = document.querySelectorAll('.card-entry');
        query = query.toLowerCase();
        
        cards.forEach(card => {
            const title = card.querySelector('.entry-title')?.textContent.toLowerCase() || '';
            const content = card.querySelector('.entry-content')?.textContent.toLowerCase() || '';
            
            if (title.includes(query) || content.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
};

// 统计更新
JournalApp.Stats = {
    updateStats: function() {
        fetch('/api/JournalStats')
            .then(response => response.json())
            .then(data => {
                console.log('Stats updated:', data);
                // 更新统计卡片
                data.forEach(stat => {
                    const element = document.querySelector(`[data-stat="${stat.StatName}"]`);
                    if (element) {
                        element.textContent = stat.StatValue;
                    }
                });
            })
            .catch(err => console.error('Failed to update stats:', err));
    }
};

// 日记卡片效果
JournalApp.CardEffects = {
    init: function() {
        const cards = document.querySelectorAll('.card-entry');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }
};

// HTMX 完成事件
document.addEventListener('htmx:afterRequest', function(evt) {
    // 刷新后更新统计
    JournalApp.Stats.updateStats();
});

// 初始化
document.addEventListener('DOMContentLoaded', function() {
    JournalApp.MoodFilter.init();
    JournalApp.CardEffects.init();
    console.log('Journal App initialized');
    
    // 添加心情表情映射
    window.moodEmojis = {
        'happy': '😊',
        'good': '🙂',
        'neutral': '😐',
        'bad': '😔',
        'angry': '😠'
    };
});

// 工具函数
JournalApp.Utils = {
    // 格式化日期
    formatDate: function(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('zh-CN', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            weekday: 'long'
        });
    },
    
    // 获取心情表情
    getMoodEmoji: function(mood) {
        return window.moodEmojis[mood] || '😐';
    },
    
    // 截断文本
    truncate: function(text, maxLength) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }
};
