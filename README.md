# Secure Information Storage REST API

### Project setup

* Add `secure-storage.localhost` to your `/etc/hosts`: `127.0.0.1 secure-storage.localhost`

* Run `make init` to initialize project

	This command do following steps:
	- Generates SSL certificates in `docker/ssl` directory
	- Builds and runs docker containers configured in `docker-compose.yml`
	- Creates symfony database, loads migrations and fixtures data

Note: Since SSL certificate is self signed - clients (browser/postman etc) might block requests until risks will be accepted. 
In Postman it's required to turn Off check certificate validity in settings.

* Open in browser: http://secure-storage.localhost:8000/item Should get `Full authentication is required to access this resource.` error, because first you need to make `login` call (see `postman_collection.json` or `SecurityController` for more info).

### Run tests

make tests

### API credentials

* User: john
* Password: maxsecure

### Postman requests collection

You can import all available API calls to Postman using `postman_collection.json` file
