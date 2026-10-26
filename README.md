# Zana website skeleton

A starting point for a website on [Zana](https://github.com/mjnable/zana-docs): pages in English
and French, templates, page metadata, versioned assets, security headers, translated error pages,
and upload archives for shared hosting.

```
composer create-project mj-zana/skeleton-web my-site
cd my-site
composer serve
```

Open http://localhost:8000. `composer create-project` writes `.env` with a fresh secret.

## What is where

| Path | |
|---|---|
| `src/Bootstrap.php` | The framework's providers |
| `config/` | Settings; `APP_A__B` in the environment or `.env` overrides `a.b` |
| `modules/Site/` | The pages: routes in `SiteModule`, `PageController`, templates and translations |
| `templates/` | The layout and the error pages |
| `translations/` | Texts shared by every module |
| `public/` | The document root: `index.php`, assets, `503.html` |
| `tests/` | Browser tests: `composer test` |

Add pages with the maker (`composer require --dev mj-zana/maker`):

```
php bin/console make:page Site Services
```

## Going live

1. On the server, set `APP_ENV=production`, `APP_APP__URL` and `APP_SECURITY__SECRET`, in the
   environment or in `.env`.
2. Make `var/` writable.
3. Check the server: `php bin/console doctor`.

**The document root is `public/`.** When the hosting fixes the document root to the project folder,
the root `.htaccess` sends every request into `public/`, so `.env`, `config/` and `vendor/` cannot be
reached.

**Shared hosting without SSH** (cPanel and the like, where `public_html` is fixed and Composer cannot
run):

```
php bin/console release:build
```

It writes two archives from the last commit, with the production dependencies installed:

- `build/website.zip`: unpack it beside `public_html`, so the project is in `~/website`;
- `build/public_html.zip`: unpack it into `public_html`. Its `index.php` finds the project in
  `../website` (`--folder` picks another name).

Create `~/website/.env` on the server, and delete `~/website/var/cache` after each upload so the
container is compiled again.

The [deployment guide](https://github.com/mjnable/zana-docs/blob/main/deployment.md) covers all of it.

## License

MIT
