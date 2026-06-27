<div align="center">
  <br>
  <img src="https://raw.githubusercontent.com/maicolrme/inoxcms/main/docs/assets/inox-badge.svg" width="90" alt="InoxCMS">
  <br><br>
  <h1>InoxCMS</h1>
  <p><strong>A modern PHP CMS that doesn't rust.</strong></p>
  <p>Laravel-powered · Modular · Marketplace · REST API · Visual Schema Builder</p>
  <br>
  <p>
    <a href="https://maicolrme.github.io/inoxcms/">📖 Documentation</a>&nbsp;&nbsp;·&nbsp;&nbsp;
    <a href="https://github.com/maicolrme/inoxcms">🐙 GitHub</a>&nbsp;&nbsp;·&nbsp;&nbsp;
    <a href="#-quick-start">🚀 Quick Start</a>
  </p>
  <br>
  <p>
    <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php" alt="PHP 8.3+">
    <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel" alt="Laravel 13">
    <img src="https://img.shields.io/badge/Livewire-4-FB70A9?style=flat-square" alt="Livewire 4">
    <img src="https://img.shields.io/badge/license-MIT-blue?style=flat-square" alt="License MIT">
    <img src="https://img.shields.io/badge/status-alpha-yellow?style=flat-square" alt="Status Alpha">
  </p>
  <br>
</div>

<br>

<p align="center">
  WordPress was built in 2003. InoxCMS is what it would be if designed today —<br>
  with modern PHP, clean architecture, and a developer experience that doesn't get in the way.
</p>

<br>

---

## ✨ Overview

InoxCMS is a **Laravel 13** based content management system that hides the framework internals inside a `core/` directory, giving you a clean project root that looks and feels like a CMS — not a Laravel app.

| What | How |
|---|---|
| **Content** | Posts, pages, categories, tags — same `posts` table (like WordPress) |
| **Modules** | Self-contained packages with their own migrations, views, and Livewire components |
| **Themes** | Switchable themes with template selection per page |
| **Settings** | All stored in DB (not `.env`). Modules & themes register their own schema |
| **API** | RESTful with visual route manager — toggle endpoints on/off from the admin |
| **Schema Studio** | Design data models visually → auto-generate migrations, models, policies, and CRUD |
| **Admin** | Built entirely with Livewire 4 — reactive, no JavaScript build step |
| **Marketplace** | Modules & themes distributed as GitHub repos, fetched from `raw.githubusercontent.com` |

<br>

---

## 🎯 Why InoxCMS?

```
  WordPress                 InoxCMS
  ─────────                 ──────
  20 years of legacy        Clean OOP on Laravel 13
  Procedural spaghetti      Service providers, DI, events
  wp_postmeta hell          Proper relational schema + migrations
  No real module system     ModuleEngine with explicit permissions
  Plugin conflict chaos     Composer-based resolution
  Bolted-on REST API        API-first, per-route toggles
  No data modeling          Visual Schema Builder → full CRUD
  Shared hosting only       Shared hosting + VPS + cloud
```

<br>

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────┐
│               PROJECT ROOT                  │
│                                             │
│  index.php     artisan     .env             │
│  modules/      themes/     schema/          │
│  docs/         vendor/     composer.json    │
│  ─────────────────────────────────────────  │
│  core/  ← All Laravel internals abstracted  │
│   ├── app/      (Models, Livewire, Core)    │
│   ├── config/   (inox.php, filesystems...)  │
│   ├── bootstrap/(app.php, env path)         │
│   ├── database/ (migrations, seeders)       │
│   ├── routes/   (web.php, api.php)          │
│   └── vendor/   (Composer dependencies)     │
└─────────────────────────────────────────────┘
```

**Key design decision:** `cms_path()` helper returns the project root, so all modules, themes, and `.env` references work regardless of where Laravel thinks the base path is.

<br>

---

## 🚀 Quick Start

```bash
git clone https://github.com/maicolrme/inoxcms.git
cd inoxcms

# Install Laravel dependencies (inside core/)
cd core && composer install && cd ..

# Setup environment
cp .env.example .env
php artisan key:generate

# Run the interactive installer
php artisan inox:install

# Or skip prompts with defaults
php artisan inox:install --quick

# Start the dev server
php artisan serve
```

Then visit **`http://127.0.0.1:8000/admin`** and log in with your admin credentials.

> [!NOTE]
> Modules and themes are managed as git submodules from separate marketplace repos. Initialize with `git submodule init && git submodule update`.

<br>

---

## 📦 Modules

