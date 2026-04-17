# Tailwind Manager

A WordPress admin feature that lets you manage the `@source inline()` directive in `resources/css/app.css` and trigger a Tailwind CSS build — all from wp-admin, without touching the filesystem directly.

Located at **Settings → Tailwind Manager**.

---

## What it does

- Reads the current value inside `@source inline("...")` from `app.css` and pre-populates a textarea on page load
- On save, writes the new value back to `app.css` in-place, replacing only the content inside the quotes
- Provides a **Run npm run build** button that executes `npm run build` from the theme root and streams stdout/stderr output back to the page via AJAX

---

## Structure

Each class has a single responsibility. `TailwindManager` is the only entry point — it builds the dependency graph and registers all WordPress hooks.

```
tailwind-manager/
├── TailwindManager.php      Bootstrapper. Wires dependencies to WordPress hooks.
├── AdminPage.php            Registers the Settings menu page and renders the HTML.
├── SaveHandler.php          Handles the form POST (cap check, nonce, sanitise, write).
├── BuildAjaxHandler.php     Handles the AJAX build request (cap check, nonce, respond).
├── CssEditor.php            Reads and writes @source inline() in app.css via WP_Filesystem.
├── BuildRunner.php          Writes build.trigger and polls for build.result.
├── watch-trigger.js         Node watcher — run on the host to execute npm run build.
└── README.md                This file.
```

### How the build works

Because `npm` runs on the host machine (not inside the Docker PHP container), the build uses a filesystem handshake:

1. PHP writes `build.trigger` to the theme root
2. `watch-trigger.js` (running on the host) detects it, runs `npm run build`, writes output to `build.result`
3. PHP polls for `build.result`, reads it, and returns the output to the page

`build.trigger` and `build.result` are written to the theme root (the shared Docker volume) so both sides can see them. They are gitignored.

**Start the watcher once in a terminal on your Mac:**

```bash
node web/app/themes/vanilla-hair-and-beauty/app/features/tailwind-manager/watch-trigger.js
```

---

## Security

- All endpoints are restricted to users with the `manage_options` capability
- All form submissions and AJAX requests are protected by WordPress nonces
- All `$_POST` input is sanitised before use
- `proc_open` is called with an argument array (no shell string) — no shell injection risk, no `escapeshellarg` required

---

## Changes required outside this feature

Two files outside `tailwind-manager/` must be modified for the feature to load.

### 1. `composer.json` — add a PSR-4 autoload entry

The existing mapping `"App\\": "app/"` does not resolve hyphenated directory names on case-sensitive filesystems. Add a dedicated entry:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "App\\Features\\TailwindManager\\": "app/features/tailwind-manager/"
    }
}
```

After editing, regenerate the autoloader:

```bash
composer dump-autoload
```

### 2. `app/setup.php` — bootstrap the feature in admin only

Add the following at the end of `setup.php`, outside any existing hook callbacks:

```php
if ( is_admin() ) {
    ( new \App\Features\TailwindManager\TailwindManager() )->register();
}
```

The `is_admin()` guard ensures the feature classes are never instantiated on front-end requests.
