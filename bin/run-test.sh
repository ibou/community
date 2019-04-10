#!/bin/bash

docker exec -it sf4_php_apache ./vendor/bin/simple-phpunit --coverage-text --coverage-html web/coverage