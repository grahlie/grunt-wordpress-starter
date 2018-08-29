#!/usr/bin/env bash

echo "1) NPM Install"
npm install

echo "2) Grunt deploy"
grunt

echo "3) Build docker"
cd ./docker
./build_web.sh