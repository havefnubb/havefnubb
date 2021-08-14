#!/bin/bash

if [ "$1" == "" -o "$2" == "" ]; then
  echo "Usage: ssl.sh <command> <domain>"
  echo "commands: reset, resetCA, createCA, createCert, checkCSR, checkCert"
fi

docker image inspect hfn-openssl >/dev/null 2>&1
if [ "$?" == "1" ]; then
  docker build -t hfn-openssl openssl/
fi

if [ "$1" == "createCA" ]; then
  docker run -it -v $(pwd)/certs:/sslcerts --user $(id -u):$(id -g) --env CA_CERT_DOMAIN=$2  hfn-openssl $1
else
  if [ "$3" == "" ]; then
    cadomain=tests.hfn
  else
    cadomain=$3
  fi
  docker run -it -v $(pwd)/certs:/sslcerts --user $(id -u):$(id -g) --env CERT_DOMAIN=$2  --env CA_CERT_DOMAIN=$cadomain hfn-openssl $1
fi
