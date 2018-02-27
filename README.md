
# OpenSSL File Encrypt

#### Simple symmetric file encryption.


## Purpose

Provide simple and strong file encryption with OpenSSL, via PHP.

One use is to avoid direct OpenSSL interaction, such as:

        openssl enc -e -aes-256-cbc -in abc.txt -out abc.enc -k password -S deadbeef

and offer something simpler:

        php cmdline_example.php -e abc.txt


## License

OpenSSL File Encrypt is released under the [GPL v.3](https://www.gnu.org/licenses/gpl-3.0.html).
