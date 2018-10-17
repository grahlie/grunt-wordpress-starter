#!/usr/bin/env bash

# echo "1) Create htaccess"
# if [ "$MULTISITE" -gt 0 ]; then
#     echo "# BEGIN WordPress"                                                            >>/var/www/.htaccess
#     echo "<IfModule mod_rewrite.c>"                                                     >>/var/www/.htaccess
#     echo "RewriteEngine On"                                                             >>/var/www/.htaccess
#     echo "RewriteBase /"                                                                >>/var/www/.htaccess
#     echo "RewriteRule ^server-status - [L]"                                             >>/var/www/.htaccess
#     echo "RewriteRule ^index\.php$ - [L]"                                               >>/var/www/.htaccess
#     echo "# add a trailing slash to /wp-admin"                                          >>/var/www/.htaccess
#     echo "RewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]"               >>/var/www/.htaccess
#     echo "RewriteCond %{REQUEST_FILENAME} -f [OR]"                                      >>/var/www/.htaccess
#     echo "RewriteCond %{REQUEST_FILENAME} -d"                                           >>/var/www/.htaccess
#     echo "RewriteRule ^ - [L]"                                                          >>/var/www/.htaccess
#     echo "RewriteRule ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) $2 [L]"        >>/var/www/.htaccess
#     echo "RewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]"                             >>/var/www/.htaccess
#     echo "RewriteRule . index.php [L]"                                                  >>/var/www/.htaccess
#     echo "</IfModule>"                                                                  >>/var/www/.htaccess
#     echo "# END WordPress"                                                              >>/var/www/.htaccess
# else
#     echo "# BEGIN WordPress"                                                            >>/var/www/.htaccess
#     echo "<IfModule mod_rewrite.c>"                                                     >>/var/www/.htaccess
#     echo "RewriteEngine On"                                                             >>/var/www/.htaccess
#     echo "RewriteBase /"                                                                >>/var/www/.htaccess
#     echo "RewriteRule ^server-status - [L]"                                             >>/var/www/.htaccess
#     echo "RewriteRule ^index\.php$ - [L]"                                               >>/var/www/.htaccess
#     echo "RewriteCond %{REQUEST_FILENAME} !-f"                                          >>/var/www/.htaccess
#     echo "RewriteCond %{REQUEST_FILENAME} !-d"                                          >>/var/www/.htaccess
#     echo "RewriteRule . /index.php [L]"                                                 >>/var/www/.htaccess
#     echo "</IfModule>"                                                                  >>/var/www/.htaccess
#     echo "# END WordPress"                                                              >>/var/www/.htaccess
# fi

echo "2) Create wp-config"
wpconfig="/var/www/wp-config.php"
if [ -e "$wpconfig" ]; then
    rm /var/www/wp-config.php
fi

echo "<?php"                                           >>/var/www/wp-config.php
# Setup multisite
if [ "$MULTISITE" -gt 0 ]; then
    echo "define('WP_ALLOW_MULTISITE', true);"         >>/var/www/wp-config.php
    echo "define('MULTISITE', true);"                  >>/var/www/wp-config.php
    echo "define('SUBDOMAIN_INSTALL', false);"         >>/var/www/wp-config.php
    echo "define('PATH_CURRENT_SITE', '/');"           >>/var/www/wp-config.php
    echo "define('SITE_ID_CURRENT_SITE', 1);"          >>/var/www/wp-config.php
    echo "define('BLOG_ID_CURRENT_SITE', 1);"          >>/var/www/wp-config.php
fi

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
echo "define('FS_METHOD', 'direct');"                  >>/var/www/wp-config.php

echo ""                                                >>/var/www/wp-config.php

# Set salts
curl -s https://api.wordpress.org/secret-key/1.1/salt/ >>/var/www/wp-config.php

echo ""                                                >>/var/www/wp-config.php

# Debug mode
if [ "$DEBUG" -gt 0 ]; then
    echo "define('WP_DEBUG', true);"                  >>/var/www/wp-config.php
else
    echo "define('WP_DEBUG', false);"                 >>/var/www/wp-config.php
fi

# Other settings
echo "define('WP_AUTO_UPDATE_CORE', false);"           >>/var/www/wp-config.php
echo "define('WP_MEMORY_LIMIT', '96M');"               >>/var/www/wp-config.php
echo "define( 'WP_POST_REVISIONS', 100 );"             >>/var/www/wp-config.php
echo "if ( !defined('ABSPATH') )"                      >>/var/www/wp-config.php
echo "  define('ABSPATH', dirname(__FILE__) . '/');"   >>/var/www/wp-config.php
echo "require_once(ABSPATH . 'wp-settings.php');"      >>/var/www/wp-config.php

# echo "3) Setup Wordpress folders"
# mkdir -p /var/www/wp-content/uploads
# chown -R www-data:www-data /var/www/wp-content

# echo "4) Install sendmail"
# export HOSTNAME="$DOMAIN"
# yes | sendmail