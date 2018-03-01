
# OpenSSL File Encrypt

#### Simple symmetric file encryption.


## Purpose

Provide strong file encryption using OpenSSL, via an easy-to-use PHP wrapper.

One use is to avoid direct OpenSSL interaction, such as:

        openssl enc -e -aes-256-cbc -in abc.txt -out abc.enc -k password -S deadbeef

and offer something simpler:

        php cmdline_example.php -e abc.txt


## Max File Size

The maximum file size that can be processed is approximately 1.8GB.

This limit is decided by the PHP openssl module (OpenSSL itself processes 2GB+ files).


## Low Memory Systems

A file-chunking version for limited memory availability works with the non-counter mode ciphers.

Appending the HMAC of the final file is not yet ready.


## License

OpenSSL File Encrypt is released under the [GPL v.3](https://www.gnu.org/licenses/gpl-3.0.html).