Modules are self-contained packages that extend CMS functionality. Each module has its own `module.json` manifest, ServiceProvider, migrations, views, and Livewire components.

### Available Now

| Module | Description |
|---|---|
| **SEO** (`inox/seo`) | Meta tags, sitemap (`/sitemap.xml`), robots.txt, Open Graph |
| **API** (`inox/api`) | RESTful API, Sanctum tokens, visual route manager, request logging |
| **Storage** (`inox/storage`) | Media library, file uploads, local/S3/R2 drivers |
| **Schema Studio** (`inox/schema-studio`) | Visual data model designer → migrations, models, policies, CRUD |

### Module Structure

```
modules/inox/seo/
├── module.json              # Name, version, provider, permissions
├── config/                  # Merged into app config
├── database/migrations/     # Run on activation
├── resources/views/         # Blade templates
├── routes/                  # Web & API routes
└── src/
    ├── Providers/           # ModuleServiceProvider
    ├── Livewire/            # Admin UI components
    ├── Models/              # Eloquent models
    └── Http/                # Controllers, middleware
```

Modules register with the CMS via hooks, event listeners, and admin nav items — all in PHP, no build step required.

<br>

---

## 🎨 Themes

Themes control the public-facing side of your site. Switch them from the admin dropdown.

```
themes/inox/simple/
├── theme.json          # Manifest + settings schema
├── templates/          # layout, home, page, post, archive, search, 404
└── assets/             # CSS, JS
```

Themes declare settings in `theme.json` — the admin panel renders the settings form automatically:

```json
{
  "settings": [
    { "key": "primary_color", "type": "color", "default": "#2563eb", "label": "Brand Color" },
    { "key": "footer_text",   "type": "text",  "default": "",        "label": "Footer Note" }
  ],
  "templates": ["layout", "home", "page", "post"],
  "assets": { "css": ["assets/css/app.css"] }
}
```

**Create a theme:**

```bash
php artisan inox:make-theme mytheme --vendor=mycompany
```

<br>

---

## 🗄️ Settings System

All settings are stored in the `inox_settings` table, not in `.env`. The `SettingRegistry` provides:

- **Schema registration** — modules & themes declare their settings
- **Autoload** — frequently accessed settings cached in Laravel config
- **Helper functions** — `setting('key', 'default')` and `theme('key', 'default')`
- **Clean purge** — uninstalling a module removes its settings

```
setting('site_name', 'My Site');     // Global setting
theme('primary_color', '#2563eb');   // Theme-specific setting
```

<br>

---

## 🧩 Schema Studio

The visual schema builder lets you design data models and generates everything automatically:

```
  Design a "Product" model in the visual canvas
                    ↓
  Inox generates:   schema/product.schema.json
                    database/migrations/xxxx_create_products_table.php
                    app/Models/Product.php
                    app/Policies/ProductPolicy.php
                    routes/api.php (CRUD endpoints)
                    OpenAPI spec + TypeScript types
                    Admin CRUD UI (auto)
```

### Field Types

`string` · `text` · `number` · `boolean` · `email` · `url` · `date` · `datetime` · `image` · `relationship` · `select` · `json` · `slug` · `color` · `code` · `status`

### Dynamic CRUD API

```
GET    /api/dynamic/{model}      # List (paginated)
POST   /api/dynamic/{model}      # Create
GET    /api/dynamic/{model}/{id} # Show
PUT    /api/dynamic/{model}/{id} # Update
DELETE /api/dynamic/{model}/{id} # Destroy
```

Each route can be toggled on/off from the Route Manager in the admin panel.

<br>

---

## 🔌 API Module

The API module provides a full RESTful layer:

- **Sanctum token authentication** — create and revoke tokens from admin
- **Visual Route Manager** — enable/disable individual routes per method
- **Request logging** — full request/response log with status codes and timing
- **Rate limiting** — configurable per IP
- **Dynamic model CRUD** — auto-generated endpoints for Schema Studio models

```
# Create an API token
POST /api/tokens

# Use in requests
Authorization: Bearer {your-plain-text-token}
```

<br>

---

## 📸 Storage & Media

The storage module provides a media library with file upload support:

```
Features:
  ✓ Upload via drag & drop or file picker
  ✓ Grid & list views
  ✓ Search by filename
  ✓ Edit alt text & captions
  ✓ Image preview with dimensions
  ✓ Multiple disk drivers
```

**Configure the driver:**

```env
STORAGE_DISK=local    # Options: local, s3, r2
```

Files are served directly via the web server through a junction/symlink at `public/storage → storage/app/public`.

<br>

---

## 🔐 Roles & Permissions

