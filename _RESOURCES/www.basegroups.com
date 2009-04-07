<virtualhost *:80>
	ServerName teachmyclients.com
	ServerAlias teachmyclients.com
	DocumentRoot /var/www/
	<directory "/var/www/">
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</directory>
</virtualhost>

<virtualhost *:80>
	ServerName default
	ServerAlias *
	DocumentRoot /home/jade/www/

	<directory /home/jade/www/>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</directory>
</virtualhost>
