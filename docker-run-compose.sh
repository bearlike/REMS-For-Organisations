#!/usr/bin/env bash
echo -e "Building Image..."
docker build -t krishnaalagiri/rems:latest .
echo -e "Deploying Stack..."
docker-compose up -d