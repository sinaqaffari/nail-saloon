#!/usr/bin/env python3
"""Checks a *fresh, unedited* Nue installation's Persian frontend and local assets.

NUE_HOST=example.test NUE_PROTO=https python3 tests/persian-smoke.py http://127.0.0.1:9402
The starter post is intentionally expected; this is not a test for an edited live site.
"""
import os
import sys
from urllib.parse import urlparse
from urllib.request import Request, urlopen

base = (sys.argv[1] if len(sys.argv) > 1 else 'http://127.0.0.1:9402').rstrip('/')
headers = {'Host': os.getenv('NUE_HOST', urlparse(base).netloc), 'X-Forwarded-Proto': os.getenv('NUE_PROTO', urlparse(base).scheme)}
def fetch(path):
    with urlopen(Request(base + path, headers=headers), timeout=30) as response:
        return response.read()

home = fetch('/').decode('utf-8')
blog = fetch('/blog/').decode('utf-8')
services = fetch('/services/').decode('utf-8')
sample = fetch('/sample-page/').decode('utf-8')
checks = {
    'Persian RTL document': 'lang="fa-IR" dir="rtl"' in home,
    'Persian home content': 'استودیو ناخن / زیبایی در جزئیات' in home,
    'Editorial stylesheet': 'assets/css/lune.css?ver=1.4.0' in home,
    'Persian service cards': 'مانیکور کلاسیک' in services,
    'Persian WordPress starter post': 'به مجله نیو استودیو خوش آمدید' in blog and 'Hello world!' not in blog,
    'Persian WordPress sample page': 'صفحه نمونه' in sample and 'This is an example page.' not in sample,
    'Persian default category': 'دسته‌بندی نشده' in blog and '>Uncategorized<' not in blog,
    'Menu accessibility fallback': 'aria-label="باز کردن فهرست"' in home,
    'Editorial CSS asset': bool(fetch('/wp-content/themes/nue-studio/assets/css/lune.css')),
    'Service rows': 'lune-service-row' in home,
    'Four-step process': 'lune-steps' in home,
    'Pricing cards': 'lune-price-card' in home,
    'Six homepage questions': home.count('<details class="wp-block-details') == 6,
    'Booking banner': 'رزرو آنلاین' in home,
    'Local Persian display font': bool(fetch('/wp-content/themes/nue-studio/assets/fonts/noto-naskh-arabic-arabic-wght-normal.woff2')),
}
failed = [name for name, ok in checks.items() if not ok]
if failed:
    sys.exit('Failed: ' + ', '.join(failed))
print(f'Passed {len(checks)} Persian UI and asset checks.')
