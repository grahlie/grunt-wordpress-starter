#!/bin/bash

echo "1) Install JSON parser"
UNAME=`uname`
which -s jq >/dev/null
if [[ $? == 1 ]]; then 
    if [[ "$UNAME" == 'Linux' ]]; then
        sudo apt-get install jq
    elif [[ "$UNAME" == 'Darwin' ]]; then
        brew install jq
    fi
fi

echo "2) Set some variables"
FILE="../config.json"
NAME=$(jq .name $FILE)
FOLDER=$(jq .grunt.deploy $FILE)
PORT=$(jq .docker.port $FILE)
DOMAIN=$(jq .docker.domain $FILE)
DBNAME=$(jq .docker.dbname $FILE)
DBUSER=$(jq .docker.dbuser $FILE)
DBPASS=$(jq .docker.dbpass $FILE)
DBVOLUME=$(jq .docker.dbvolume $FILE)
DEBUG=$(jq .docker.debug $FILE)
MACHINE=$(jq .docker.machine $FILE)

echo "3) Strip specialchars from JSON value"
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
DBVOLUME="${DBVOLUME%\"}"
DBVOLUME="${DBVOLUME#\"}"
DEBUG="${DEBUG%\"}"
DEBUG="${DEBUG#\"}"
MACHINE="${MACHINE%\"}"
MACHINE="${MACHINE#\"}"

echo "4) Create compose file"
dockercompose="./docker-compose.yml"
if ! [ -e $dockercompose ]; then
    echo "version: '2'"                           >> ./docker-compose.yml
    echo "services:"                              >> ./docker-compose.yml
    echo "  "$NAME"-db:"                          >> ./docker-compose.yml
    echo "    image: mysql:5.7"                   >> ./docker-compose.yml
    echo "    restart: always"                    >> ./docker-compose.yml
    echo "    environment:"                       >> ./docker-compose.yml
    echo "      MYSQL_ROOT_PASSWORD: wordpress"   >> ./docker-compose.yml
    echo "      MYSQL_DATABASE:" $DBNAME          >> ./docker-compose.yml
    echo "      MYSQL_USER:" $DBUSER              >> ./docker-compose.yml
    echo "      MYSQL_PASSWORD:" $DBPASS          >> ./docker-compose.yml
    echo "    container_name: " $NAME"-db"        >> ./docker-compose.yml
    echo "  "$NAME":"                             >> ./docker-compose.yml
    echo "    build: ."                           >> ./docker-compose.yml
    echo "    volumes:"                           >> ./docker-compose.yml
    echo "      - "$FOLDER"/:/var/www/"           >> ./docker-compose.yml
    echo "    expose:"                            >> ./docker-compose.yml
    echo "      - 80"                             >> ./docker-compose.yml
    echo "    depends_on:"                        >> ./docker-compose.yml
    echo "      - "$NAME"-db"                     >> ./docker-compose.yml
    echo "    restart: always"                    >> ./docker-compose.yml
    echo "    environment:"                       >> ./docker-compose.yml
    echo "      VIRTUAL_HOST:" $DOMAIN            >> ./docker-compose.yml
    echo "      DBNAME:" $DBNAME                  >> ./docker-compose.yml
    echo "      DBUSER:" $DBUSER                  >> ./docker-compose.yml
    echo "      DBPASS:" $DBPASS                  >> ./docker-compose.yml
    echo "      DBHOST:" $NAME"-db"               >> ./docker-compose.yml
    echo "      DOMAIN:" $DOMAIN                  >> ./docker-compose.yml
    echo "      DEBUG:" '$DEBUG'                  >> ./docker-compose.yml
    echo "    container_name: " $NAME             >> ./docker-compose.yml
    echo "networks:"                              >> ./docker-compose.yml
    echo "  default:"                             >> ./docker-compose.yml
    echo "    external:"                          >> ./docker-compose.yml
    echo "      name: nginx-proxy"                >> ./docker-compose.yml
fi


echo "5) Compose build"
docker-compose build

echo "6) Compose down (fix for old cache)"
docker-compose -f docker-compose.yml down

echo "7) Compose up"
docker-compose up -d

echo "8) Configure hosts file"
sudo cp /etc/hosts /etc/hosts.backup

# Add container to hosts
# TODO: Adds multiple
HOSTIP="$(docker-machine ip $MACHINE)"
sudo -- sh -c "echo $HOSTIP $DOMAIN >> /etc/hosts"

# Remove Backup
# sudo rm /etc/hosts.backup
