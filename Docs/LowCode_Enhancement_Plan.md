# LowCode 平台增强计划

## P0 - 核心增强（立即实施）

### 1. 业务规则验证 ✅
- [x] YAML 配置验证规则
- [ ] 实现验证引擎
- [ ] 内置验证函数库

### 2. 完善多表表单 ✅
- [x] 基础功能实现
- [x] 事务支持
- [ ] 更多测试场景
- [ ] 文档完善

### 3. 权限控制基础
- [x] JWT 认证
- [x] 用户表结构
- [ ] 角色权限配置
- [ ] 基于角色的访问控制

## P1 - 重要增强（近期计划）

### 4. 计算字段
```yaml
models:
  OrderItem:
    calculated_fields:
      Subtotal:
        expression: "UnitPrice * Quantity"
      Tax:
        expression: "Subtotal * 0.1"
```

### 5. 级联操作
```yaml
models:
  Customer:
    cascade_delete:
      - table: Order
        foreign_key: CustomerId
      - table: Invoice
        foreign_key: CustomerId
```

### 6. 审计字段
```yaml
models:
  Product:
    audit_fields:
      created_at: { auto: true }
      created_by: { auto: true, from: user }
      updated_at: { auto: true }
      updated_by: { auto: true, from: user }
```

## P2 - 高级功能（未来计划）

### 7. 动态表单布局
```yaml
models:
  Product:
    form_layout:
      - section: 基本信息
        columns: 2
        fields: [Name, Category, Price]
      - section: 详细信息
        columns: 1
        fields: [Description, Specifications]
```

### 8. 工作流引擎
```yaml
models:
  Order:
    workflow:
      initial_state: Pending
      states:
        - Pending
        - Confirmed
        - Processing
        - Shipped
        - Delivered
      transitions:
        - from: Pending
          to: Confirmed
          trigger: confirm
          guard: "hasPayment"
```

### 9. 动态列表操作
```yaml
models:
  Order:
    list_actions:
      - name: Export
        icon: fa-download
        handler: export
      - name: Print
        icon: fa-print
        handler: print
```

## 技术债务

- [ ] 移除重复的 AuthService（Application 层和 Infrastructure 层）
- [ ] 统一错误处理机制
- [ ] 改进日志记录
- [ ] 添加单元测试
- [ ] 性能优化（缓存、连接池）

## 文档需求

- [x] 项目主文档
- [x] 多表表单文档
- [ ] API 参考文档
- [ ] 部署指南
- [ ] 最佳实践
