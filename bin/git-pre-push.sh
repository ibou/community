#!/bin/bash
docker exec sf4_php_apache ./vendor/bin/simple-phpunit  --exclude-group reposbdd --coverage-text --coverage-html web/coverage