#!/bin/bash
# Basic test to ensure required files include db connection
missing=0
for f in daily_get_*.php; do
    if ! grep -q "require 'db.php';" "$f"; then
        echo "Missing db.php in $f"
        missing=1
    fi
done
exit $missing
