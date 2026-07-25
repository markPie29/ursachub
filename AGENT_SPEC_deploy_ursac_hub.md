# Spec: Dockerize & Deploy URSAC Hub as a Free Public Demo

## Objective
Make URSAC Hub (Laravel) deployable as a free, public, portfolio-linkable demo on
Render.com's free web service tier, using Docker + SQLite so no paid database or
external SQL server is required.

## Scope
- Add Docker + deploy tooling to the repo.
- Switch the demo's DB driver to SQLite (production/local dev DB config is untouched —
  this is a separate demo configuration, not a replacement for the real setup).
- Do NOT touch business logic, routes, controllers, models, or existing migrations.

---

## MANDATORY: Gather context before writing anything

Before creating or editing a single file, inspect the actual repo and confirm:

1. **PHP version** — open `composer.json`, check `"require": { "php": "..." }`.
   The base image in the provided Dockerfile ships PHP 8.2.7. If the repo requires
   8.3+, stop and flag this — do not silently downgrade or force-install.
2. **Frontend build tooling** — check for `vite.config.js`/`vite.config.ts` or
   `webpack.mix.js` and a `package.json` build script. If none exists (no npm build
   step), delete Stage 1 of the Dockerfile and the corresponding `COPY --from=assets`
   line — do not leave a dead build stage in.
3. **Queue/cache/mail usage** — grep for `Queue::`, `dispatch(`, `Mail::`, and check
   `config/queue.php` / `config/cache.php` / `config/session.php` defaults. This spec
   assumes `sync` queue, `file` cache, `file` sessions (no Redis/Memcached). If the app
   hard-depends on Redis (e.g. broadcasting, rate limiting via Redis), flag it — don't
   silently strip that functionality.
4. **Existing migrations/seeders** — confirm `database/seeders/DatabaseSeeder.php`
   actually seeds enough data for a demo to look populated (not just a blank admin
   user). If seeders are thin or missing, flag it before deploying — a demo with an
   empty database defeats the purpose.
5. **File uploads** — grep for `Storage::disk('local')` or `public` disk usage
   (profile photos, attachments, etc). Flag any feature that writes user-uploaded
   files to local disk — see "What NOT to Do" below.
6. **.env.example** — confirm one exists and is current. The deploy script copies it
   to `.env` if no `.env` is present in the image.

Report findings back before proceeding if anything in 1–5 doesn't match this spec's
assumptions. Do not silently work around a mismatch.

---

## Files to add (already drafted — place at these exact paths)

```
Dockerfile                          → repo root
.dockerignore                       → repo root
scripts/00-laravel-deploy.sh        → repo root
conf/nginx/nginx-site.conf          → repo root
```

Content for each is provided alongside this spec. Copy verbatim, then adjust only
per the context-gathering findings above (e.g. remove Stage 1 if no npm build step,
change base image tag if PHP 8.3+ is required).

After copying `scripts/00-laravel-deploy.sh`, make it executable:
```
chmod +x scripts/00-laravel-deploy.sh
```

## .env additions (demo-specific — do not overwrite existing local .env)

```
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
APP_ENV=production
APP_DEBUG=false
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

Remove/comment out any `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` lines for
the demo `.env` — they're not used by SQLite and their presence doesn't break
anything, but leave a comment noting they're irrelevant here to avoid confusion later.

---

## What NOT to Do

- Do NOT commit real `.env` values (API keys, mail credentials, third-party
  secrets) into the repo, Docker image, or Render dashboard as plaintext in commit
  history — use Render's environment variable UI for anything sensitive.
- Do NOT enable `APP_DEBUG=true` on the deployed demo. A public demo with debug
  mode on leaks stack traces, `.env` values, and file paths to anyone who triggers
  a 500 error.
- Do NOT wire up real payment gateways, real email sending, or real SMS/OTP
  providers in the demo `.env`. If URSAC Hub has any of these integrations, stub or
  disable them for the demo build — a public link is not the place for live
  Stripe/Twilio credentials.
- Do NOT assume uploaded files (user avatars, documents, etc.) will persist.
  Render's free-tier filesystem is ephemeral — anything written outside the image
  build is gone on restart. Do not present file-upload features in the demo as if
  they're durable; either disable that feature for the demo or clearly label it as
  non-persistent.
- Do NOT change `migrate:fresh --seed` to a conditional/incremental migration
  without understanding the tradeoff — see checklist. This is intentional for a
  stateless free-tier demo.
- Do NOT alter existing production deployment configs, CI/CD, or the real database
  connection settings used outside this demo path.
- Do NOT install or enable Xdebug or any dev-only PHP extensions in the production
  image — increases image size and can leak internals.

## Guardrails

- If any step in "gather context" surfaces a mismatch (PHP version, Redis
  dependency, missing seeders), stop and report — do not improvise a silent fix.
- If `composer install --no-dev` fails due to a PHP version mismatch, do not force
  `--ignore-platform-reqs` as a permanent fix — that masks a real incompatibility.
  Report it instead.
- Treat this as a separate demo deployment path, not a replacement for however
  URSAC Hub is actually meant to run in production.

---

## Verification steps (after deploy)

1. Visit the Render URL — confirm the app loads (allow ~30–60s for cold start if
   the service had been idle).
2. Confirm login/demo credentials work (whatever `DatabaseSeeder` creates).
3. Trigger a 404 and a validation error — confirm no stack trace / debug info is
   shown (`APP_DEBUG=false` working).
4. Manually restart the Render service and reload the site — confirm it comes back
   up clean with reseeded data (proves the `RUN_SCRIPTS` deploy path is reliable,
   not a one-time fluke).
5. Check Render's build logs for any suppressed composer/npm warnings worth noting.

See `DEPLOYMENT_CHECKLIST.md` for the full pre-flight and post-deploy checklist.
