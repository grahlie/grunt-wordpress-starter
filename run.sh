#!/usr/bin/env bash
inputVariable=$1

echo "1) NPM Install"
npm install

echo "2) Grunt deploy"
if [[ $inputVariable == 'production' ]]; then
    grunt production
else
    grunt
fi

echo "3) Build docker"
cd ./docker
if [[ $inputVariable == 'production' ]]; then
    ./build_web.sh production
else
    ./build_web.sh
fi