# Security Policy

## Supported Versions

| Version | Supported |
|---|---|
| 1.x (current) | ✅ |
| < 1.0 | ❌ |

---

## Reporting a Vulnerability

Aerial Predator is an open-source OSINT intelligence platform. We take security seriously — particularly around proxy configuration, upstream feed handling, and any exposure of sensitive routing logic.

**Do not open a public GitHub issue for security vulnerabilities.**

### How to Report

Send a detailed report to: **security@aerialpredator.com**

Include the following where possible:
- Description of the vulnerability and its potential impact
- Steps to reproduce or proof-of-concept
- Affected file(s) or component(s)
- Suggested remediation if known

### What to Expect

| Timeframe | Action |
|---|---|
| Within 48 hours | Acknowledgement of your report |
| Within 7 days | Initial assessment and severity classification |
| Within 30 days | Patch or mitigation deployed (critical/high findings) |
| Post-remediation | Credit in release notes (if desired) |

---

## Scope

### In Scope

- `proxy.php` — input validation, SSRF potential, upstream request handling
- `nginx-proxy.conf` — header injection, misconfiguration, proxy bypass
- Cross-site scripting (XSS) in any HTML interface
- Information disclosure via error messages or response headers
- Hardcoded credentials or API keys

### Out of Scope

- Upstream third-party intelligence feed availability or accuracy
- Vulnerabilities in embedded third-party iframes or external platforms
- Social engineering attacks
- Rate limiting on public-facing read-only pages
- Issues in browsers older than 2 major versions

---

## Security Architecture Notes

This platform uses a PHP relay (`proxy.php`) and nginx reverse proxy (`nginx-proxy.conf`) to embed third-party intelligence feeds without exposing upstream source URLs directly. Researchers are encouraged to review these components in particular.

---

## Disclosure Policy

We follow a **coordinated disclosure** model. We ask that you give us reasonable time to patch before public disclosure. We will credit researchers who report valid findings unless they prefer to remain anonymous.

---

*This security policy is maintained by the Aerial Predator project owner. Last reviewed: March 2026.*
