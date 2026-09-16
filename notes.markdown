# Configurar Timezone
config/app.php
'timezone' => 'America/Sao_Paulo',

# Comands
composer require filament/filament:"^3.3" -W

php artisan filament:install --panels

php artisan migrate

php artisan db:seed

### criar link simbólico
php artisan storage:link


### Criar um link simbólico (symlink)
cd ~/domains/nome-do-dominio/public_html
ln -s /home/u233139548/domains/cardapiovirtual.space/storage/app/public storage

ln -s //home/u359724568/domains/mcboutique.com.br/storage/app/public storage

### Enter In SHH ignore user erro of windows
ssh -F NUL -p [port] [sshuserip]

## Composer production
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

### Composer resolver erros
composer dump-autoload -o


php artisan key:generate


ASSET_URL=https://compliance.sequoia.srv.br


chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
chmod -R 775 storage database
