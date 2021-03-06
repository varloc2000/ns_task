.PHONY: start stop init build tests

start:
	docker-compose up -d

stop:
	docker-compose stop

init:
	$(shell chmod +x ./build/generate_openssl.sh)
	./build/generate_openssl.sh
	docker-compose build
	docker-compose up -d
	docker-compose exec php composer install
	docker-compose exec php php bin/console doctrine:database:create
	docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction

build:
	$(shell chmod +x ./build/build.sh)
	build/build.sh

tests:
	docker-compose exec php php vendor/bin/simple-phpunit
