#!/usr/bin/env python3
"""Run the target-local Alatyr adapter checker wrapper.

Coverage: manifest, context-router, placeholder, local path, and stale
checker-claim checks are delegated to the AlatyrCore source validator.

Set ALATYR_CORE_SOURCE to a local AlatyrCore checkout that contains
tools/validate_target_adapter.py. Extra command-line arguments are passed
through to the validator.
"""

from __future__ import annotations

import os
import subprocess
import sys
from pathlib import Path


def main() -> int:
    source = os.environ.get("ALATYR_CORE_SOURCE")
    if not source:
        print(
            "ALATYR_CORE_SOURCE must point to an AlatyrCore checkout.",
            file=sys.stderr,
        )
        return 2

    validator = Path(source) / "tools" / "validate_target_adapter.py"
    if not validator.is_file():
        print(
            f"Alatyr validator not found: {validator}",
            file=sys.stderr,
        )
        return 2

    repo = Path(__file__).resolve().parents[1]
    command = [sys.executable, str(validator), "--target", str(repo), *sys.argv[1:]]
    return subprocess.call(command)


if __name__ == "__main__":
    raise SystemExit(main())
