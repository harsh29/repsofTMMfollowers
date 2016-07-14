#! /bin/bash

CUR=$(cd `dirname $0`; pwd)

SRC="${CUR}/$1"

if [ -d "$SRC" ]; then
        cp -r "$SRC" "${CUR}/../pub/fonts/"
else
        echo "cannot find backup $SRC"
        exit 1
fi

