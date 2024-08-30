#!/bin/bash

# Ajusta permissões
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Inicia o Supervisor
exec supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
