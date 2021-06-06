#!/usr/bin/env bash
docker stop "rems-mysql" && docker rm "rems-mysql"
docker stop "rems-apache" && docker rm "rems-apache"
docker network create --driver=bridge --subnet=172.16.238.0/24 "rems-network"
docker volume rm "rems_mysql" && docker volume create "rems_mysql"

docker run --name "rems-mysql" -v "rems_mysql":"/var/lib/mysql" -v "$PWD/docker/mysql":"/docker-entrypoint-initdb.d" -e MYSQL_ROOT_PASSWORD="0000" -p 3306:3306 -d --network "rems-network" --ip 172.16.238.10 mysql
docker build -t krishnaalagiri/rems:latest .

docker run --name "rems-apache" -d -p 8080:80 --network "rems-network" krishnaalagiri/rems:latest