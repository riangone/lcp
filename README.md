# LowCode Platform

> **通过 YAML 定义驱动一切，尽可能不写代码、不生成代码**

[![.NET](https://img.shields.io/badge/.NET-10.0-512BD4?logo=dotnet)](https://dotnet.microsoft.com)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

## 🎯 特性

- ✅ **运行时驱动** - YAML 定义，运行时动态生成 API 和 UI
- ✅ **多项目管理** - 每个项目独立数据库和用户系统
- ✅ **用户认证** - JWT 认证，BCrypt 密码加密
- ✅ **多表关联** - 支持复杂的多表 CRUD 操作
- ✅ **动态 UI** - 列表、表单、过滤、分页自动生成

## 🚀 快速开始

```bash
# 构建
dotnet build

# 运行
dotnet run --project Platform.Api
```

访问：http://localhost:5267

## 📚 文档

- [完整项目文档](Docs/PROJECT_DOCUMENTATION.md)
- [多表表单功能](Docs/MultiTableForm.md)
- [增强计划](Docs/LowCode_Enhancement_Plan.md)

## 📁 项目结构

```
lcp/
├── Platform.Api/              # Web 应用
├── Platform.Infrastructure/   # 基础设施层
├── Platform.Domain/           # 领域模型
├── Projects/                  # 项目目录
│   ├── todo/                  # TODO 项目
│   ├── chinook/               # 音乐商店
│   └── ecommerce/             # 电商订单
└── Docs/                      # 文档
```

## 🔐 默认账户

| 项目 | 用户名 | 密码 |
|------|--------|------|
| todo | admin | admin123 |
| chinook | admin | admin123 |
| ecommerce | admin | admin123 |

---

&copy; 2026 LowCode Platform
