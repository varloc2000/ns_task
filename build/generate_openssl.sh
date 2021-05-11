#!/bin/bash

docker run --rm -v $PWD/docker/ssl:/usr/share alpine /bin/sh -c "apk add openssl; openssl req -x509 -nodes -days 365 -subj \"/C=CA/ST=QC/O=NodrSecurity, Inc./CN=secure-storage.localhost;\" -addext \"subjectAltName=DNS:secure-storage.localhost;\" -newkey rsa:2048 -keyout /usr/share/new-selfsigned.key -out /usr/share/new-selfsigned.crt;"
