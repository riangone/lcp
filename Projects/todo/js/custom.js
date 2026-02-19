// TODO 项目自定义脚本

// 任务管理相关功能
window.TodoApp = window.TodoApp || {};

// 任务过滤器
TodoApp.TaskFilter = {
    init: function() {
        console.log('TaskFilter initialized');
    },
    
    // 按优先级过滤
    byPriority: function(priority) {
        const cards = document.querySelectorAll('.card-task');
        cards.forEach(card => {
            if (priority === 'all' || card.dataset.priority === priority) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    },
    
    // 按状态过滤
    byStatus: function(status) {
        const cards = document.querySelectorAll('.card-task');
        cards.forEach(card => {
            if (status === 'all' || card.dataset.status === status) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
};

// 项目统计
TodoApp.ProjectStats = {
    updateStats: function() {
        fetch('/api/ProjectStats')
            .then(response => response.json())
            .then(data => {
                console.log('Project stats updated:', data);
            });
    }
};

// HTMX 完成事件
document.addEventListener('htmx:afterRequest', function(evt) {
    // 刷新后更新统计
    TodoApp.ProjectStats.updateStats();
});

// 初始化
document.addEventListener('DOMContentLoaded', function() {
    TodoApp.TaskFilter.init();
    console.log('TODO App initialized');
});
