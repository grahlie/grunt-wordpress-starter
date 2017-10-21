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


echo "2) Set some ENV variables"
FILE="../config.json"
NAME=$(jq .name $FILE)
FOLDER=$(jq .grunt.deploy $FILE)
PORT=$(jq .docker.port $FILE)
DOMAIN=$(jq .docker.domain $FILE)
DBNAME=$(jq .docker.dbname $FILE)
DBHOST=$(jq .docker.dbhost $FILE)
DBUSER=$(jq .docker.dbuser $FILE)
DBPASS=$(jq .docker.dbpass $FILE)

echo "3) Strip specialchars from JSON value"
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
DBHOST="${DBHOST%\"}"
DBHOST="${DBHOST#\"}"
DBUSER="${DBUSER%\"}"
DBUSER="${DBUSER#\"}"
DBPASS="${DBPASS%\"}"
DBPASS="${DBPASS#\"}"

echo "4) Build image"
docker build -t $NAME .

echo "5) Create compose file"
echo "version: '2'"                           >> ./docker-compose.yml
echo "services:"                              >> ./docker-compose.yml
echo "  "$NAME"-db:"                          >> ./docker-compose.yml
echo "    image: mysql:5.7"                   >> ./docker-compose.yml
echo "    environment:"                       >> ./docker-compose.yml
echo "      MYSQL_ROOT_PASSWORD: wordpress"   >> ./docker-compose.yml
echo "      MYSQL_DATABASE:" $DBNAME          >> ./docker-compose.yml
echo "      MYSQL_USER:" $DBUSER              >> ./docker-compose.yml
echo "      MYSQL_PASSWORD:" $DBPASS          >> ./docker-compose.yml
echo "  "$NAME":"                             >> ./docker-compose.yml
echo "    build: ."                           >> ./docker-compose.yml
echo "    volumes:"                           >> ./docker-compose.yml
echo "      - "$FOLDER$NAME"/:/var/www/html/" >> ./docker-compose.yml
echo "    ports:"                             >> ./docker-compose.yml
echo "      - '"$PORT":80'"                   >> ./docker-compose.yml
echo "    depends_on:"                        >> ./docker-compose.yml
echo "      - "$NAME"-db"                     >> ./docker-compose.yml
echo "    environment:"                       >> ./docker-compose.yml
echo "      DBNAME:" $DBNAME                  >> ./docker-compose.yml
echo "      DBUSER:" $DBUSER                  >> ./docker-compose.yml
echo "      DBPASS:" $DBPASS                  >> ./docker-compose.yml
echo "      DBHOST:" $DBHOST                  >> ./docker-compose.yml
echo "      DOMAIN:" $DOMAIN                  >> ./docker-compose.yml

echo "6) Compose up"
docker-compose up -d


#echo 6) Configure hosts file
# ##################################################################################################
# #Confar /etc/hosts med de nystartade confarna
# if [ "$UPPDATERAHOSTS" != "" ]; then
#   echo
#   echo "Försöker ställa om ip-numret i din hosts-fil."
#   cd /var/tmp
#   cp /etc/hosts hosts.bak
#   sudo sed -ie "/# boot2dock/d" /etc/hosts
#   for containerid in $(docker ps -q); do
#     echo Checking out $containerid
#     export domain=$(docker inspect $containerid | grep '"Domainname":' | cut -f2 -d":" | cut -f2 -d'"')
#     export host=$(docker inspect $containerid | grep '"Hostname":' | cut -f2 -d":" | cut -f2 -d'"')
#     export ip=$(docker inspect $containerid | grep 'IPAdd' | egrep -o "[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*")
#     if [ "$ip" != "" ]; then
#       if [ "$domain" != "" ]; then
#         echo Storing values $ip $host.$domain
#         grep $host.$domain /etc/hosts && echo .....ojojojojoj..... $host.$domain finns redan i din hosts-fil.
#         echo $ip $host.$domain \# boot2dockerscript | sudo tee -a /etc/hosts
#       else
#         echo Storing values $ip $host
#         grep $host /etc/hosts && echo .....ojojojojoj..... $host finns redan i din hosts-fil.
#         echo $ip $host \# boot2dockerscript | sudo tee -a /etc/hosts
#       fi
#     fi
#   done
# fi