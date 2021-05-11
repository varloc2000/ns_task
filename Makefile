.PHONY: start stop init build tests

start:
	docker-compose up -d

stop:
	docker-compose stop

init:
  docker run --rm -v $PWD/docker/ssl:/usr/share alpine /bin/sh -c "apk add openssl; openssl req -x509 -nodes -days 365 -subj \"/C=CA/ST=QC/O=NodrSecurity, Inc./CN=secure-storage.localhost;\" -addext \"subjectAltName=DNS:secure-storage.localhost;\" -newkey rsa:2048 -keyout /usr/share/new-selfsigned.key -out /usr/share/new-selfsigned.crt;"
	docker-compose build
	docker-compose up -d
	docker-compose exec php composer install
	docker-compose exec php php bin/console doctrine:database:create
	docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction

build:
	build/build.sh

tests:
	docker-compose exec php php vendor/bin/simple-phpunit
