#!/bin/sh
RED='\033[0;31m'
NC='\033[0m' # No Color
echo -e "${RED}file:${1}${NC}"

echo -e "${RED}php artisan make:model Models${1}${NC}"
php artisan make:model Models$1

echo -e "${RED}php artisan make:controller ${1}Controller --resource${NC}"
php artisan make:controller $1Controller --resource

echo -e "${RED}php artisan make:service ${1}Service${NC}"
php artisan make:service $1Service

echo -e "${RED}php artisan make:dataobjects ${1}${NC}"
php artisan make:dataobjects $1

echo ...done
