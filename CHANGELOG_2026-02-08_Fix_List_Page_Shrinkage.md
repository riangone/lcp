# 修复List页面过滤后持续缩小问题

## 问题描述
在List页面中，每次点击Filter按钮后，页面宽度会逐渐缩小。经过调查发现，这是由于htmx请求返回的内容结构导致的嵌套问题。

## 根本原因
当用户点击Filter按钮时：
1. htmx向服务器发送GET请求
2. 服务器端的`UiController.Index`方法返回完整的List视图（包含`<div id="list">`容器）
3. htmx将返回的完整内容插入到现有的`#list`元素中
4. 导致`<div id="list">`元素被嵌套在自身内部
5. 每次点击Filter都会增加一层嵌套，导致视觉上的缩小效果

## 解决方案
创建了一个新的部分视图，只包含列表的实际内容，而不包含外层容器。

### 具体修改

#### 1. 创建新的部分视图 `_ListContent.cshtml`
- 复制了`List.cshtml`的内容
- 移除了外层的`<div id="list">`容器
- 保留了列表的所有实际内容（过滤器、表格、分页等）

#### 2. 修改 `UiController.cs`
- 更新了`Index`方法，添加对htmx请求的检测
- 当检测到htmx请求时，返回`_ListContent`部分视图
- 非htmx请求（初次加载）仍返回完整的`List`视图

#### 3. 代码变更详情

**新增文件：**
- `Platform.Api/Views/Ui/_ListContent.cshtml` - 不含容器的列表内容部分视图

**修改文件：**
- `Platform.Api/Controllers/UiController.cs` - 更新Index方法以区分完整视图和部分视图

## 技术细节
- 使用`Request.Headers["HX-Request"]`检测htmx请求
- 对于htmx请求返回`PartialView("_ListContent", rows)`
- 对于普通请求返回`View("List", rows)`

## 测试验证
- 初次加载页面正常显示
- 点击Filter按钮后，页面内容正确更新
- 多次点击Filter不会导致页面缩小
- 页面结构保持扁平，无嵌套容器

## 提交信息
- 修复List页面过滤后持续缩小的问题
- 引入部分视图以解决htmx内容嵌套问题
- 优化用户体验和页面性能