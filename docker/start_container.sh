#!/usr/bin/env bash

# Copy files to the right place
cp /.htaccess /var/www/html/.htaccess
wpconfig="/var/www/html/wp-config.php"

if [ -e "$wpconfig" ]; then
    rm /var/www/html/wp-config.php
fi

echo "<?php"                               >>/var/www/html/wp-config.php
# Setup database
echo "define('DB_NAME', '"$DBNAME"');"     >>/var/www/html/wp-config.php
echo "define('DB_USER', '"$DBUSER"');"     >>/var/www/html/wp-config.php
echo "define('DB_PASSWORD', '"$DBPASS"');" >>/var/www/html/wp-config.php
echo "define('DB_HOST', '"$DBHOST"');"     >>/var/www/html/wp-config.php
echo "\$table_prefix  = '"$DBNAME"';"      >>/var/www/html/wp-config.php

# Setup domain
echo "define('WP_SITEURL', 'http://"$DOMAIN");"   >>/var/www/html/wp-config.php
echo "define('WP_HOME', 'http://"$DOMAIN");"      >>/var/www/html/wp-config.php
echo "define('WP_DEFAULT_THEME', 'grahlie');"     >>/var/www/html/wp-config.php
echo "define('DB_CHARSET', 'utf8');"              >>/var/www/html/wp-config.php
echo "define('DB_COLLATE', '');"                  >>/var/www/html/wp-config.php

# Set salts
curl -s https://api.wordpress.org/secret-key/1.1/salt/ >>/var/www/html/wp-config.php

# Other settings
echo "define('WP_DEBUG', false);"                    >>/var/www/html/wp-config.php
echo "define('WP_AUTO_UPDATE_CORE', false);"         >>/var/www/html/wp-config.php
echo "define('WP_MEMORY_LIMIT', '96M');"             >>/var/www/html/wp-config.php
echo "define( 'WP_POST_REVISIONS', 100 );"           >>/var/www/html/wp-config.php
echo "if ( !defined('ABSPATH') )"                    >>/var/www/html/wp-config.php
echo "  define('ABSPATH', dirname(__FILE__) . '/');" >>/var/www/html/wp-config.php
echo "require_once(ABSPATH . 'wp-settings.php');"    >>/var/www/html/wp-config.php