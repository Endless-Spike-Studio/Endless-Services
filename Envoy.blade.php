@servers(['web' => 'localhost'])

@task('deploy')
cd /app
git fetch --all
git reset --hard origin/main
git pull
composer update --no-dev
php artisan optimize:clear
php artisan migrate
pnpm update
pnpm run build
php artisan static:upload
php artisan optimize
php artisan octane:reload
@endtask
