# Changelog

All Zana packages share one version number and are released together. A release that did
not change this project is not listed.

## 2.5.0 — unreleased

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
