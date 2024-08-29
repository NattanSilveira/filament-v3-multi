#!/bin/bash

# Ajusta permissões dos diretórios
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Executa o build do Yarn
echo "Executando yarn run build..."
cd /var/www || exit
#yarn run build

# Coloque aqui sua lógica para esperar por serviços externos, se necessário

# Executa migrações do Laravel
#php /var/www/artisan migrate --force

# Inicializa o Supervisor para gerenciar os serviços
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
