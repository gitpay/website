#!/bin/bash

TMPFILE=/tmp/$$

echo $1 > $TMPFILE

OUT=$(2>/dev/null ssh-keygen -e -f $TMPFILE -m PKCS8 | openssl rsa -pubin -text | grep ':[0-9a-z][0-9a-z]' | sed 's/://g' | sed ':a;N;$!ba;s/\n//g' | sed 's/\s//g' )

echo -n "$OUT";

rm $TMPFILE
