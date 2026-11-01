# OrbitFlow — Flux UI SaaS 起始项目

一个以 **Laravel 12 + Livewire 3 + Flux UI 2 + Tailwind CSS 4** 编写的通用团队协作 SaaS MVP。UI 采用 Flux 官方 Laravel / Livewire 生态，而非名字相似的 Vue / React 库。

## 已实现

| 模块 | 实现情况 |
| --- | --- |
| 首页 | 产品介绍、基础功能、演示价格方案与注册入口 |
| 身份认证 | 注册、登录、退出、登录限流、密码哈希和会话轮换 |
| 多工作区 | 注册自动创建、独立创建、成员关联、切换并校验成员资格 |
| 仪表盘 | 当前工作区统计、近期项目、近期到期任务 |
| 项目管理 | 新建、编辑、搜索、筛选、删除、分页 |
| 任务管理 | 新建、指定负责人、修改状态、删除、优先级、截止日期 |
| 成员管理 | 添加**已注册**用户、调整角色、移除成员、保护所有者 |
| 账户设置 | 编辑个人名称、修改密码、重命名工作区 |
| 订阅管理 | 当前计划与用量的演示页面，**未连接支付系统** |
| 多租户隔离 | 业务查询限定当前工作区；敏感操作服务端检查身份和角色 |
| 测试 | 含跨租户访问、权限、注册和项目任务流程的 PHPUnit 用例（需要本地安装依赖后运行） |

## 前置条件

- PHP >= 8.2，扩展：`mbstring`、`pdo_sqlite`、`openssl`、`fileinfo`（使用 PostgreSQL 则需要 `pdo_pgsql`）
- Composer 2；Node.js 20+ / npm；SQLite（默认）
- 需要联网下载 Composer / npm 依赖（未打包 `vendor/` 与 `node_modules/`）

## 本地快速启动

```bash
unzip orbitflow-flux-saas.zip
cd orbitflow-flux-saas
bash setup.sh
php artisan serve
```

浏览器访问 http://127.0.0.1:8000 ，点击“免费开始”，注册账号后自动创建你的第一个工作区。

Windows PowerShell（在解压后的项目目录执行）：

```powershell
powershell -ExecutionPolicy Bypass -File .\setup.ps1
php artisan serve
```

手动安装：

```bash
cp .env.example .env
touch database/database.sqlite
composer install
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

开发时可在另一个终端执行 `npm run dev`，生产环境只部署 `npm run build` 产物，并设置 `APP_ENV=production`、`APP_DEBUG=false`、HTTPS、生产数据库、可用的 SMTP 服务等。

## 使用 PostgreSQL

将 `.env` 改成：

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=orbitflow
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

运行 `php artisan migrate`。用户与项目等表使用数据库迁移创建。

## 项目结构

```
app/
  Http/Controllers/       注册登录、创建及切换工作区
  Livewire/               Dashboard / Projects / Project / Team / Billing / Settings
  Models/                 User / Workspace / Project / Task
  Support/WorkspaceContext.php   当前租户校验
resources/
  views/components/layouts/     Flux UI 后台、认证布局
  views/livewire/               业务页面
  views/welcome.blade.php       营销首页
  css/app.css                  Tailwind 4 + Flux 样式
routes/web.php
database/migrations/
 tests/Feature/SaasFlowTest.php
```

## 权限模型

- `owner`：工作区所有者，可管理成员、删除项目、修改工作区。
- `admin`：可管理成员、删除项目、修改工作区，但不能更改或移除所有者。
- `member`：可查看、创建和编辑所在工作区的项目与任务。

所有项目/任务请求从当前工作区关系加载对象；切换工作区和向项目指派任务也校验成员资格。**这是以工作区 ID 进行隔离的共享数据库模式，不是物理独立数据库**。

## 明确不包括的功能

- Stripe/Cashier 支付、Webhooks、真实订阅价格/周期及额度限制
- 邮件邀请（当前仅可直接添加已经注册的用户）、邮箱验证、找回密码、SSO/2FA
- 文件上传、审计日志、队列工作器、全局搜索、自动化工作流、API Token
- 系统管理后台、运行监控、备份方案及正式环境安全审计

因此**此项目是可扩展的 MVP 基础代码，不应直接视为已通过生产安全验收的成品**。上线前至少补齐密码重置/邮箱验证、登录安全策略、租户授权测试、备份恢复、速率限制、运营监控与业务验收。

## 验证

```bash
php artisan test
vendor/bin/pint --test
npm run build
```

生成文件时的执行环境未提供 Composer、PHP SQLite/mbstring 扩展与外网包下载能力，因此仅进行了静态检查，未实际运行以上集成测试。请在满足依赖的开发环境内先执行测试。

## 官方参考

- Flux UI: https://fluxui.dev/
- Flux 安装：https://fluxui.dev/docs/installation
- Flux 侧边栏：https://fluxui.dev/layouts/sidebar
- Laravel 官方 Livewire Starter Kit：https://github.com/laravel/livewire-starter-kit
- Laravel 官网：https://laravel.com/

项目源码自编写并按需使用 Flux UI Free 组件，不包含 Flux Pro 授权或私有包。

## GitHub Pages — 基于真正 Flux UI 的只读渲染快照

> GitHub Pages **不能运行** PHP、Laravel、Livewire 状态交互、登录或数据库。这里展示的是 GitHub Actions 在真实 Laravel + Flux 环境中服务端渲染的 HTML **只读快照**，而不是可交互 SaaS。要体验真实操作，请部署 Laravel 到 PHP 服务器。

此前的 `docs/index.html` 和 `docs/styles.css` 为手写的静态仿制页面，**不是 Flux 组件**，不应作为 Flux UI 演示。本项目已停止发布那套静态仿制页面。

现在的 `.github/workflows/deploy-pages.yml` 在 GitHub Actions 中：

1. 通过 Composer 安装 `livewire/flux`，并使用 Vite 构建 Tailwind + 官方 Flux CSS。
2. 创建临时 SQLite 数据库，填充不含真实用户信息的演示记录。
3. 运行 Laravel，登录临时演示用户，通过 `scripts/export_flux_preview.py` 读取 `/dashboard`、`/projects`、`/team`、`/billing`、`/settings` 的真实 **Blade/Flux 渲染 HTML**。
4. 删除 Session/CSRF/Livewire 状态和交互脚本，只发布脱敏的 HTML 以及编译后的 CSS 资源到 GitHub Pages。

预览地址：https://weepwood.github.io/OrbitFlow/ （构建成功后）

源组件位于 `resources/views/components/layouts/` 与 `resources/views/livewire/`：包含 `<flux:sidebar>`、`<flux:button>`、`<flux:input>`、`<flux:table>`、`<flux:modal>`、`<flux:select>` 等真实组件。

静态预览的按钮、表单、任务更改等均无法运行：这是 Pages 平台限制，而非 Flux UI 组件缺失。正式 SaaS 应部署 Laravel、数据库、SESSION、队列、缓存以及 Vite 资源，并启用 HTTPS。

### 正式 SaaS 部署

- 服务器运行 `public/index.php`，安装 PHP 8.3+、Composer 与 Laravel 所需扩展；生产数据库可采用 PostgreSQL。
- 设置 `APP_ENV=production`、`APP_DEBUG=false`，生成独立生产密钥，并配置 HTTPS、会话存储、邮件、监控和备份。
- 对外开放注册前，补充找回密码、邮箱验证、租户授权审计；计费功能目前仅用于展示。
