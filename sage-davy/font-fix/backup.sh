#! /bin/bash

CUR=$(cd `dirname $0`; pwd)

SRC="${CUR}/../pub/fonts/$1"

if [ -d "$SRC" ]; then
        cp -r "$SRC" "$CUR/"
else
        echo "cannot find font $SRC"
        exit 1
fi

