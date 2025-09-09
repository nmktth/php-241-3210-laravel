#!/bin/bash
echo "Содержимое /app:"
ls -la /app

if [ ! -f "/app/artisan" ]; then
    echo "Error: /app/artisan не найден!"
    exit 1
fi

chmod +x /app/artisan
exec php /app/artisan serve --host=0.0.0.0 --port=80