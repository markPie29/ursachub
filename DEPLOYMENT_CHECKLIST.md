# URSAC Hub — Free Demo Deployment Checklist

## A. Before touching Docker at all

- [ ] `composer.json` PHP version constraint checked against the Docker base image
      (richarvey/nginx-php-fpm:3.1.6 = PHP 8.2.7). Mismatch → either bump/pin the
      constraint if safe, or swap base image tag.
- [ ] Confirmed whether the app has an npm/Vite build step (`package.json` +
      `vite.config.*`). If none, removed Stage 1 from the Dockerfile.
- [ ] `.env.example` exists, is current, and does NOT contain real secrets.
- [ ] Checked `config/database.php` — confirmed a `sqlite` connection entry exists
      (Laravel ships one by default; custom configs may have removed it).
- [ ] Confirmed `database/seeders/DatabaseSeeder.php` produces enough demo data
      to make the app look real (sample users, sample records) — not just one
      empty admin account.
- [ ] Searched the codebase for hardcoded `localhost`, `127.0.0.1`, or absolute
      local file paths that would break in a container.
- [ ] Searched for any `Storage::disk('local')` / file upload features. Decided:
      disable for demo, or accept non-persistence and label it in the UI/README.
- [ ] Checked for Redis-dependent features (broadcasting, rate limiters, specific
      cache tags). If present, either provision a free Redis alternative or
      gracefully degrade that feature for the demo.
- [ ] Checked for scheduled tasks (`app/Console/Kernel.php` schedule) that assume
      a cron runner — free tier has no cron by default; note as a known limitation
      if relevant to the demo.
- [ ] Removed/stubbed any real third-party API keys (payment, SMS, email sending)
      so the demo can't accidentally hit live services or leak billing.

## B. Docker files

- [ ] `Dockerfile` placed at repo root, PHP version confirmed correct.
- [ ] `.dockerignore` placed at repo root — confirms `.env`, `vendor/`,
      `node_modules/` are excluded from the image.
- [ ] `scripts/00-laravel-deploy.sh` placed at `scripts/`, marked executable
      (`chmod +x`).
- [ ] `conf/nginx/nginx-site.conf` placed at `conf/nginx/`.
- [ ] Confirmed `WEBROOT` env var in the Dockerfile matches the actual public
      folder (`/var/www/html/public` — standard Laravel, don't change unless the
      repo structure is non-standard).
- [ ] Local test: `docker build -t ursac-hub-demo .` completes without errors.
- [ ] Local test: `docker run -p 8080:80 ursac-hub-demo` and the app loads at
      `localhost:8080`.

## C. Laravel/app-level demo settings

- [ ] `APP_ENV=production`, `APP_DEBUG=false` — confirmed, not left on `true`.
- [ ] `APP_KEY` — confirmed the deploy script runs `key:generate --force` (don't
      hardcode a key in the repo).
- [ ] `APP_URL` set to whatever the eventual Render URL will be (update after
      first deploy once the URL is known, then redeploy or set it as a Render
      env var so it's correct from the start).
- [ ] Mail driver set to `log` or disabled for demo (no real emails sent from a
      public demo).
- [ ] Any registration/contact forms that send real emails — decided how they
      should behave in demo mode (log only, or disabled with a note).
- [ ] CORS / Sanctum config checked if the frontend is a separate SPA — confirm
      it'll work against the Render demo domain.

## D. Render.com setup

- [ ] Repo pushed to GitHub (Render deploys from Git).
- [ ] New Web Service created on Render, connected to the repo, runtime set to
      **Docker**.
- [ ] Confirmed **no credit card was required** to create the free web service.
- [ ] Environment variables set in the Render dashboard (not committed to git):
      any secrets from `.env` that shouldn't live in the repo.
- [ ] Confirmed free-tier limits are acceptable for this use case: 512MB RAM,
      spins down after 15 min idle, ~30–60s cold start on next visit.
- [ ] First deploy triggered, build logs reviewed for errors (composer install,
      npm build, migrate:fresh --seed all completing).

## E. Post-deploy verification

- [ ] Site loads at the Render URL.
- [ ] Demo login/seeded credentials work as expected.
- [ ] Key user flows walked through manually (whatever you'd want a recruiter/
      portfolio visitor to try).
- [ ] Triggered a deliberate error (bad route, invalid form input) — confirmed no
      debug stack trace or `.env` values leak in the response.
- [ ] Restarted the Render service manually, reloaded the site — confirmed it
      comes back up cleanly with fresh seeded data (validates the RUN_SCRIPTS
      deploy path, not just the first lucky build).
- [ ] Checked page weight/asset loading — confirmed compiled CSS/JS from the
      Vite build stage actually loaded (no missing `public/build` assets, which
      is the most common failure if Stage 1 was misconfigured).
- [ ] Tested on mobile viewport if the portfolio audience will view it on phones.

## F. Portfolio-readiness polish

- [ ] Added a small banner/note in the demo UI or README: "Demo environment —
      data resets periodically, not for real use" — sets correct expectations
      for anyone poking around.
- [ ] Documented demo login credentials somewhere visible (README or on-page)
      so visitors aren't stuck at a login wall.
- [ ] Added a note about the cold-start delay if idle, so a visitor doesn't
      think the link is broken during the first 30-60s.
- [ ] Linked the Render URL from the portfolio site, with a short one-line
      description of what URSAC Hub does.
- [ ] (Optional) Custom subdomain — Render free tier supports custom domains,
      so `ursachub-demo.yourdomain.com` is possible instead of the default
      `*.onrender.com` URL if you want it to look more polished.

## G. Known limitations to just accept for a free demo

- Free tier sleeps after 15 min idle → first visitor after a while waits for
  cold start. Not fixable without paying (~$7/mo Starter plan).
- SQLite resets on every container restart/redeploy → any data a visitor enters
  will not persist. This is by design for this setup, not a bug.
- No queue workers running in the background → anything using `dispatch()` for
  queued jobs will need `QUEUE_CONNECTION=sync` to actually execute inline,
  which is already set — just don't expect true async behavior in the demo.
