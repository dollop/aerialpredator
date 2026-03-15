<div align="center">

# `AERIAL PREDATOR`
### UNIFIED OSINT PLATFORM

[![Status](https://img.shields.io/badge/status-operational-34c759?style=flat-square&logo=statuspage&logoColor=white)]()
[![Live](https://img.shields.io/badge/live-aerialpredator.com-ff3b30?style=flat-square)](https://aerialpredator.com)
[![Stack](https://img.shields.io/badge/stack-HTML%20%2F%20CSS%20%2F%20JS-ff9f0a?style=flat-square)]()
[![License](https://img.shields.io/badge/license-private-4a4a4a?style=flat-square)]()

*A unified OSINT platform engineered for the surveillance of the global theater. Fusing orbital assets, flight telemetry, and seismic signatures into a single unblinking interface.*

</div>

---

## Overview

Aerial Predator is a purpose-built open-source intelligence (OSINT) web platform that aggregates and presents real-time global surveillance data through a unified operator interface. Designed for analysts, researchers, and threat intelligence practitioners who need consolidated situational awareness without switching between disparate tools.

The platform runs as a static front-end with an nginx proxy and PHP relay layer, allowing secure embedding of live third-party intelligence feeds without exposing upstream sources directly.

---

## Platform Modules

| Module | Path | Description |
|---|---|---|
| **Home** | `index.html` | Mission overview, intelligence resources, live track counter, radar canvas |
| **Live Interface** | `live.html` | Real-time embedded platform feed with uplink status and direct fallback |
| **Maritime Traffic** | `maritime.html` | Global maritime vessel tracking and port activity |
| **Middle East Monitor** | `middle-east.html` | Regional OSINT feed — airspace, activity, and event tracking |
| **Platform Console** | `console.html` | Operator console view |

---

## Architecture

```
┌─────────────────────────────────────────┐
│           aerialpredator.com            │
│         (static HTML/CSS/JS)            │
└────────────────┬────────────────────────┘
                 │
        ┌────────▼────────┐
        │   nginx proxy   │  ← nginx-proxy.conf
        │  (embed relay)  │
        └────────┬────────┘
                 │
        ┌────────▼────────┐
        │   proxy.php     │  ← upstream feed relay
        │  (PHP backend)  │
        └────────┬────────┘
                 │
        ┌────────▼────────────────────────┐
        │    Upstream Intelligence Feeds  │
        │  Flight • Maritime • Seismic    │
        └─────────────────────────────────┘
```

---

## Stack

- **Frontend** — Vanilla HTML5, CSS3, JavaScript (no framework dependencies)
- **Fonts** — JetBrains Mono, Barlow Condensed (Google Fonts)
- **Backend** — PHP proxy relay, nginx reverse proxy
- **Deployment** — Static hosting with nginx config

---

## Files

```
aerial/
├── index.html          # Main landing — mission overview & resources
├── live.html           # Live feed interface
├── maritime.html       # Maritime traffic module
├── middle-east.html    # Middle East intelligence monitor
├── platform.html       # Platform overview
├── console.html        # Operator console
├── proxy.php           # PHP upstream relay
├── nginx-proxy.conf    # nginx proxy configuration
├── logo.png            # Brand logo
├── favicon.ico         # Favicon assets
├── favicon-16.png
├── favicon-32.png
├── favicon-180.png     # Apple touch icon
└── brand_guideline.docx
```

---

## Deployment

**Prerequisites:** nginx, PHP 8+

```bash
# Clone the repo
git clone https://github.com/dollop/aerialpredator.git
cd aerialpredator

# Copy nginx config to your sites-available
sudo cp nginx-proxy.conf /etc/nginx/sites-available/aerialpredator
sudo ln -s /etc/nginx/sites-available/aerialpredator /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx

# Serve static files from your web root
# Point your web root to this directory
```

---

## Live Site

[aerialpredator.com](https://aerialpredator.com)

---

<div align="center">
<sub>Built by <a href="https://github.com/dollop">dollop</a> · San Francisco</sub>
</div>
