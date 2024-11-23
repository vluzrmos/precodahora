#!/bin/bash

args=("$@")

if (( $# == 0 )); then
    args="php example.php"
fi

docker run --rm -it -v .:/app -w /app $(docker build -q .) $args