# 数据库修改说明

## 数据库文件变更

### chinook.db
- 原始Chinook数据库文件
- 包含基本的音乐商店数据结构

### chinook_with_data.db  
- 带有示例数据的Chinook数据库
- 用于应用程序的初始数据

### init_db.sql
- 数据库初始化脚本
- 包含创建表和插入初始数据的SQL语句

### test.db
- 测试数据库
- 用于开发和测试目的

## 数据库用途

这些数据库文件用于支持以下功能：

1. **CRUD操作** - 通过UI控制器提供创建、读取、更新和删除功能
2. **AI功能** - 作为AI建议和验证的数据源
3. **库存管理** - Inventory AI功能的数据基础
4. **快照管理** - AI决策快照的存储

## 数据库结构

数据库包含以下主要表：
- Artist（艺术家）
- Album（专辑）
- Track（音轨）
- Customer（客户）
- Employee（员工）
- Genre（流派）
- Invoice（发票）
- MediaType（媒体类型）

## 与AI三层架构的集成

- **Functional Core** - 对数据库数据的纯函数操作
- **Deterministic Shell** - 数据库事务和快照管理
- **Non-deterministic Edge** - AI建议生成的数据源