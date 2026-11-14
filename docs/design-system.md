# OrbitFlow UI 设计规范

本次改造的设计原则是 **quiet productivity（低噪音、高信息密度、清晰反馈）**。重点保留真正的 Flux UI Blade 组件与 Livewire 行为，而非替换成只可观赏的静态页面。

## 设计基础

| 语义 | 浅色模式 | 深色模式 |
| --- | --- | --- |
| Canvas | \`#f7f8fb\` | \`#101117\` |
| Surface | \`#ffffff\` | \`#1a1b23\` |
| Border | \`#e8eaf0\` | \`#30313b\` |
| Text | \`#202537\` | \`#f2f2f7\` |
| Secondary text | \`#73798b\` | \`#a3a3b1\` |
| Accent | \`#6356d9\` | \`#a39af6\` |

设计令牌位于 \`resources/css/app.css\`，以 \`--orbit-*\` 命名，避免与 Flux 官方内部变量冲突。保持 6px 任务进度条、12–20px 主要圆角、柔和边框和极弱阴影。尽量避免大面积渐变、重玻璃态以及仅为装饰的复杂动画。

## 页面与组件

- **工作台布局**：品牌、工作区切换、导航与用户身份分区。移动端沿用 Flux 的 Sidebar Toggle。
- **仪表盘**：四个关键指标、真实任务完成率、最近项目与截止任务。所有百分比均从当前工作区的实际任务数据计算；没有任务时为 0%。
- **项目列表**：继续使用 \`flux:table\`、\`flux:input\`、\`flux:select\`、\`flux:modal\`；增加已完成任务计数，避免用虚拟进度数据。
- **营销首页**：左右分栏，明确展示“界面示意 · 展示数据为样例”；避免把模拟数据描述为真实业务指标。
- **身份认证**：统一品牌标识与深色信息面板，保持现有表单逻辑不变。

## 使用规则

1. 交互组件优先使用 Flux UI（例如 Button、Input、Modal、Sidebar、Table）。不要为纯展示目的替换掉其 Livewire 指令。
2. 使用已有的 \`surface\`、\`orbit-stat-card\`、\`orbit-panel-heading\`、\`orbit-progress-track\` 和 \`orbit-progress-fill\` 构造通用界面。
3. 始终提供空状态、按钮可访问名称、合理标题层级与焦点状态。进度条需要 \`role=progressbar\` 和 \`aria-valuenow\`。
4. 支持 \`prefers-reduced-motion\`；避免 hover 才能发现的重要操作，禁止以动画作为唯一反馈。
5. 深色与浅色模式都应检查文字对比度；不要单独使用颜色区分任务状态。
6. **GitHub Pages 为真实 Flux 组件的只读 HTML 快照，不是可登录/可操作的 SaaS 演示。** 交互必须在实际 PHP 服务中验证。

## 交付验收

- \`npm run build\`：检查 Tailwind 与 Flux 资源。
- \`php artisan test\`：检查真实工作区指标与数据隔离。
- 桌面端、平板端、手机端检查侧栏、表格水平滚动、表单 Modal、空状态。
- 检查浅色/深色模式、键盘 Tab 顺序、200% 浏览器放大和减少动画选项。
- 不得把计费演示、静态 Pages 页面或模拟仪表盘误称为生产可用的商业服务。
