#!/bin/bash
echo ""
echo "==============="
echo "Running build_web.sh in $1"
echo "==============="
echo ""

echo "3.1) Install JSON parser"
UNAME=`uname`
which -s jq >/dev/null
if [[ $? == 1 ]]; then 
    if [[ "$UNAME" == 'Linux' ]]; then
        # TODO: this doesn't work
        sudo apt-get --assume-yes install jq
    elif [[ "$UNAME" == 'Darwin' ]]; then
        brew install jq
    fi
fi

echo "==============="
echo ""

echo "3.2) Set some variables"
if [[ $inputVariable == 'production' ]]; then
    FILE="../config.prod.json"
    if [[ ! -d $FILE ]]; then
        echo "config.prod.json Doesn't exists you need to create this first!"
        echo "exiting script here"
        exit;
    fi
else
    FILE="../config.json"
fi

NAME=$(jq .name $FILE)
FOLDER=$(jq .grunt.deploy $FILE)
PORT=$(jq .docker.port $FILE)
DOMAIN=$(jq .docker.domain $FILE)
DBNAME=$(jq .docker.dbname $FILE)
DBUSER=$(jq .docker.dbuser $FILE)
DBPASS=$(jq .docker.dbpass $FILE)
# DBVOLUME=$(jq .docker.dbvolume $FILE)
DEBUG=$(jq .docker.debug $FILE)
MACHINE=$(jq .docker.machine $FILE)
MULTISITE=$(jq .docker.multisite $FILE)
VERSION=$(jq .docker.version $FILE)

echo "==============="
echo ""

echo "3.3) Strip specialchars from JSON value"
# TODO: Find a solution to loop this
NAME="${NAME%\"}"
NAME="${NAME#\"}"
FOLDER="${FOLDER%\"}"
FOLDER="${FOLDER#\"}"
PORT="${PORT%\"}"
PORT="${PORT#\"}"
DOMAIN="${DOMAIN%\"}"
DOMAIN="${DOMAIN#\"}"
DBNAME="${DBNAME%\"}"
DBNAME="${DBNAME#\"}"
DBUSER="${DBUSER%\"}"
DBUSER="${DBUSER#\"}"
DBPASS="${DBPASS%\"}"
DBPASS="${DBPASS#\"}"
# DBVOLUME="${DBVOLUME%\"}"
# DBVOLUME="${DBVOLUME#\"}"
DEBUG="${DEBUG%\"}"
DEBUG="${DEBUG#\"}"
MACHINE="${MACHINE%\"}"
MACHINE="${MACHINE#\"}"
MULTISITE="${MULTISITE%\"}"
MULTISITE="${MULTISITE#\"}"
VERSION="${VERSION%\"}"
VERSION="${VERSION#\"}"

echo "==============="
echo ""

