#!/usr/bin/env python3
"""HTTP smoke test for an installed Nue Studio site.

Usage: python3 tests/wordpress-smoke.py https://example.test
For a local proxy: NUE_HOST=example.test NUE_PROTO=https python3 tests/wordpress-smoke.py http://127.0.0.1:9400
"""
import os
import sys
from html.parser import HTMLParser
from urllib.error import HTTPError
from urllib.parse import urljoin, urlparse
from urllib.request import Request, build_opener, HTTPRedirectHandler

base = sys.argv[1].rstrip('/') if len(sys.argv) > 1 else 'http://127.0.0.1:9400'
host = os.getenv('NUE_HOST', urlparse(base).netloc)
headers = {'Host': host, 'X-Forwarded-Proto': os.getenv('NUE_PROTO', urlparse(base).scheme)}
paths = ['/', '/services/', '/services/classic-manicure/', '/nail-art/', '/nail-art-project/milky-white-nails/', '/about/', '/team/', '/booking/', '/pricing/', '/gift-cards/', '/blog/', '/blog/hello-world/', '/contact/', '/faq/', '/testimonials/', '/privacy-policy/', '/terms-and-conditions/', '/cancellation-policy/', '/refund-policy/']

class Links(HTMLParser):
    def __init__(self):
        super().__init__()
        self.hrefs = []
    def handle_starttag(self, tag, attrs):
        if tag == 'a':
            self.hrefs += [v for k, v in attrs if k == 'href' and v]

class LocalRedirect(HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, response_headers, newurl):
        target = urlparse(newurl)
        if target.netloc == host:
            newurl = base + target.path + (('?' + target.query) if target.query else '')
        return super().redirect_request(req, fp, code, msg, response_headers, newurl)

opener = build_opener(LocalRedirect())

def fetch(path):
    try:
        with opener.open(Request(base + path, headers=headers), timeout=30) as res:
            return res.status, res.read().decode('utf-8', 'replace')
    except HTTPError as error:
        return error.code, error.read().decode('utf-8', 'replace')

failed = []
links = set()
for path in paths:
    code, content = fetch(path)
    if code != 200 or any(error in content for error in ('Fatal error:', 'There has been a critical error on this website')):
        failed.append(f'{path}: HTTP {code} or PHP error')
    parser = Links()
    parser.feed(content)
    for href in parser.hrefs:
        u = urlparse(urljoin('https://' + host + path, href))
        if u.netloc == host and u.path and not u.path.startswith(('/wp-admin/', '/wp-json/', '/wp-content/', '/wp-includes/')):
            links.add(u.path)
for path in ('/nue-missing-page-smoke-test/', '/services/nue-missing-service-smoke-test/'):
    code, _ = fetch(path)
    if code != 404:
        failed.append(f'404 template at {path}: HTTP {code}')
for path in sorted(links - set(paths)):
    code, _ = fetch(path)
    if code >= 400:
        failed.append(f'linked URL {path}: HTTP {code}')
if failed:
    print('\n'.join(failed))
    sys.exit(1)
print(f'Passed {len(paths)} page routes, two 404 routes, and {len(links)} internal linked paths.')
