# Changelog

All Zana packages share one version number and are released together. A release that did
not change this project is not listed.

## 2.13.0 — 2026-11-05

### Changed

- `AGENTS.md` for Zana 2.13.
- Requires the Zana packages at ^2.13.

## 2.12.0 — 2026-11-04

### Changed

- `AGENTS.md` for Zana 2.12.
- Requires the Zana packages at ^2.12.

## 2.11.0 — 2026-11-01

### Changed

- `AGENTS.md` for Zana 2.11, which names `make:plan`.
- Requires the Zana packages at ^2.11.

## 2.10.0 — 2026-11-01

### Changed

- `AGENTS.md` for Zana 2.10.
- Requires the Zana packages at ^2.10.

## 2.9.0 — 2026-11-01

### Changed

- The CI workflow runs `check` after the tests.
- `AGENTS.md` for Zana 2.9.
- Requires the Zana packages at ^2.9.

## 2.8.0 — 2026-11-01

### Added

- `composer test` also runs the tests in `modules/*/Tests`, where the maker writes them.
- `config/testing/security.php`: a secret for the `testing` environment, so that tests, generated
  module tests included, run without `.env` (in CI, for example).

### Changed

- `AGENTS.md` for Zana 2.8, which names the use-case generators (FE-47, FE-49).
- Requires the Zana packages at ^2.8.

## 2.7.0 — 2026-10-31

### Added

- `AGENTS.md`, the rules and workflow for AI coding agents, written by `make:agents` for this Zana
  version, with `CLAUDE.md` and `.github/copilot-instructions.md` pointing to it, and
  `tests/AgentsFileTest.php`, which fails when `AGENTS.md` names another Zana version (FE-44).
- `mj-zana/maker` and `mj-zana/testing` in `require-dev`; the `make:` commands are registered when
  the maker is installed, and `check` applies the architecture rules to `modules/`.

### Changed

- `bin/console` uses `ConsoleKernel::runBuilder()`, so `php bin/console check` reports a container
  that does not build.
- Requires the Zana packages at ^2.7.

## 2.6.0 — 2026-10-31

### Changed

- Installs `mj-zana/logging` and writes warnings and errors to `var/log/app.log`, rotated at 10 MB with
  5 old files kept (`config/logging.php`, FE-43).
- Requires the Zana packages at ^2.6.
## 2.5.0 — 2026-10-27

### Changed

- `release:build` comes from `mj-zana/console` (ADR-021), which fixes it on Windows (FE-41). The
  skeleton's own `src/Release/` and `ReleaseBuildCommand` are removed. Projects created from 2.3 or
  2.4 should delete them and their registration in `Bootstrap`.
- Requires `mj-zana/console` ^2.5.

## 2.4.0 — 2026-10-26

### Changed

- Uses the configuration cache in production (`var/cache/config.php`).

## 2.3.0 — 2026-10-26

First release (FE-17). A website in two languages with page metadata, versioned assets, a language
switcher, security headers and translated error pages; `.htaccess` files for hosting with a fixed
document root; a front controller for the split layout of shared hosting; and `release:build`, which
writes the upload archives from a commit.
