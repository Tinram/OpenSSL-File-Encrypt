
# OpenSSL File Encrypt

#### Simple symmetric file encryption.


## Purpose

Provide strong file encryption using OpenSSL, via an easy-to-use PHP wrapper.

One use is to avoid direct OpenSSL interaction, such as:

        openssl enc -e -aes-256-cbc -in abc.txt -out abc.enc -k password -S deadbeef

and offer something simpler:

        php cmdline_example.php -e abc.txt


## Example

### Encrypt

        php cmdline_example.php -e abc.txt

results in the encrypted file *abc.txt.osl*

### Decrypt

        php cmdline_example.php -d abc.txt.osl

results in *abc.txt*, with the correct password

-- and **overwrites** the original file *abc.txt* if it is present in the same directory.


## Max File Size

The maximum file size that can be processed is approximately 1.8GB.

This limit is seemingly dictated by the PHP *openssl* module (OpenSSL itself on the command-line will process 2GB+ files).


## Low Memory Systems

A file-chunking version for limited memory availability works with the non-counter (CTR) mode ciphers.

Appending the HMAC to the final file and decrypting successfully is not yet ready.


## License

OpenSSL File Encrypt is released under the [GPL v.3](https://www.gnu.org/licenses/gpl-3.0.html).
