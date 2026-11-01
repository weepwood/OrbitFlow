#!/usr/bin/env python3
"""Capture server-rendered, genuine Flux Blade pages for a READ-ONLY Pages preview.

GitHub Pages cannot run the Laravel/Livewire backend. These HTML snapshots
are intentionally non-interactive; production SaaS still requires PHP hosting.
"""
import argparse
import html
from http.cookiejar import CookieJar
import os
from pathlib import Path
import re
import shutil
import urllib.error
import urllib.parse
import urllib.request

ROUTES = {
    '/dashboard': 'index.html',
    '/projects': 'projects.html',
    '/team': 'team.html',
    '/billing': 'billing.html',
    '/settings': 'settings.html',
}


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--base', default='http://127.0.0.1:8000')
    parser.add_argument('--output', required=True)
    args = parser.parse_args()
    password = os.environ.get('ORBITFLOW_PREVIEW_PASSWORD', '')
    if len(password) < 20:
        raise RuntimeError('Missing ephemeral ORBITFLOW_PREVIEW_PASSWORD')

    output = Path(args.output).resolve()
    output.mkdir(parents=True, exist_ok=True)
    opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(CookieJar()))

    login = opener.open(args.base + '/login', timeout=30).read().decode('utf-8')
    csrf = re.search(r'<input\b[^>]*name=["\']_token["\'][^>]*value=["\']([^"\']+)', login)
    if not csrf:
        raise RuntimeError('Login form CSRF token not found')
    body = urllib.parse.urlencode({
        '_token': html.unescape(csrf.group(1)),
        'email': 'preview@orbitflow.test',
        'password': password,
    }).encode('utf-8')
    response = opener.open(urllib.request.Request(args.base + '/login', data=body, method='POST'), timeout=30)
    if '/dashboard' not in response.url:
        raise RuntimeError('Preview authentication failed')

    for route, filename in ROUTES.items():
        response = opener.open(args.base + route, timeout=30)
        raw = response.read().decode('utf-8')
        if '<html' not in raw.lower() or 'OrbitFlow' not in raw:
            raise RuntimeError(f'Unexpected Laravel response for {route}')
        if 'livewire' not in raw.lower() or 'flux' not in raw.lower():
            raise RuntimeError(f'Expected Livewire and Flux output for {route}')
        # Strip session/csrf/Livewire state and all backend-dependent scripts.
        raw = re.sub(r'<script\b[^>]*>.*?</script\s*>', '', raw, flags=re.I | re.S)
        raw = re.sub(r'<meta\b[^>]*name=["\']csrf-token["\'][^>]*>', '', raw, flags=re.I)
        raw = re.sub(r'<input\b[^>]*name=["\']_token["\'][^>]*>', '', raw, flags=re.I)
        raw = re.sub(r'\s+wire:(?:snapshot|effects|id|key|navigate|click|submit|model(?:\.[\w-]+)*)=(?:"[^"]*"|\'[^\']*\')', '', raw, flags=re.I)
        raw = re.sub(r'\s+(?:x-data|x-on:[\w.-]+)=(?:"[^"]*"|\'[^\']*\')', '', raw, flags=re.I)
        raw = raw.replace(args.base, '')
        # Vite produces /build/assets/...; move those assets with the exported HTML.
        raw = re.sub(r'(?P<q>["\'])/build/', r'\g<q>./build/', raw)
        # Disable dynamic routes (post form actions and unsupported detail pages).
        raw = re.sub(r'(<form\b[^>]*?)\saction=(?:"[^"]*"|\'[^\']*\')', r'\1', raw, flags=re.I)
        raw = re.sub(r'<form\b', '<form onsubmit="return false"', raw, flags=re.I)
        raw = re.sub(r'\s+href=(?:"[^"]*"|\'[^\']*\')', lambda m: replace_href(m.group(0)), raw, flags=re.I)
        # Prevent Flux controls from claiming live interactivity on Pages.
        banner = '''<div role="status" style="position:relative;z-index:99999;background:#18181b;color:#fff;padding:11px 20px;text-align:center;font:500 13px/1.6 system-ui,sans-serif">真实 Flux UI / Livewire 服务端渲染 · GitHub Pages 只读快照（无 PHP 后端） · <a style="color:#c4b5fd;text-decoration:underline" href="https://github.com/weepwood/OrbitFlow/tree/main/resources/views">查看 Flux Blade 源码</a></div>'''
        raw = re.sub(r'(<body\b[^>]*>)', r'\1' + banner, raw, count=1, flags=re.I)
        raw = raw.replace('</head>', '<style>body [wire\\:click],button[type="submit"]{cursor:not-allowed!important}body .flux-preview-note{font:12px system-ui}</style></head>')
        (output / filename).write_text(raw, encoding='utf-8')
        print(f'Exported authentic Flux UI server-rendered page: {route} -> {filename} ({len(raw)} bytes)')

    build = Path('public/build')
    if not (build / 'manifest.json').is_file():
        raise RuntimeError('Missing compiled Vite assets')
    shutil.copytree(build, output / 'build', dirs_exist_ok=True)
    (output / '.nojekyll').write_text('', encoding='utf-8')
    # A separate audit file makes it clear where the snapshots came from.
    (output / 'PREVIEW.txt').write_text(
        'Generated from real Laravel + Livewire + Flux UI Blade components by GitHub Actions.\n'
        'Read-only HTML snapshot. Dynamic features require a PHP/Livewire deployment.\n', encoding='utf-8')


def replace_href(attr):
    match = re.match(r'(\s+href=)(["\'])(.*)\2$', attr, flags=re.I | re.S)
    if not match:
        return attr
    href = match.group(3)
    path = urllib.parse.urlparse(href).path
    if href.startswith('http') and not href.startswith('http://127.0.0.1'):
        return attr
    if path in ROUTES:
        return f'{match.group(1)}{match.group(2)}./{ROUTES[path]}{match.group(2)}'
    if path.startswith('/projects/'):
        return f'{match.group(1)}{match.group(2)}./projects.html{match.group(2)}'
    if path in ('/', '/login', '/register'):
        return f'{match.group(1)}{match.group(2)}./index.html{match.group(2)}'
    return attr


if __name__ == '__main__':
    main()
