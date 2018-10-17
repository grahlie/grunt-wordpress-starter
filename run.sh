#!/usr/bin/env bash
inputVariable=$1

if [[ $inputVariable < 1 ]]; then
    echo "Variable is empty, printing help for script!"
    echo "Run this script with one of these commands:"
    echo ""
    echo ""
    echo "--------------------------------------------------------"
    echo "|    production -- for a fully production ready web    |"
    echo "|    dev -- a web without compressed files             |"
    echo "|    fresh -- for a new clean install                  |"
    echo "--------------------------------------------------------"
    echo ""
    echo ""
    exit;
fi

echo "1) NPM Install"
if [[ ! -d 'node_modules' ]]; then
    npm install
else
    echo "node_modules already exists"
fi

echo "==============="
echo ""

echo "2) Grunt deploy"
if [[ $inputVariable == 'production' ]]; then
    grunt production
else
    grunt dev
fi

echo "==============="
echo ""

echo "3) Build docker"
cd ./docker
if [[ $inputVariable == 'production' ]]; then
    ./build_web.sh production
elif [[ $inputVariable == 'fresh' ]]; then
    echo "New docker-compose.yml file"
    rm docker-compose.yml
    ./build_web.sh dev
else
    ./build_web.sh dev
fi

echo "==============="