#!/usr/bin/env bash

set -e

env=${APP_ENV:-production}

if [ "$env" != "local" ]; then
    echo "Caching configuration"
    php /application/artisan config:cache
    php /application/artisan route:cache
fi