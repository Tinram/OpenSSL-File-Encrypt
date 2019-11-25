#!/bin/sh

# File:           test_openssl-file-encrypt.sh
#
# Description:    OpenSSL-File-Encrypt (OFE) encryption and decryption test,
#                 relying first on OFE's HMAC, and then file comparison.
#
# Usage:          ./test_openssl-file-encrypt.sh
#
# Copyright:      Martin Latter, 25/11/2019
# Version:        0.02
# License:        GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
# Link:           https://github.com/Tinram/OpenSSL-File-Encrypt.git


TEST_FILE="test.txt"

if command -v openssl
then
	openssl rand -hex 1024 > $TEST_FILE
else
	head -c 1024 /dev/urandom | base64 -w 0 > $TEST_FILE
fi

cp $TEST_FILE orig.$TEST_FILE

echo "encrypt $TEST_FILE"

php ../cmdline_example.php -e $TEST_FILE

echo "decrypt $TEST_FILE.osl"

php ../cmdline_example.php -d $TEST_FILE.osl

cmp $TEST_FILE orig.$TEST_FILE

md5sum $TEST_FILE orig.$TEST_FILE
