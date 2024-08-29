#!/bin/bash

# Ajusta permissões dos diretórios
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Executa o build do Yarn
echo "Executando yarn run build..."
cd /var/www || exit
yarn run build

# Adiciona os cron jobs
echo "Configurando cron jobs..."
(crontab -l 2>/dev/null; echo "* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1") | crontab -
(crontab -l 2>/dev/null; echo "* * * * * cd /var/www && php artisan queue:work --stop-when-empty >> /dev/null 2>&1") | crontab -

# Inicia o cron
echo "Iniciando o cron..."
service cron start

# Inicializa o Supervisor para gerenciar os serviços
echo "Iniciando o Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
