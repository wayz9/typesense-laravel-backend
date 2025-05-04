import requests
import json
import os
from typing import List, Dict

API_KEY = os.getenv("TMDB_BEARER") 
BASE_URL = "https://api.themoviedb.org/3/tv/popular"
OUTPUT_FILE = "tmdb_tv.json"
TOTAL_PAGES = 500

def fetch_page(page: int) -> List[Dict]:
    """Fetch a single page of results from TMDB API."""
    headers = {
        "Authorization": f"Bearer {API_KEY}",
        "accept": "application/json"
    }
    params = {
        "language": "en-US",
        "page": page
    }

    try:
        response = requests.get(BASE_URL, headers=headers, params=params)
        response.raise_for_status()  # Raise exception for bad status codes
        data = response.json()
        return data.get("results", [])
    except requests.RequestException as e:
        print(f"Error fetching page {page}: {e}")
        return []

def save_to_json(movies: List[Dict]) -> None:
    """Append movies to the output JSON file."""
    # If file exists, read existing data
    existing_data = []
    if os.path.exists(OUTPUT_FILE):
        try:
            with open(OUTPUT_FILE, 'r') as f:
                existing_data = json.load(f)
        except json.JSONDecodeError:
            print("Warning: Existing file is empty or corrupted. Starting fresh.")
    
    # Append new movies
    existing_data.extend(movies)
    
    # Write back to file
    with open(OUTPUT_FILE, 'w') as f:
        json.dump(existing_data, f, indent=2)
    print(f"Saved {len(movies)} movies to {OUTPUT_FILE}")

def main():
    """Main function to fetch movies from all pages."""
    if not API_KEY:
        print("Error: TMDB_BEARER environment variable not set.")
        return
    
    for page in range(1, TOTAL_PAGES + 1):
        print(f"Fetching page {page}/{TOTAL_PAGES}")
        movies = fetch_page(page)
        if movies:
            save_to_json(movies)
        else:
            print(f"No results for page {page}")

if __name__ == "__main__":
    main()
