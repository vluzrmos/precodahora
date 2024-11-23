#!/bin/bash

args=("$@")

if (( $# == 0 )); then
    args="php example.php"
fi

docker run --rm -it $(docker build -q .) $args