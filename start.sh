#!/bin/sh
# Use $PORT if set, otherwise 8000
PORT=${PORT:-8000}

echo "Starting PHP server on 0.0.0.0:$PORT"
php -S 0.0.0.0:$PORT -t public
