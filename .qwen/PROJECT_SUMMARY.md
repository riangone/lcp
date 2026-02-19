The user wants me to create a comprehensive project summary based on the conversation history. Let me analyze what happened in this session:

1. Initial problem: CRUD pages were returning "model undefined" errors
2. Fixed multiple issues:
   - Delete button API path was wrong (/api/data/ → /api/)
   - UiController was using AppDefinitions directly instead of ProjectScope
   - project parameter was being lost in navigation

3. Main feature implemented: Custom UI view system
   - Added CustomViewDefinition to ModelDefinition.cs
   - Created journal-specific UI (timeline list, rich text form, details view)
   - Added YAML configuration for default_ui_mode (custom/generic)
   - Added UI switch buttons between custom and generic views
   - Modified home page cards to link to appropriate UI based on configuration

4. Final state:
   - Journal Entry model uses custom UI by default (timeline view)
   - Other models use generic UI
   - Users can switch between UIs using buttons
   - Home page My Journal card links to custom UI

Let me create a comprehensive summary.# LowCodePlatform (LCP) Project Summary

## Overall Goal
Build a runtime-driven low-code platform using .NET 10 where YAML definitions dynamically generate CRUD APIs and Web UIs without code generation.

## Key Knowledge

### Architecture
- **Runtime-driven**: YAML definitions loaded at runtime, not code generation
- **Single controller pattern**: `GenericApiController` handles all models dynamically
- **Multi-project support**: Projects directory with independent configurations (journal, chinook, ecommerce, crm, todo)
- **Project switching**: Via URL parameter `?project={name}`

### Technology Stack
- Backend: ASP.NET Core 10.0, Dapper ORM
- Database: SQLite (per-project)
- Frontend: Razor Views + HTMX + Tailwind CSS
- Configuration: YAML (YamlDotNet)

### Build & Run Commands
```bash
dotnet build
dotnet run --project Platform.Api --urls "http://localhost:5267"
```

### Critical Conventions
- **Project parameter**: Must be preserved in all navigation links (`&project={name}`)
- **UI switching**: Via `&ui=custom` or `&ui=generic` URL parameters
- **View location**: Custom views in `Platform.Api/Views/Ui/{ViewName}/`
- **YAML configuration**: `custom_view.default_ui_mode` controls default UI behavior

## Recent Actions

### Bug Fixes (Completed)
1. **Fixed "Model undefined" errors** - Root cause: `UiController` was injecting `AppDefinitions` directly instead of using `ProjectScope`
2. **Fixed delete button API path** - Changed from `/api/data/{model}/{id}` to `/api/{model}/{id}`
3. **Fixed project parameter loss** - Added `project` parameter to all navigation links, forms, and redirects in:
   - `_ListContent.cshtml` (pagination, filters, edit buttons)
   - `GenericApiController.cs` (HX-Redirect headers)
   - `UiController.cs` (clear filter redirect, create/edit redirects)

### Custom UI System (Completed)
1. **Added CustomViewDefinition** to `ModelDefinition.cs`:
   - `enabled`: Enable custom UI
   - `default_ui_mode`: "custom" or "generic"
   - `list_template`, `form_template`, `details_template`: View paths
   - `style`: Layout, theme, pagination config

2. **Created Journal-specific UI**:
   - **List view**: Timeline layout with mood emojis (😊🙂😐😔😠), stats, search, mood filter
   - **Form view**: Rich text editor with toolbar, mood selector, category dropdown
   - **Details view**: Full-screen reading mode with animations
   - **Custom CSS**: Enhanced styling in `Projects/journal/css/custom.css`

3. **UI Switching Mechanism**:
   - YAML config: `default_ui_mode: custom` or `generic`
   - URL override: `&ui=custom` or `&ui=generic`
   - Switch buttons on both UI types
   - Home page cards auto-link based on configuration

### File Changes
| File | Purpose |
|------|---------|
| `Platform.Infrastructure/Definitions/ModelDefinition.cs` | CustomViewDefinition class |
| `Platform.Api/Controllers/UiController.cs` | UI mode detection and view switching |
| `Platform.Api/Controllers/GenericApiController.cs` | Project parameter in redirects |
| `Platform.Api/Views/Ui/Journal/*.cshtml` | Custom views (List, Form, Details) |
| `Platform.Api/Views/Home/Components/_CardGrid.cshtml` | Auto-link to custom UI |
| `Platform.Api/Views/Ui/_ListContent.cshtml` | Add custom UI switch button |
| `Projects/journal/app.yaml` | Custom view configuration |
| `Projects/journal/home.yaml` | Home page links |

## Current Plan

### [DONE]
1. ✅ Fix CRUD "Model undefined" errors
2. ✅ Preserve project parameter across all navigation
3. ✅ Implement CustomViewDefinition YAML configuration
4. ✅ Create Journal custom UI (timeline, form, details)
5. ✅ Add UI switching mechanism (buttons + URL params)
6. ✅ Configure home page cards to link to appropriate UI
7. ✅ Set Journal Entry default_ui_mode to generic (list) with custom UI accessible via button

### [IN PROGRESS]
- None currently

### [TODO]
1. **Extend custom UI to other projects** - Create custom views for ecommerce, crm, etc.
2. **Add Details view support** - Currently List and Form work, Details needs YAML config support
3. **Improve view location system** - Current workaround copies views to `Platform.Api/Views/`; consider embedded views or better path resolution
4. **Add custom UI templates** - Create reusable custom UI templates (timeline, kanban, calendar, etc.)
5. **Documentation** - Document custom UI creation process for users

## Access Information

### Running Server
- **URL**: http://localhost:5267
- **Projects**: journal, chinook, ecommerce, crm, todo

### Key URLs
| Page | URL | UI Type |
|------|-----|---------|
| Journal Home | `/Home?project=journal` | - |
| Journal Entry (Custom) | `/ui/Entry?project=journal&ui=custom` | Timeline |
| Journal Entry (Generic) | `/ui/Entry?project=journal` | Table |
| Artist (Generic) | `/ui/Artist?project=chinook` | Table |

### YAML Configuration Example
```yaml
models:
  Entry:
    table: Entry
    custom_view:
      enabled: true
      type: custom
      default_ui_mode: generic    # generic=table default, custom=timeline default
      list_template: "views/entry/List.cshtml"
      form_template: "views/entry/Form.cshtml"
      style:
        layout: timeline
        show_stats: true
```

---

## Summary Metadata
**Update time**: 2026-02-19T22:13:14.111Z 
