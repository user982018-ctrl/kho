kho.phanbonmiennam.net

sudo mkdir -p /var/www/phanbonmiennam.net
sudo chown -R $USER:$USER /var/www/phanbonmiennam.net

sudo nano /etc/apache2/sites-available/phanbonmiennam.net.conf
<VirtualHost *:80>
    ServerName phanbonmiennam.net
    ServerAlias www.phanbonmiennam.net
    DocumentRoot /var/www/phanbonmiennam.net

    <Directory /var/www/phanbonmiennam.net>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/example.com-error.log
    CustomLog ${APACHE_LOG_DIR}/example.com-access.log combined
</VirtualHost>

sudo a2ensite phanbonmiennam.net
sudo systemctl reload apache2

sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d phanbonmiennam.net -d www.phanbonmiennam.net

sudo certbot --apache -d kho.phanbonmiennam.net -d www.kho.phanbonmiennam.net

sudo certbot certonly --standalone -d kho.phanbonmiennam.net -d www.kho.phanbonmiennam.`

mv /var/www/kho.phanbonmiennam.net/kho/* /var/www/kho.phanbonmiennam.net
mv /var/www/kho.phanbonmiennam.net/kho/.* /var/www/kho.phanbonmiennam.net/ 2>/dev/null

sudo chown -R www-data:www-data /var/www/kho.phanbonmiennam.net
sudo chmod -R 755 /var/www/kho.phanbonmiennam.net

sudo nano /etc/apache2/sites-available/kho.phanbonmiennam.net.conf

sudo mkdir -p /var/www/kho.phanbonmiennam.net
sudo chown -R www-data:www-data /var/www/kho.phanbonmiennam.net
sudo a2ensite kho.phanbonmiennam.net.conf
sudo systemctl reload apache2
sudo certbot --apache -d kho.phanbonmiennam.net -d www.kho.phanbonmiennam.net
