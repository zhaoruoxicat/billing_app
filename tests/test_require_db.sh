#!/bin/bash
# Basic test to ensure required files include db connection
missing=0
for f in daily_get_channels.php daily_get_methods.php daily_get_platforms.php; do
    if ! grep -q "require 'db.php';" "$f"; then
        echo "Missing db.php in $f"
        missing=1
    fi
done
exit $missing
