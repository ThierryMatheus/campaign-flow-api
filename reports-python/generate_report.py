import argparse
import csv
import json
from pathlib import Path


def dashboard_to_rows(data: dict) -> list[list]:
    voters = data.get("voters", {})
    by_status = voters.get("by_status", {})
    demands = data.get("demands", {})
    finance = data.get("finance", {})
    return [
        ["metric", "value"],
        ["voters_total", voters.get("total", 0)],
        ["supporters", by_status.get("supporter", 0)],
        ["undecided", by_status.get("undecided", 0)],
        ["demands_open", demands.get("open", 0)],
        ["donations", finance.get("total_donations", 0)],
        ["expenses", finance.get("total_expenses", 0)],
        ["balance", finance.get("balance", 0)],
    ]


def generic_table_to_rows(data: dict) -> list[list]:
    """Espera { \"headers\": [...], \"rows\": [[...], ...] }"""
    headers = data.get("headers", [])
    rows = data.get("rows", [])
    return [headers, *rows]


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--type", required=True, choices=["dashboard", "voters", "transactions"])
    parser.add_argument("--input", required=True, help="Path to JSON data file")
    parser.add_argument("--output", required=True, help="Path to output CSV")
    args = parser.parse_args()

    payload = json.loads(Path(args.input).read_text(encoding="utf-8"))

    if args.type == "dashboard":
        lines = dashboard_to_rows(payload)

        import matplotlib
        matplotlib.use("Agg")
        import matplotlib.pyplot as plt

        labels = ["Supporters", "Undecided", "Opponent", "Unknown"]
        by = payload.get("voters", {}).get("by_status", {})
        values = [
            by.get("supporter", 0),
            by.get("undecided", 0),
            by.get("opponent", 0),
            by.get("unknown", 0),
        ]

        fig, ax = plt.subplots()
        ax.bar(labels, values)
        ax.set_title("Voters by status")
        ax.set_ylabel("Count")

        out = Path(args.output)
        out.parent.mkdir(parents=True, exist_ok=True)

        png_path = out.with_suffix(".png")
        fig.savefig(png_path, bbox_inches="tight")
        plt.close(fig)
        print("OK chart", png_path)
    else:
        lines = generic_table_to_rows(payload)
    out = Path(args.output)
    out.parent.mkdir(parents=True, exist_ok=True)

    with out.open("w", newline="", encoding="utf-8") as f:
        writer = csv.writer(f)
        writer.writerows(lines)

    print(f"OK {out}")


if __name__ == "__main__":
    main()