echo "3.4) Create compose file"
dockercompose="./docker-compose.yml"
if [[ ! -e $dockercompose ]]; then
    echo "version: '2'"                                                     >> ./docker-compose.yml
    echo "services:"                                                        >> ./docker-compose.yml
    echo "  "$NAME"-db:"                                                    >> ./docker-compose.yml
    echo "    image: mariadb"                                               >> ./docker-compose.yml
    echo "    restart: always"                                              >> ./docker-compose.yml
    echo "    environment:"                                                 >> ./docker-compose.yml
    echo "      MYSQL_ROOT_PASSWORD: wordpress"                             >> ./docker-compose.yml
    echo "      MYSQL_DATABASE:" $DBNAME                                    >> ./docker-compose.yml
    echo "      MYSQL_USER:" $DBUSER                                        >> ./docker-compose.yml
    echo "      MYSQL_PASSWORD:" $DBPASS                                    >> ./docker-compose.yml
    echo "    volumes:"                                                     >> ./docker-compose.yml
    echo "      - ./database.sql:/docker-entrypoint-initdb.d/database.sql"  >> ./docker-compose.yml
    echo "    container_name: " $NAME"-db"                                  >> ./docker-compose.yml
    echo "  "$NAME":"                                                       >> ./docker-compose.yml
    echo "    image: wordpress:"$VERSION                                    >> ./docker-compose.yml
    echo "    volumes:"                                                     >> ./docker-compose.yml
    echo "      - "$FOLDER":/var/www/html/wp-content/themes/"$NAME          >> ./docker-compose.yml
    echo "    expose:"                                                      >> ./docker-compose.yml
    echo "      - 80"                                                       >> ./docker-compose.yml
    echo "    depends_on:"                                                  >> ./docker-compose.yml
    echo "      - "$NAME"-db"                                               >> ./docker-compose.yml
    echo "    restart: always"                                              >> ./docker-compose.yml
    echo "    environment:"                                                 >> ./docker-compose.yml
    echo "      VIRTUAL_HOST:" $DOMAIN", www."$DOMAIN                       >> ./docker-compose.yml
    echo "      WORDPRESS_DB_NAME:" $DBNAME                                 >> ./docker-compose.yml
    echo "      WORDPRESS_DB_USER:" $DBUSER                                 >> ./docker-compose.yml
    echo "      WORDPRESS_DB_PASSWORD:" $DBPASS                             >> ./docker-compose.yml
    echo "      WORDPRESS_DB_HOST:" $NAME"-db"                              >> ./docker-compose.yml

    if [[ $DEBUG == 1 ]]; then 
        echo "      WORDPRESS_DEBUG:" $DEBUG                                >> ./docker-compose.yml
    fi

    # TODO: Wordpress image doesn't work with this
    if [[ $MULTISITE == 1 ]]; then
        echo "      WORDPRESS_CONFIG_EXTRA: |"                              >> ./docker-compose.yml
        echo "        define('WP_ALLOW_MULTISITE', true );"                 >> ./docker-compose.yml
        echo "        define('MULTISITE', true);"                           >> ./docker-compose.yml
        echo "        define('SUBDOMAIN_INSTALL', false);"                  >> ./docker-compose.yml
        echo "        define('DOMAIN_CURRENT_SITE', '$DOMAIN');"            >> ./docker-compose.yml
        echo "        define('PATH_CURRENT_SITE', '/');"                    >> ./docker-compose.yml
        echo "        define('SITE_ID_CURRENT_SITE', 1);"                   >> ./docker-compose.yml
        echo "        define('BLOG_ID_CURRENT_SITE', 1);"                   >> ./docker-compose.yml
    fi

    echo "      DOMAIN:" $DOMAIN                                            >> ./docker-compose.yml
    echo "    container_name: " $NAME                                       >> ./docker-compose.yml
    echo "  nginx-proxy:"                                                   >> ./docker-compose.yml
    echo "    image: jwilder/nginx-proxy"                                   >> ./docker-compose.yml
    echo "    container_name: nginx-proxy"                                  >> ./docker-compose.yml
    echo "    ports:"                                                       >> ./docker-compose.yml
    echo "      - '80:80'"                                                  >> ./docker-compose.yml
    echo "    volumes:"                                                     >> ./docker-compose.yml
    echo "      - /var/run/docker.sock:/tmp/docker.sock:ro"                 >> ./docker-compose.yml
    echo "networks:"                                                        >> ./docker-compose.yml
    echo "  default:"                                                       >> ./docker-compose.yml
    echo "    external:"                                                    >> ./docker-compose.yml
    echo "      name: nginx-proxy"                                          >> ./docker-compose.yml
fi

echo "==============="
echo ""

echo "3.5) Compose up"
docker-compose up -d

echo "==============="
echo ""

echo "3.6) Configure hosts file"
if [[ $inputVariable != 'production' ]]; then
    HOSTIP="$(docker-machine ip $MACHINE)"
    if grep -Fxq "$HOSTIP $DOMAIN" /etc/hosts; then
        echo "Already exists in host file"
    else
        echo "Added to host file"
        sudo cp /etc/hosts /etc/hosts.backup
        sudo -- sh -c "echo $HOSTIP $DOMAIN >> /etc/hosts"
    fi
else
    echo "Running in production so no change in hostfile"
fi

if [[ $inputVariable != 'production' ]]; then
    echo "7) You're up and running buddy!"
    echo "The website is running on http://$DOMAIN"
    echo "Next command you can run is grunt watch to start develop your theme"
fi
echo "==============="