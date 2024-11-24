#!/bin/bash

ARGS="$@"

if (( $# == 0 )); then
    ARGS="php example.php"
fi

BUILD_ID=$(docker build -q . -t vluzrmos/precodahora:latest)

if [ "$BUILD_ID" != "" ]; then
cd "$(dirname "$0")"
docker run --rm -it -v .:/app -w /app $BUILD_ID $ARGS
else
echo "Error: Docker build failed" > /dev/stderr
exit 1
fi