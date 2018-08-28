#!/usr/bin/env bash

echo "\n\n start_container \n\n"
echo "1) Clean up apache"
rm -rf /var/www/html

echo "2) Copy files to the right place"
cp /.htaccess /var/www/.htaccess

echo "3) Create wp-config"
wpconfig="/var/www/wp-config.php"
if [ -e "$wpconfig" ]; then
    rm /var/www/wp-config.php
fi

echo "<?php"                                           >>/var/www/wp-config.php
# Setup database
echo "define('DB_NAME', '"$DBNAME"');"                 >>/var/www/wp-config.php
echo "define('DB_USER', '"$DBUSER"');"                 >>/var/www/wp-config.php
echo "define('DB_PASSWORD', '"$DBPASS"');"             >>/var/www/wp-config.php
echo "define('DB_HOST', '"$DBHOST"');"                 >>/var/www/wp-config.php
echo "\$table_prefix  = '"$DBNAME"_';"                 >>/var/www/wp-config.php

echo ""                                                >>/var/www/wp-config.php

# Setup domain
echo "define('WP_SITEURL', 'http://"$DOMAIN"');"       >>/var/www/wp-config.php
echo "define('WP_HOME', 'http://"$DOMAIN"');"          >>/var/www/wp-config.php
echo "define('WP_DEFAULT_THEME', 'grahlie');"          >>/var/www/wp-config.php
echo "define('DB_CHARSET', 'utf8mb4');"                >>/var/www/wp-config.php
echo "define('DB_COLLATE', '');"                       >>/var/www/wp-config.php

echo ""                                                >>/var/www/wp-config.php

# Set salts
curl -s https://api.wordpress.org/secret-key/1.1/salt/ >>/var/www/wp-config.php

echo ""                                                >>/var/www/wp-config.php

# Other settings
echo "define('WP_DEBUG', '"$DEBUG"');"                 >>/var/www/wp-config.php
echo "define('WP_AUTO_UPDATE_CORE', false);"           >>/var/www/wp-config.php
echo "define('WP_MEMORY_LIMIT', '96M');"               >>/var/www/wp-config.php
echo "define( 'WP_POST_REVISIONS', 100 );"             >>/var/www/wp-config.php
echo "if ( !defined('ABSPATH') )"                      >>/var/www/wp-config.php
echo "  define('ABSPATH', dirname(__FILE__) . '/');"   >>/var/www/wp-config.php
echo "require_once(ABSPATH . 'wp-settings.php');"      >>/var/www/wp-config.php

echo "4) Import database"
# mysql --host="$DBHOST" --user="$DBUSER" --password="$DBPASS" "$DBNAME" < wordpress.sql