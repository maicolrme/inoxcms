<div align="center">

# ⚡ INOX

**The modern PHP CMS that doesn't rust.**

*Website · API · E-commerce · Full-stack — you choose at install time.*

[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![Laravel 11](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Livewire 3](https://img.shields.io/badge/Livewire-3-FB70A9?style=flat-square)](https://livewire.laravel.com)
[![License MIT](https://img.shields.io/badge/License-MIT-blue?style=flat-square)](LICENSE)
[![Status Alpha](https://img.shields.io/badge/Status-Alpha-orange?style=flat-square)]()

```
Works on shared hosting. No VPS required. No Node.js. No Docker. No terminal.
Download → Upload → Visit /install → Done.
```

</div>

---

## 🤔 Why Inox?

WordPress powers 43% of the internet — and it shows. It was built in 2003 and carries 20 years of technical decisions that can never be undone. Inox is what WordPress would be if designed today, from scratch, with modern PHP and modern expectations.

| Problem in WordPress | How Inox solves it |
|---|---|
| 20 years of procedural legacy code | Clean OOP architecture on Laravel 11 |
| No real plugin sandboxing | Modules declare permissions explicitly — you approve what they touch |
| `wp_postmeta` is a performance disaster | Proper relational schema with indexes and migrations |
| No native cache | 3-layer cache in core: page → object → fragment |
| Fake wp-cron (fires on visits) | Real scheduler with visual job manager |
| No real-time anything | Laravel Reverb WebSockets, built in |
| REST API was bolted on in 2016 | API-first from day zero |
| No visual data modeling | Visual Schema Builder — draw models, get CRUD automatically |
| Shared hosting only supports basics | WebSockets, queues, and real-time all work on shared hosting |
| Plugin dependency hell | Semver + Composer-based resolution with conflict detection |
| No AI integration | AI Layer module: local (Ollama/LM Studio) or cloud |
| Updating breaks everything | Versioned contracts between core, modules, and themes |
| No audit trail | Every admin action logged with who, what, and when |

---

## 🏗️ Architecture Overview

```
┌──────────────────────────────────────────────────────────────────────┐
│                          PRESENTATION LAYER                          │
│                                                                      │
│  Blade + Livewire 3          Alpine.js            Vue 3 (islands)   │
│  ──────────────────          ─────────────        ────────────────  │
│  Admin panel (90%)           Dropdowns            Visual Builder    │
│  Forms, tables, lists        Modals, tooltips     Schema Studio     │
│  Module UI (no build step)   Tabs, toggles        Event Monitor     │
│  Settings, media, users      Micro-interactions   Real-time feeds   │
│                                                                      │
├──────────────────────────────────────────────────────────────────────┤
│                          APPLICATION CORE                            │
│                                                                      │
│  Laravel 11        Module Engine        Theme Engine                │
│  Hook System       Schema Builder       Auth Studio                 │
│  REST API          GraphQL (opt-in)     WebSockets (Reverb)         │
│  Queue System      Real Scheduler       Audit Logger                │
│                                                                      │
├────────────────────┬─────────────────────────────────────────────────┤
│    AI LAYER        │                 DATA LAYER                      │
│  (inox/ai module)  │                                                 │
│  ────────────────  │  Database         Cache            Storage      │
│  Anthropic Claude  │  ────────────     ─────────────    ─────────── │
│  OpenAI GPT        │  SQLite (default) File (default)   Local       │
│  Ollama (local)    │  MySQL            Redis             Amazon S3   │
│  LM Studio (local) │  PostgreSQL       Memcached         Cloudflare R2│
│  Groq              │                                    Backblaze B2 │
│  Custom endpoint   │                                    FTP / SFTP   │
│  Agent API         │                                                 │
└────────────────────┴─────────────────────────────────────────────────┘
```

---

## 🎨 Frontend Stack — Why Blade + Livewire + Alpine + Vue islands

This is one of the most important architectural decisions in Inox. Here is the rationale:

### The problem with a pure Vue 3 SPA

A fully Vue-based admin requires every module to ship a pre-compiled JavaScript bundle. This means module authors need Node.js, Vite, and a build step. It creates a high barrier for the community and makes module development unnecessarily complex.

### The problem with pure Blade

Blade alone cannot deliver the Visual Builder (drag and drop), the Schema Studio (relational canvas), or real-time feeds. These require a proper reactive frontend.

### The solution: right tool for each job

```
Blade + Livewire 3
  Everything in the admin that is not a visual builder.
  Lists, tables, forms, filters, settings, user management,
  media library, module manager, content editor.
  Fully reactive: typing in a search field filters results
  instantly without writing any JavaScript.
  Modules add their admin pages in pure Blade + PHP.
  Zero build step for module authors.

Alpine.js (15KB)
  Micro-interactions that do not need server roundtrips.
  Dropdowns, modals, sidebars, tabs, toggles, tooltips.
  Declared inline in Blade templates with x-data directives.

Vue 3 — only where it is essential (islands)
  Visual Page Builder: drag and drop canvas
  Schema Studio: relational model designer
  Real-time Event Monitor: live event stream
  These load Vue only on the specific pages that need it.
  The rest of the admin never loads Vue at all.
```

### What this means for module authors

A module that adds pages to the admin ships only PHP and Blade:

```
modules/vendor/mymodule/
└── resources/
    ├── views/livewire/     ← Blade templates
    └── Livewire/           ← PHP component classes
```

No Node.js. No npm. No build command. No admin.js to compile.
Any PHP developer can write a module for Inox.

---

## 🚀 Quick Start

### User install (no terminal required)

```
1. Download inox-v1.0.0.zip from inox.dev
2. Upload and extract on your server
3. Visit yourdomain.com/install
4. Follow the 5-step wizard
5. Done — your site is running
```

### Developer install

```bash
git clone https://github.com/inox/inox my-project
cd my-project
composer install
npm install && npm run build   # Only needed for Visual Builder island
php inox install
php inox serve
```

### Pre-compiled releases

Every official release of Inox is built by CI before packaging:

```
GitHub Actions (on every release tag):
  composer install --no-dev --optimize-autoloader
  npm run build
  zip inox-v1.0.0.zip . --exclude="node_modules/*" --exclude=".git/*"
  Upload to CDN and GitHub releases
```

The user download includes `vendor/` and `public/build/` already compiled.
No Composer. No Node. No terminal needed on the user's server.

---

## 🧙 Installation Wizard

The installer runs entirely in the browser. No CLI required for end users.

### Step 1 — Project type

```
What are you building?

  ◉ 🌐  Website / Blog / CMS
         Classic CMS with pages, posts, themes, and visual builder.

  ○ 🔌  Pure API Backend
         Headless. JSON only. No frontend. Visual Schema Builder active.

  ○ 🛒  E-commerce
         Products, cart, orders, inventory, payments.

  ○ 📱  Headless CMS
         Manage content visually, deliver via API to any frontend.

  ○ 🔧  Full-stack Application
         Everything: pages + API + auth + real-time + schemas.
```

Inox configures itself differently based on this choice:
activates the right modules, registers the right routes, shows the right admin.

### Step 2 — Database

```
  ◉ SQLite      Zero configuration. File-based. Great for most projects.
                Upgrade to MySQL later with one command.

  ○ MySQL       Existing database on your hosting account.

  ○ PostgreSQL  For VPS or cloud environments.
```

### Step 3 — File storage

```
  ◉ Local       Files on this server. Simple, always works.
  ○ Amazon S3   Or any S3-compatible service (MinIO, Linode, etc.)
  ○ Cloudflare R2  Zero egress fees.
  ○ Backblaze B2   Cheapest object storage.
  ○ FTP / SFTP  External server via FTP.
```

### Step 4 — Optional features

```
  ☑ Real-time (WebSockets via Reverb)
  ☑ Page cache
  ☐ Redis (if available on your server)
  ☐ Queue worker (background jobs — requires CLI or supervisor)
  ☐ GraphQL API
  ☐ AI Layer (configure provider after install)
```

### Step 5 — Admin account

Name, email, password. Done.

After the wizard, Inox runs migrations, seeds defaults, and shows the admin URL.
The `install.php` file self-destructs after successful installation.

---

## 🎨 Visual Schema Builder

Active in API, Headless, and Full-stack modes.
In Website mode, content types are managed through the Content Studio.

### Files first — source of truth is your codebase

The builder writes real files. Not just database records.

```
You design a Product model in the visual canvas and click Save
                          ↓
Inox writes:
  schema/product.schema.json             ← Human-readable definition
  database/migrations/xxxx_products.php  ← Laravel migration
  app/Models/Product.php                 ← Eloquent model with casts
  app/Policies/ProductPolicy.php         ← Permission policy
                          ↓
Inox runs the migration automatically
                          ↓
REST API is live immediately:
  GET    /api/products
  POST   /api/products
  GET    /api/products/{id}
  PUT    /api/products/{id}
  DELETE /api/products/{id}
```

Why files and not just database records?

- Your schema lives in Git. Data models are versioned alongside your code.
- Developers can edit migration files by hand — the visual builder reflects changes.
- Deployments use standard Laravel migrations. No proprietary tooling required.
- If you ever move away from Inox, your migrations and models go with you.

### What the builder generates automatically

```
For each model you design:

  ✓ Database migration with proper indexes and foreign keys
  ✓ Eloquent model with fillable, casts, and relationships
  ✓ Resource policy (per-role permissions: read, write, delete)
  ✓ Full REST CRUD (list, show, create, update, delete, bulk)
  ✓ Admin panel section (list table + create/edit form, automatically)
  ✓ Input validation rules per field type
  ✓ OpenAPI / Swagger documentation (auto-generated)
  ✓ TypeScript types (optional export for headless frontends)
  ✓ Filterable, sortable, and searchable list out of the box
```

### Field types

| Type | Details |
|---|---|
| `text` | Short string, min/max length |
| `longtext` | Rich text with optional HTML editor |
| `integer` `decimal` `float` | Numeric with optional range and precision |
| `boolean` | True/false toggle |
| `date` `datetime` `time` | With timezone support |
| `select` | Enum from a defined list |
| `multi-select` | Multiple values from a defined list |
| `media` | Single file linked to the Media Library |
| `media[]` | Multiple files |
| `json` | Arbitrary structured data |
| `relation:belongsTo` | Many-to-one |
| `relation:hasMany` | One-to-many |
| `relation:belongsToMany` | Many-to-many with pivot table |
| `slug` | Auto-generated from another field, unique |
| `uuid` | Auto-generated UUID primary key |
| `email` `url` `phone` | Validated string types |
| `password` | Bcrypt hashed |
| `color` | Hex color picker |
| `coordinates` | Latitude and longitude pair |
| `code` | Code editor with syntax highlighting |
| `status` | Predefined workflow states with transitions |

### Relationship canvas

Models are drawn as cards. You draw lines between them to create relationships.
Inox handles junction tables, foreign keys, cascade rules, and eager loading automatically.

---

## ⚡ Real-time

Inox ships with **Laravel Reverb** — a WebSocket server written entirely in PHP.
Real-time works on shared hosting. No Node.js. No separate process in most configurations.

### Built-in real-time features

```
Admin notifications     New comment, failed job, new user signup, module update available
Live content preview    Builder reflects changes as you type, without refreshing
Presence channels       See who is editing what — prevents conflicts
Event Bus monitor       Watch every system event fire in real time
Custom broadcasts       Modules emit and subscribe to events freely
```

### Broadcasting from a module

```php
// Emit from PHP:
broadcast(new OrderStatusChanged($order))->toOthers();
```

```javascript
// Listen in Blade with Alpine.js + Echo:
Echo.private(`orders.${orderId}`)
    .listen('OrderStatusChanged', (e) => {
        $dispatch('order-updated', e)
    })
```

### Real-time Event Bus monitor

```
LIVE EVENTS                                            ● Connected
──────────────────────────────────────────────────────────────────
14:32:01   user.login          user_id: 42   ip: 192.168.1.1
14:32:05   content.updated     post_id: 17   user_id: 42
14:32:09   cache.flushed       driver: file   tags: [posts]
14:32:11   module.installed    module: inox/seo   version: 1.4.2
14:32:18   queue.failed        job: SendWelcomeEmail   attempt: 3
──────────────────────────────────────────────────────────────────
Filter by:  [All events ▾]   [All modules ▾]   [Search...]
```

Modules register their own events and they appear here automatically.

---

## 🔐 Auth Studio

A visual interface to configure authentication without writing code.
Everything here generates configuration — no custom auth code in your project.

### Authentication methods

```
METHOD                          STATUS          NOTES
──────────────────────────────────────────────────────────────────
Email + Password                ✓ Active
API Keys                        ✓ Active        Scoped + rate limited
JWT Tokens                      ✓ Active        Expiry: 7 days
Session (web)                   ✓ Active
Magic Link (passwordless)       ○ Disabled
OAuth2 — Google                 ○ Disabled      Needs client ID/secret
OAuth2 — GitHub                 ○ Disabled
OAuth2 — Discord                ○ Disabled
Two-Factor Auth (TOTP)          ○ Disabled
Device sessions                 ✓ Active        List + revoke per user
```

### Visual Role and Permission Builder

```
ROLE: Editor                                         [Save] [Delete]
──────────────────────────────────────────────────────────────────
Resource          Read      Write     Delete    Publish
──────────────────────────────────────────────────────────────────
Posts             ✓         ✓         ✗         ✓
Pages             ✓         ✓         ✗         ✗
Media             ✓         ✓         ✗         —
Users             ✗         ✗         ✗         —
Modules           ✗         ✗         ✗         —
Settings          ✗         ✗         ✗         —
──────────────────────────────────────────────────────────────────
Custom: Product   ✓         ✓         ✗         —
Custom: Order     ✓         ✗         ✗         —
──────────────────────────────────────────────────────────────────
API endpoints     /api/posts:GET ✓    /api/posts:POST ✓   ...
```

### API Key management

```
KEY                     SCOPES                    RATE LIMIT   STATUS
──────────────────────────────────────────────────────────────────────
sk-inox-...f8a2         products:read             1,000/hour   Active
                        orders:read orders:write
                        Created: 2024-01-15  Last used: 2 min ago
                                                             [Revoke]

sk-inox-...3c1d         *:read (read-only)         500/hour   Active
                        Created: 2024-01-20  Last used: 5 days ago
                                                             [Revoke]
```

Each key has explicit scopes. A key can never do more than you declare.

### Rate limiting

Configurable per route group, per role, and per API key from the admin. No code required.

---

## 🤖 AI Layer — `inox/ai`

The AI Layer is an official module maintained by the Inox team.
It is not part of the core — it is optional, but first-class.
Installing it adds AI capabilities throughout the admin and exposes the Agent API.

### Providers

```php
// config/ai.php — set from admin or .env
'providers' => [
    'default' => env('AI_PROVIDER', 'ollama'),

    'ollama'    => ['url'     => 'http://localhost:11434', 'model' => 'llama3'],
    'lmstudio'  => ['url'     => 'http://localhost:1234',  'model' => 'auto'],
    'anthropic' => ['api_key' => env('ANTHROPIC_KEY'),    'model' => 'claude-sonnet-4-6'],
    'openai'    => ['api_key' => env('OPENAI_KEY'),       'model' => 'gpt-4o'],
    'groq'      => ['api_key' => env('GROQ_KEY'),         'model' => 'llama3-70b-8192'],
    'custom'    => ['url'     => env('CUSTOM_AI_URL')],   // Any OpenAI-compatible endpoint
]
```

You can configure a different provider per capability — for example, use a local model for content suggestions and Claude for module generation.

### AI capabilities in the admin

```
Admin assistant        Ask questions about your system in natural language
Content generation     Draft, translate, summarize, tone-adjust
SEO suggestions        Analyze content, suggest title, description, keywords
Schema suggestions     Describe what you need — AI designs the data model
Module generator       Describe a feature — AI scaffolds the module structure
Config helper          Ask what any setting does — get a plain-language answer
Image alt text         Auto-generate alt text for uploaded media
Spam detection         Flag suspicious content for review
```

### Agent API — programmatic control for LM agents

A secure, audited HTTP endpoint that your AI agents can call to modify the Inox system.
Built for working with LLM-based automation, CI/CD pipelines, and agent frameworks.

```http
POST /api/agent/action
Authorization: Bearer sk-agent-xxxxxxxxxxxxxxxx
Content-Type: application/json

{
    "action": "create_schema",
    "payload": {
        "name": "Product",
        "fields": [
            { "name": "name",        "type": "text",    "required": true },
            { "name": "price",       "type": "decimal", "required": true },
            { "name": "description", "type": "longtext" },
            { "name": "category_id", "type": "relation:belongsTo", "model": "Category" }
        ]
    }
}
```

Available agent actions:

```
Schema & Data
  create_schema          Design a new data model
  update_schema          Add or modify fields on an existing model
  run_migration          Run pending database migrations
  create_content         Create a content entry in any model
  query_content          Fetch content with filters

Modules & Themes
  install_module         Install a module from the marketplace or a URL
  remove_module          Uninstall a module
  list_modules           List installed modules and their status
  make_module            Scaffold a new module skeleton

System
  update_config          Modify a configuration value
  flush_cache            Clear cache (full or by tag)
  deploy_theme           Activate or update a theme
  run_command            Run a whitelisted Inox CLI command

Auth
  create_api_key         Generate a scoped API key
  revoke_api_key         Revoke an API key

Observability
  query_logs             Search the system log
  list_jobs              List pending, running, and failed queue jobs
  retry_failed_jobs      Retry failed queue jobs
```

Every agent action is:

```
✓ Authenticated       Dedicated agent API key, separate from user keys
✓ Scope-limited       The key only has the permissions you explicitly grant
✓ Logged              Full audit trail: action, payload, result, timestamp, IP
✓ Reversible          Where applicable, a rollback is recorded
✓ Rate-limited        Configurable per agent key
```

### AI Layer module structure

```
modules/inox/ai/
├── src/
│   ├── Providers/          One driver class per AI provider
│   ├── Capabilities/       Discrete AI actions (generate, translate, suggest...)
│   ├── AgentApi/           Agent HTTP endpoint and action registry
│   ├── Prompts/            System prompts per capability (editable in admin)
│   └── AiServiceProvider.php
├── resources/
│   └── views/livewire/     Admin UI for AI settings (pure Blade/Livewire)
└── module.json
```

---

## 📦 Module System

Modules are the Inox equivalent of WordPress plugins — designed properly from the start.

### Module manifest — `module.json`

```json
{
    "name": "inox/seo",
    "version": "1.4.2",
    "description": "Full SEO toolkit: sitemap, meta tags, schema.org, redirects",
    "requires": {
        "inox": ">=1.0.0",
        "php": ">=8.3"
    },
    "permissions": {
        "database": ["read:posts", "write:post_meta"],
        "events":   ["content.saved", "content.deleted"],
        "routes":   ["/sitemap.xml", "/robots.txt"],
        "storage":  [],
        "hooks":    ["head.meta", "admin.sidebar", "content.saving"]
    },
    "conflicts": [],
    "authors": [{ "name": "Inox Team", "email": "team@inox.dev" }]
}
```

When you install a module, Inox shows the permission summary before proceeding:

```
Installing inox/seo v1.4.2

This module needs access to:
  📊  Database   read posts, write post meta
  📡  Events     listen to content.saved, content.deleted
  🌐  Routes     register /sitemap.xml, /robots.txt
  🪝  Hooks      modify head meta, add sidebar item, filter content on save
  💾  Storage    none

[Install]  [Cancel]
```

Nothing runs beyond what is declared here.

### Module structure

```
modules/vendor/name/
├── module.json                    Manifest
├── src/
│   ├── Providers/
│   │   └── ModuleServiceProvider.php   Registers everything with Inox
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Requests/
│   ├── Models/                    Module-specific Eloquent models
│   ├── Livewire/                  Livewire component classes (PHP)
│   ├── Events/                    Events emitted by this module
│   ├── Listeners/                 Listeners for core or other events
│   └── Jobs/                      Queue jobs
├── database/
│   └── migrations/                Module-specific migrations
├── resources/
│   └── views/
│       └── livewire/              Blade templates (no build step)
├── routes/
│   ├── web.php
│   └── api.php
├── config/
│   └── seo.php                    Module config, merged into main config
└── tests/                         Required for Marketplace listing
```

### Admin registration — pure PHP, no JavaScript bundle

```php
// ModuleServiceProvider.php
public function boot(): void
{
    // Register Livewire components
    Livewire::component('seo-settings',     SeoSettings::class);
    Livewire::component('seo-sitemap',      SitemapManager::class);
    Livewire::component('seo-redirects',    RedirectManager::class);

    // Register admin pages — Inox renders them automatically
    InoxAdmin::page([
        'route'     => '/admin/seo',
        'label'     => 'SEO',
        'icon'      => 'chart-bar',
        'component' => 'seo-settings',
        'group'     => 'Tools',
    ]);

    // Register hooks
    Hook::on('head.meta', [SeoMetaHook::class, 'inject']);
    Hook::on('content.saving', [SeoSlugHook::class, 'generate']);

    // Register events
    Event::listen(ContentSaved::class, UpdateSitemap::class);
}
```

Zero build step. Zero JavaScript compilation. Pure PHP and Blade.

### Dependency resolution

```bash
php inox module:install inox/ecommerce

# Output:
Resolving dependencies...
  inox/ecommerce 2.1.0  →  requires inox/media >=1.0
  inox/media 1.3.0      →  already installed ✓
  inox/ecommerce 2.1.0  →  requires inox/auth >=1.0
  inox/auth 1.2.0       →  already installed ✓

Plan:
  Install  inox/ecommerce 2.1.0

Permissions required: [shown here]

Proceed? [Y/n]
```

### Hook and Event system

```php
// Core emits hooks — modules intercept them:
Hook::on('content.saving', function (Content $content): Content {
    $content->meta['word_count'] = str_word_count(strip_tags($content->body));
    return $content;
});

Hook::on('head.meta', function (array $meta, Content $content): array {
    $meta[] = view('seo::partials.meta', compact('content'))->render();
    return $meta;
});

// Filters (modify data) and actions (side effects) use the same API:
Hook::filter('content.excerpt', fn($text, $length) => Str::limit($text, $length));
Hook::action('user.registered', fn($user) => Newsletter::subscribe($user->email));
```

---

## 🎨 Theme System

Themes control the public-facing side of your site.
They include templates, components, assets, and their own settings panel.

### Theme structure

```
themes/vendor/mytheme/
├── theme.json              Manifest with name, version, settings schema
├── templates/
│   ├── layout.blade.php    Base HTML shell
│   ├── home.blade.php
│   ├── page.blade.php
│   ├── post.blade.php
│   ├── archive.blade.php
│   ├── search.blade.php
│   └── 404.blade.php
├── components/             Blade components: cards, heroes, navbars, footers
├── assets/
│   ├── css/app.css         Compiled CSS
│   └── js/app.js           Compiled JS (optional, theme-specific)
├── blocks/                 Custom builder blocks registered by this theme
└── screenshots/            Preview images for the admin and marketplace
```

### Theme settings — declared, not coded

Themes declare their settings in `theme.json`. Inox renders the settings panel automatically — no PHP required:

```json
{
    "settings": [
        { "key": "primary_color", "type": "color",   "default": "#3b82f6",      "label": "Brand Color" },
        { "key": "font_body",     "type": "select",  "default": "Inter",
          "options": ["Inter", "Roboto", "Merriweather", "Lora"],                 "label": "Body Font" },
        { "key": "hero_layout",   "type": "select",  "default": "fullscreen",
          "options": ["fullscreen", "boxed", "minimal"],                          "label": "Hero Style" },
        { "key": "show_sidebar",  "type": "boolean", "default": true,            "label": "Show Sidebar" },
        { "key": "footer_text",   "type": "text",    "default": "",              "label": "Footer Note" },
        { "key": "logo",          "type": "media",                               "label": "Site Logo" }
    ]
}
```

Settings are available in all templates via `theme('primary_color')`.

---

## ⚡ Cache System

Three cache layers, working automatically. No manual configuration required.
Upgrade the driver as your site grows — no code changes.

```
Layer 1 — Full Page Cache
  Stores the entire HTML output.
  Fastest possible response: PHP barely runs.
  Tagged by content, menu, widget — only relevant pages flush on change.

Layer 2 — Object Cache
  Caches expensive queries and computed values.
  Used automatically by core and modules.
  Modules can tag their own cache keys for selective flushing.

Layer 3 — Fragment Cache
  Cache parts of a page with different TTLs.
  Example: sidebar cached 1 hour, hero 10 minutes, analytics widget 5 min.
```

### Cache drivers — upgrade path

```
File (default, works everywhere)
  → SQLite (slightly faster file cache)
    → Redis (production, full feature set)
      → Memcached (alternative to Redis)
```

Code does not change when you upgrade the driver. Only config changes.

### Cache control in admin

```
CACHE                                             [Flush All]
──────────────────────────────────────────────────────────────
Full page        312 pages cached    19 MB    [Flush]
Object cache     2,841 keys          4 MB     [Flush]
Fragments        142 fragments       1 MB     [Flush]

Auto-invalidation rules
  content.saved    → flush affected page + related archive pages
  menu.updated     → flush all page cache
  theme.changed    → flush all cache
  module.updated   → flush all cache
```

---

## ⏰ Scheduler and Queues

### Real cron

One entry on the server handles all scheduled tasks:

```bash
* * * * * php /path/to/inox schedule:run >> /dev/null 2>&1
```

Modules register their own scheduled tasks in their ServiceProvider:

```php
Schedule::command('inox:sitemap:generate')->daily()->at('03:00');
Schedule::command('inox:media:cleanup')->weekly();
Schedule::command('inox:cache:warmup')->hourly();
Schedule::call(fn() => Newsletter::sendPending())->everyFiveMinutes();
```

Visual scheduler in admin shows every registered task, next run time, and last result.

### Queue workers

Long-running operations run in the background without blocking the response:

```php
// Dispatch from anywhere:
ProcessUploadedImage::dispatch($media)->onQueue('media');
SendWelcomeEmail::dispatch($user)->delay(now()->addMinutes(2));
GenerateSitemap::dispatch()->onQueue('low');
```

Run a worker:

```bash
php inox queue:work --queue=media,default,low
```

Visual job monitor in admin:

```
QUEUE MONITOR                                   [Pause Workers]
──────────────────────────────────────────────────────────────
Workers running      2
Pending jobs         6
Running              ProcessUploadedImage #2041   15s
Failed               3 jobs                       [Retry All] [View]
Processed today      8,342 jobs
──────────────────────────────────────────────────────────────
Failed jobs                                       [Flush Failed]
  SendWelcomeEmail #2038   Attempt 3/3   Connection timeout   [Retry]
  SendWelcomeEmail #2039   Attempt 3/3   Connection timeout   [Retry]
```

---

## 📧 Email System

Inox includes a complete email system with no extra modules required.

```php
// config/mail.php — set during install or from admin
'driver' => env('MAIL_DRIVER', 'smtp'), // smtp, mailgun, ses, postmark, log (dev)
```

### Transactional emails

Core emails (welcome, password reset, notifications) use Blade templates that themes can override:

```
resources/views/emails/
├── welcome.blade.php
├── password-reset.blade.php
├── notification.blade.php
└── ...
```

Themes can place custom email templates in `themes/mytheme/emails/` to override defaults.

### Email log

Every outgoing email is logged in the admin with recipient, subject, status, and timestamp.

---

## 🔔 Notification System

```php
// Send a notification to a user:
$user->notify(new OrderShipped($order));

// Channels:
//   database   (stored in DB, shown in admin notification bell)
//   mail       (email)
//   broadcast  (real-time via WebSockets)
//   slack      (if inox/slack module installed)
```

The admin shows a notification center with all unread notifications per user.
Modules register their own notification types and channels.

---

## 🔎 Full-text Search

Inox core includes basic database search. For advanced search, install an official module:

```
inox/search-meilisearch    Meilisearch driver — fastest, recommended
inox/search-typesense      Typesense driver — self-hosted alternative
inox/search-algolia        Algolia driver — managed, paid
```

All search modules share the same API so switching drivers is a config change:

```php
// Any model can be made searchable:
class Post extends Model
{
    use Searchable;

    public function toSearchableArray(): array
    {
        return ['title' => $this->title, 'body' => $this->body];
    }
}
```

---

## 🌍 Localization (i18n)

Inox supports multilingual installations out of the box.

```
Supported:
  ✓ Admin panel UI translated via language files
  ✓ Content in multiple languages (locale-aware routes)
  ✓ Date, number, and currency formatting per locale
  ✓ RTL support in admin (Arabic, Hebrew, Persian)
  ✓ Module strings declared in lang/ folders
```

Adding a language is dropping a folder in `lang/` and translating the strings.
No module required. No third-party service.

---

## 🪝 Webhooks

Send HTTP notifications to external services when things happen in Inox.

```
WEBHOOKS
─────────────────────────────────────────────────────────────────
Name               URL                         Events          Status
─────────────────────────────────────────────────────────────────
Zapier trigger     https://hooks.zapier.com/…  content.saved   Active
Deploy hook        https://vercel.com/api/…    content.saved   Active
Slack alerts       https://hooks.slack.com/…   user.blocked    Active
─────────────────────────────────────────────────────────────────
Delivery log: [View last 100 deliveries]
```

Webhooks include signature verification (HMAC-SHA256) so receiving endpoints can verify authenticity.

---

## 🔒 Security

### Baseline hardening (always on)

```
✓ CSRF protection on all POST/PUT/DELETE requests
✓ SQL injection protection via Eloquent parameterized queries
✓ XSS prevention via Blade auto-escaping
✓ Strict Content Security Policy header
✓ HTTP Strict Transport Security (HSTS)
✓ Clickjacking protection (X-Frame-Options)
✓ Rate limiting on login, registration, API, and agent endpoints
✓ Failed login lockout with configurable threshold
✓ Password hashing with bcrypt (cost configurable)
✓ Session rotation on privilege escalation
✓ install.php self-destructs after successful installation
✓ Sensitive config never exposed in responses
✓ File upload validation: type, size, MIME sniffing prevention
✓ Directory traversal protection on file operations
```

### Module security model

Modules are sandboxed by their declared permissions.
A module that does not declare `database:write:users` cannot write to the users table —
the Module Engine intercepts and blocks it before the query runs.

### Audit log

Every consequential admin action is logged:

```
AUDIT LOG
────────────────────────────────────────────────────────────────────
2024-01-20 14:32:01  admin@site.com   content.deleted     Post #47
2024-01-20 14:31:44  admin@site.com   module.installed    inox/seo 1.4.2
2024-01-20 14:28:12  editor@site.com  content.published   Post #46
2024-01-20 14:25:01  agent-key-f8a2   schema.created      Model: Product
────────────────────────────────────────────────────────────────────
Filter: [All users ▾] [All actions ▾] [Date range]   [Export CSV]
```

---

## 💾 Storage

Configured at install time. Changeable later from the admin.

```php
'disks' => [
    'local'  => ['driver' => 'local', 'root'     => storage_path('app/public')],
    's3'     => ['driver' => 's3',    'bucket'   => env('AWS_BUCKET'),
                                       'region'   => env('AWS_REGION')],
    'r2'     => ['driver' => 's3',    'endpoint' => env('R2_ENDPOINT'),
                                       'bucket'   => env('R2_BUCKET')],
    'b2'     => ['driver' => 's3',    'endpoint' => env('B2_ENDPOINT'),
                                       'bucket'   => env('B2_BUCKET')],
    'ftp'    => ['driver' => 'ftp',   'host'     => env('FTP_HOST'),
                                       'username' => env('FTP_USER')],
]
```

The Media Library behaves identically regardless of which driver is active.
Switching storage later: update config + run `php inox storage:migrate`.

---

## 📊 Media Library

```
Features:
  ✓ Upload via drag and drop or file picker
  ✓ Automatic image resizing on upload (configurable sizes)
  ✓ WebP conversion (optional)
  ✓ Folder organization
  ✓ Search by filename, type, date
  ✓ Bulk actions (move, delete, download)
  ✓ Image editor (crop, rotate, flip, brightness)
  ✓ Alt text and caption per file
  ✓ Usage tracking: see which content uses each file
  ✓ Storage driver transparent: same UI for local and S3
```

---

## 🖥️ CLI — `php inox`

The full CLI ships with Inox. No WP-CLI equivalent to install separately.

```bash
# Install & setup
php inox install                 Interactive installer
php inox install --quick         Defaults, no prompts (CI/CD)

# Modules
php inox module:install vendor/name
php inox module:remove vendor/name
php inox module:update vendor/name
php inox module:list
php inox make:module vendor/name  Scaffold a new module

# Themes
php inox theme:install vendor/name
php inox theme:activate vendor/name
php inox make:theme vendor/name

# Database
php inox migrate
php inox migrate:rollback
php inox migrate:status
php inox db:seed
php inox db:export output.sql
php inox db:import backup.sql

# Cache
php inox cache:flush
php inox cache:flush --layer=page
php inox cache:warmup             Pre-cache all public pages

# Users
php inox user:create
php inox user:list
php inox user:reset-password email@example.com
php inox user:assign-role email@example.com admin
php inox user:block email@example.com

# Scheduler & Queues
php inox schedule:run
php inox schedule:list
php inox queue:work
php inox queue:retry all
php inox queue:flush-failed

# Storage
php inox storage:migrate          Move files between drivers
php inox media:cleanup            Remove unused media files
php inox media:regenerate         Regenerate image sizes

# System
php inox system:info              PHP, extensions, disk, DB version
php inox system:check             Health check: DB, cache, storage, queues
php inox config:cache
php inox config:clear

# Development
php inox make:model ProductType   Scaffold model + migration
php inox make:livewire MyComponent
php inox make:job ProcessPayment
php inox make:event OrderPlaced
php inox make:listener SendInvoice
php inox make:policy ProductPolicy
```

---

## 🗺️ Migration from WordPress

Inox ships with a first-class WordPress importer:

```bash
php inox wp:import /path/to/wordpress-export.xml

# Imports:
#   Posts → Posts
#   Pages → Pages
#   Categories and tags → preserved
#   Media → re-downloaded or copied
#   Users → imported (passwords reset required)
#   Custom post types → mapped to Inox content types
#   ACF fields → mapped to Inox custom fields where possible
```

Available as a UI wizard in the admin for non-technical users.

---

## 📁 Project Structure

```
inox/
├── app/
│   ├── Core/
│   │   ├── Installer/           Installation wizard logic
│   │   ├── ModuleEngine/        Module lifecycle and sandboxing
│   │   ├── ThemeEngine/         Template rendering and settings
│   │   ├── HookSystem/          Filter and action bus
│   │   ├── SchemaBuilder/       Visual schema → file generator
│   │   ├── AgentBridge/         AI Agent API gateway
│   │   └── AuditLogger/         Admin action audit trail
│   ├── Http/
│   │   ├── Controllers/         Core controllers (web + API)
│   │   └── Middleware/          Auth, cache, rate limit, module sandbox
│   ├── Livewire/                Core Livewire components
│   ├── Models/                  Core Eloquent models
│   └── Providers/               Core service providers
│
├── modules/                     Installed modules
│   └── inox/
│       ├── ai/                  Official AI Layer module
│       ├── seo/                 Official SEO module
│       ├── media/               Media library module
│       ├── ecommerce/           E-commerce module
│       └── search-meilisearch/  Search module
│
├── themes/                      Installed themes
│
├── schema/                      Visual Schema Builder output (in Git)
│   └── *.schema.json
│
├── database/
│   ├── migrations/              Core migrations
│   └── seeders/
│
├── resources/
│   ├── views/                   Core Blade templates
│   │   ├── admin/               Admin panel layouts and pages
│   │   ├── livewire/            Core Livewire component views
│   │   └── emails/              Core email templates
│   ├── lang/                    Translations
│   └── vue/                     Vue 3 islands (builder only)
│       ├── builder/             Visual Page Builder
│       ├── schema-studio/       Visual Schema Builder canvas
│       └── event-monitor/       Real-time Event Bus UI
│
├── storage/
│   └── app/
│       └── database.sqlite      Default database
│
├── config/
│   ├── inox.php                 Master CMS config
│   ├── cache.php
│   ├── storage.php
│   ├── mail.php
│   └── ai.php
│
├── public/
│   └── build/                   Compiled Vue islands (pre-built in releases)
│
└── inox                         CLI entry point
```

---

## 🗺️ Roadmap

### Phase 1 — Core (Months 1–3)
- [ ] Installation wizard (web + CLI)
- [ ] Content management (posts, pages, custom types)
- [ ] User system with roles and permissions
- [ ] Admin panel base (Blade + Livewire + Alpine.js)
- [ ] REST API (full CRUD, authentication, versioning)
- [ ] SQLite and MySQL support
- [ ] File cache layer
- [ ] Email system (SMTP, transactional templates)
- [ ] Audit log
- [ ] CLI foundation

### Phase 2 — Ecosystem (Months 4–6)
- [ ] Module system (install, remove, update, permission sandbox)
- [ ] Theme system (templates, settings panel, override chain)
- [ ] Hook and Event system
- [ ] Auth Studio (visual auth configuration)
- [ ] Real-time via Laravel Reverb
- [ ] Queue and scheduler with admin monitor
- [ ] S3, R2, B2, FTP storage drivers
- [ ] Redis cache driver
- [ ] Webhook system (outbound)
- [ ] Notification center

### Phase 3 — Visual Builder (Months 7–10)
- [ ] Visual Page Builder (Vue 3 island, drag and drop blocks)
- [ ] Live preview
- [ ] Block library (text, image, video, embed, grid, hero, CTA...)
- [ ] Theme-aware block styling
- [ ] Media library with image editing

### Phase 4 — Schema Studio (Months 8–11, parallel)
- [ ] Visual Schema Builder canvas (Vue 3 island)
- [ ] Relationship drawing between models
- [ ] Files-first generation: schema.json → migration → model → API
- [ ] Auto-generated admin pages per schema
- [ ] OpenAPI documentation export
- [ ] TypeScript types export

### Phase 5 — AI Layer (Months 10–12)
- [ ] `inox/ai` module: multi-provider support
- [ ] Admin AI assistant
- [ ] Agent API with full audit log
- [ ] Module scaffolding from natural language description
- [ ] Schema suggestion from description
- [ ] Content generation and SEO suggestions

### Phase 6 — Enterprise (Month 13+)
- [ ] GraphQL API (opt-in, via module)
- [ ] PostgreSQL support
- [ ] Multisite (clean architecture, not an afterthought)
- [ ] Module marketplace with signature verification
- [ ] E-commerce module (products, cart, orders, payments)
- [ ] Full-text search modules (Meilisearch, Typesense, Algolia)
- [ ] Two-factor authentication (TOTP)
- [ ] OAuth2 providers (Google, GitHub, Discord)
- [ ] WordPress importer
- [ ] i18n and multilingual content
- [ ] RTL admin support

---

## 🔑 Design Principles

**Files over magic.**
Schema definitions, hook registrations, and module configs are real files.
Your codebase is the source of truth. No config hidden in the database.

**Explicit over implicit.**
Modules declare what they access. Nothing runs silently.
You see exactly what a module can touch before it is installed.

**Shared hosting first.**
Every feature must work on shared hosting or have a graceful fallback.
WebSockets fall back to polling. Queues fall back to synchronous execution.
No feature silently breaks in a constrained environment.

**Right tool for each job.**
Blade + Livewire for server-rendered UI.
Alpine.js for micro-interactions.
Vue 3 only for features that genuinely need a rich client canvas.

**Upgrade paths, not lock-in.**
Start SQLite, upgrade to MySQL. Start with file cache, upgrade to Redis.
Start local storage, move to S3. Your code does not change.

**Developer experience is a feature.**
Module scaffolding in one command. Visual schema generation.
Agent API for automation. Good CLI. Clear conventions.

**AI is optional infrastructure.**
The AI Layer is a module. Inox works fully without it.
When you add it, it integrates deeply — it is not a chatbot bolted on the side.

**Community over control.**
MIT license. Open marketplace.
Modules and themes can be commercial — Inox does not take a cut.

---

## 🤝 Contributing

```bash
git clone https://github.com/inox/inox
cd inox
composer install
npm install && npm run build
php inox install --dev
php inox serve
```

The best way to start contributing is to build a module.
The module system is the heart of the ecosystem.

Please read [CONTRIBUTING.md](CONTRIBUTING.md) before submitting a PR.
All PRs require tests. All modules submitted to the marketplace require tests.

---

## 📄 License

MIT — free to use, modify, and distribute.
Commercial modules and themes are permitted and encouraged.
Inox does not charge a marketplace fee.

---

<div align="center">

**Inox — Built to last. Designed to run anywhere.**

[Website](https://inox.dev) · [Docs](https://docs.inox.dev) · [Modules](https://modules.inox.dev) · [Discord](https://discord.gg/inox) · [Twitter](https://twitter.com/inoxdev)

</div>