RBAC system with fine-grained permission management:

- **Roles** — group permissions together (Admin, Editor, Author)
- **Permissions** — fine-grained access control (manage_posts, manage_users)
- **Dynamic Gates** — Laravel authorization gates registered automatically
- **Super Admin** — bypasses all permission checks
- **Middleware** — `can:permission_slug` middleware alias

```php
$user->hasPermission('manage_posts');  // Check permission
$user->hasRole('admin');               // Check role
@can('manage_posts') ... @endcan       // Blade directive
```

<br>

---

## 🪝 Hook System

WordPress-style filter/action API for extending the CMS without modifying core code:

```php
// Filter — modify data
Hook::filter('head.meta', function ($tags) {
    $tags[] = '<meta name="author" content="InoxCMS">';
    return $tags;
}, 10);

// Action — side effect
Hook::action('content.saved', function ($post) {
    Log::info('Content saved: ' . $post->id);
}, 10);

// Apply and execute anywhere
$meta = Hook::apply('head.meta', $defaultTags);
Hook::execute('module.activated', $moduleName);
```

**Core hooks:** `head.meta` · `content.saving` · `content.saved` · `content.deleted` · `admin.sidebar` · `module.activated` · `module.deactivated`

<br>

---

## 🖥️ CLI Commands

InoxCMS extends Artisan with CMS-specific commands:

```bash
# Installation
php artisan inox:install              Interactive wizard
php artisan inox:install --quick      Defaults, no prompts

# Theme scaffolding
php artisan inox:make-theme mytheme   Generate a complete theme

# Development server
php artisan inox:serve --port=8080

# Marketplace updates
php artisan inox:check-updates        Check for module/theme updates
```

All standard Laravel Artisan commands are also available.

<br>

---

## 🗺️ Roadmap

| Phase | Timeline | Focus |
|---|---|---|
| **Core** | Now | Content, users, roles, admin panel, REST API, installer |
| **Ecosystem** | Next | Marketplace maturity, module sandboxing, webhooks, notifications |
| **Visual Builder** | Future | Drag-and-drop page builder (Vue 3 island) |
| **AI Layer** | Future | Multi-provider AI assistant, agent API |
| **Enterprise** | Future | PostgreSQL, multisite, e-commerce, GraphQL |

<br>

---

## 📁 Project Structure

```
inoxcms/
├── core/                   # Laravel internals (abstracted)
│   ├── app/
│   │   ├── Console/Commands/
│   │   ├── Core/           # ModuleEngine, ThemeEngine, SettingRegistry, HookSystem, Installer
│   │   ├── Http/Controllers/
│   │   ├── Http/Middleware/
│   │   ├── Livewire/       # Admin panel components
│   │   └── Models/
│   ├── bootstrap/
│   ├── config/
│   ├── database/migrations/
│   ├── public/             # Entry point, storage junction
│   ├── resources/views/
│   ├── routes/
│   ├── storage/
│   ├── vendor/             # Composer dependencies
│   └── composer.json
├── modules/                # Marketplace modules (git submodule)
├── themes/                 # Themes (git submodule)
├── schema/                 # Schema Studio output
├── docs/                   # Documentation site (GitHub Pages)
├── index.php               # Web entry point
├── artisan                 # CLI entry point
├── .env                    # Environment config (project root)
└── composer.json           # Root manifest
```

<br>

---

## 🧑‍💻 Contributing

```bash
git clone https://github.com/maicolrme/inoxcms.git
cd inoxcms
cd core && composer install && cd ..
php artisan inox:install --quick
php artisan serve
```

The best way to contribute is to build a module or theme. Check the [documentation](https://maicolrme.github.io/inoxcms/) for guides.

<br>

---

## 📄 License

**MIT** — free to use, modify, and distribute. Commercial modules and themes are welcome.

<br>

---

<div align="center">
  <p><strong>Built with Laravel 13 · Livewire 4 · Tailwind CSS</strong></p>
  <p>
    <a href="https://maicolrme.github.io/inoxcms/">📖 Docs</a>&nbsp;&nbsp;·&nbsp;&nbsp;
    <a href="https://github.com/maicolrme/inoxcms">🐙 GitHub</a>&nbsp;&nbsp;·&nbsp;&nbsp;
    <a href="https://github.com/maicolrme/inoxcms/issues">🐛 Issues</a>
  </p>
  <br>
  <p>
    <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php" alt="PHP 8.3+">
    <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel" alt="Laravel 13">
    <img src="https://img.shields.io/badge/license-MIT-blue?style=flat-square" alt="MIT">
  </p>
  <br>
</div>
