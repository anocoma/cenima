#!/bin/sh

# Chờ MySQL sẵn sàng
#echo "Waiting for MySQL..."
#until mysqladmin ping -h db -u andk -p123 --silent; do
    #echo "MySQL not ready, waiting 2 seconds..."
    #sleep 2
#done
#echo "MySQL is ready!"

echo "Waiting for MariaDB..."
timeout 60 sh -c "until mariadb-admin ping -h db -u andk -p123 --ssl=0 --silent; do echo 'MariaDB not ready, waiting 2 seconds...'; sleep 2; done" || { echo "MariaDB timeout after 60s"; mariadb-admin ping -h db -u andk -p123 --ssl=0; exit 1; }
echo "MariaDB is ready!"

# echo "Setting permissions for storage..."
# [ -d /var/www/storage ] || mkdir -p /var/www/storage/{logs,framework/sessions,framework/cache,framework/views}
# chmod -R ug+w /var/www/storage
# chown -R www-data:www-data /var/www/storage

# Set quyền cho storage
#echo "Setting permissions for storage..."
#mkdir -p /var/www/storage/logs \
    #/var/www/storage/framework/sessions \
    #/var/www/storage/framework/cache \
    #/var/www/storage/framework/views
#chmod -R ug+w /var/www/storage
#chown -R www-data:www-data /var/www/storage
#echo "Permissions set."

# File đánh dấu nằm trong storage để persistent
# INITIALIZED_FILE="/var/www/.initialized"

# Chỉ chạy các lệnh khởi tạo 1 lần
# if [ ! -f "$INITIALIZED_FILE" ]; then
#     echo "Running initial Laravel commands..."
#     php artisan migrate --force || { echo "Migration failed"; exit 1; }
#     php artisan db:seed --force || { echo "Seeding failed"; exit 1; }
#     php artisan storage:link 
#     php artisan key:generate
#     touch "$INITIALIZED_FILE"
#     echo "Initial setup completed."
# else
#     echo "Initial setup already done, skipping..."
# fi

# clear cache
php artisan optimize:clear 

# Tạo key 
php artisan key:generate 
echo "Application key generated."

# Tạo storage link 
echo "Creating storage symbolic link..."
php artisan storage:link 
echo "Storage link created."


# Các lệnh cache chạy lại mỗi lần
echo "Running cache commands..."
php artisan config:cache 
php artisan route:cache 
php artisan view:cache 
echo "Cache commands completed."

# Khởi động Supervisor
echo "Starting Supervisor..."
supervisord -c /etc/supervisor/conf.d/supervisord.conf || { echo "Supervisor failed"; exit 1; }
echo "Supervisor started."

