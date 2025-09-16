#!/usr/bin/env python3
import requests
from bs4 import BeautifulSoup
import json
import time

BASE_URL = "https://mmajunkie-eu.usatoday.com/"

HEADERS = {
    "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36"
}

def fetch(url, retries=3, backoff=1.0):
    for i in range(retries):
        try:
            r = requests.get(url, headers=HEADERS, timeout=15)
            r.raise_for_status()
            return r.text
        except Exception:
            if i == retries - 1:
                raise
            time.sleep(backoff * (2 ** i))
    return None

def parse_mmajunkie(html):
    soup = BeautifulSoup(html, "html.parser")
    results = []

    # Atrodam jaunumu saites
    for card in soup.select("a.card-title"):
        title = card.get_text(strip=True)
        url = card['href']
        results.append({
            "title": title,
            "url": url
        })

    return results

def main():
    html = fetch(BASE_URL)
    if html:
        items = parse_mmajunkie(html)
        print(json.dumps({
            "fetched_at": time.strftime("%Y-%m-%dT%H:%M:%SZ", time.gmtime()),
            "count": len(items),
            "items": items
        }, indent=2, ensure_ascii=False))
    else:
        print("Failed to retrieve the page.")

if __name__ == "__main__":
    main()

