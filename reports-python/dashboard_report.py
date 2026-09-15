import requests
import csv
from pathlib import Path

BASE_URL = "http://127.0.0.1:8000/api"
EMAIL = "perf@campaignflow.test"
PASSWORD = "password"
WORKSPACE_ID = 1

def login():
    response = requests.post(
        f"{BASE_URL}/login",
        json={"email": EMAIL, "password": PASSWORD},
        timeout=30,
    )
    response.raise_for_status()
    data = response.json()
    token = data.get("token") or data.get("data", {}).get("token")
    if not token:
        raise RuntimeError(f"Token not found in response: {data}")
    return token

def fetch_summary(token: str):
    response = requests.get(
        f"{BASE_URL}/dashboard/summary",
        params={"workspace_id": WORKSPACE_ID},
        headers={"Authorization": f"Bearer {token}"},
        timeout=30,
    )
    response.raise_for_status()
    return response.json()

def save_csv(summary: dict, path: Path):
    rows = [
        ["metric", "value"],
        ["voters_total", summary["voters"]["total"]],
        ["supporters", summary["voters"]["by_status"].get("supporter", 0)],
        ["undecided", summary["voters"]["by_status"].get("undecided", 0)],
        ["demands_open", summary["demands"]["open"]],
        ["donations", summary["finance"]["total_donations"]],
        ["expenses", summary["finance"]["total_expenses"]],
        ["balance", summary["finance"]["balance"]],
    ]
    with path.open("w", newline="", encoding="utf-8") as f:
        writer = csv.writer(f)
        writer.writerows(rows)

def main():
    print("Logging in...")
    token = login()
    print("Fetching dashboard summary...")
    summary = fetch_summary(token)
    out = Path(__file__).parent / "dashboard_summary.csv"
    save_csv(summary, out)
    print(f"Saved: {out}")
    print(summary)

if __name__ == "__main__":
    main()
