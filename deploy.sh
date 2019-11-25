eval "$(ssh-agent -s)"
ssh-add ~/.ssh/visilog_deployment_key
git pull -f
composer install
# npm install
# npm run production
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
echo "Deployment has been finished!"

