/**
 * HMSS Application JavaScript
 * GD ZM Sales System
 */

(function() {
    'use strict';
    
    // ============================================
    // 侧边栏切换
    // ============================================
    
    const sidebarToggles = document.querySelectorAll('.sidebar-toggle');
    const sidebar = document.querySelector('.hmss-sidebar');
    
    sidebarToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });
    });
    
    // 点击外部关闭侧边栏（移动端）
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 992 && 
            sidebar && 
            !sidebar.contains(e.target) && 
            !e.target.closest('.sidebar-toggle')) {
            sidebar.classList.remove('show');
        }
    });
    
    // ============================================
    // 通知功能
    // ============================================
    
    window.HMSS = window.HMSS || {
        // 显示通知
        notify: function(message, type = 'info') {
            const types = {
                success: { icon: 'bi-check-circle-fill', class: 'bg-success' },
                error: { icon: 'bi-exclamation-triangle-fill', class: 'bg-danger' },
                warning: { icon: 'bi-exclamation-circle-fill', class: 'bg-warning' },
                info: { icon: 'bi-info-circle-fill', class: 'bg-primary' }
            };
            
            const config = types[type] || types.info;
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white ${config.class} border-0 position-fixed top-0 end-0 m-3`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi ${config.icon} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        },
        
        // 确认对话框
        confirm: function(message, title = '确认') {
            return new Promise((resolve) => {
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.setAttribute('tabindex', '-1');
                modal.innerHTML = `
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ${message}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                <button type="button" class="btn btn-primary" id="confirmOk">确认</button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                
                modal.querySelector('#confirmOk').addEventListener('click', () => {
                    resolve(true);
                    bsModal.hide();
                });
                
                modal.addEventListener('hidden.bs.modal', () => {
                    modal.remove();
                    resolve(false);
                });
            });
        },
        
        // API 请求封装
        api: async function(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            
            const mergedOptions = {
                ...defaultOptions,
                ...options
            };
            
            try {
                const response = await fetch(url, mergedOptions);
                const result = await response.json();
                
                if (!result.success) {
                    this.notify(result.message || '操作失败', 'error');
                }
                
                return result;
            } catch (error) {
                console.error('API Error:', error);
                this.notify('网络错误，请稍后重试', 'error');
                return { success: false, message: '网络错误' };
            }
        },
        
        // 格式化日期
        formatDate: function(date, format = 'YYYY-MM-DD') {
            if (!date) return '';
            
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            const hours = String(d.getHours()).padStart(2, '0');
            const minutes = String(d.getMinutes()).padStart(2, '0');
            const seconds = String(d.getSeconds()).padStart(2, '0');
            
            return format
                .replace('YYYY', year)
                .replace('MM', month)
                .replace('DD', day)
                .replace('HH', hours)
                .replace('mm', minutes)
                .replace('ss', seconds);
        },
        
        // 格式化金额
        formatMoney: function(amount, currency = '¥') {
            if (amount === null || amount === undefined) return '';
            return `${currency}${Number(amount).toLocaleString('zh-CN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        },
        
        // 防抖函数
        debounce: function(func, wait = 300) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        // 本地存储封装
        storage: {
            get: function(key) {
                try {
                    const item = localStorage.getItem(key);
                    return item ? JSON.parse(item) : null;
                } catch (e) {
                    console.error('Storage get error:', e);
                    return null;
                }
            },
            set: function(key, value) {
                try {
                    localStorage.setItem(key, JSON.stringify(value));
                    return true;
                } catch (e) {
                    console.error('Storage set error:', e);
                    return false;
                }
            },
            remove: function(key) {
                try {
                    localStorage.removeItem(key);
                    return true;
                } catch (e) {
                    console.error('Storage remove error:', e);
                    return false;
                }
            }
        }
    };
    
    // ============================================
    // 表格增强功能
    // ============================================
    
    // 全选功能
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('select-all')) {
            const table = e.target.closest('table');
            const checkboxes = table.querySelectorAll('tbody .form-check-input');
            checkboxes.forEach(cb => {
                cb.checked = e.target.checked;
            });
        }
    });
    
    // ============================================
    // 快捷键支持
    // ============================================
    
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + S: 保存
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            if (typeof window.onSaveShortcut === 'function') {
                window.onSaveShortcut();
            }
        }
        
        // Ctrl/Cmd + F: 搜索框聚焦
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            const searchInput = document.querySelector('.header-search input');
            if (searchInput) {
                searchInput.focus();
            }
        }
        
        // Escape: 关闭模态框
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal.show');
            modals.forEach(modal => {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            });
        }
    });
    
    // ============================================
    // 初始化提示
    // ============================================
    
    console.log('[HMSS] Application initialized');
    
})();
