
# OpenSSL File Encrypt

#### Simple symmetric file encryption.


## Purpose

Provide strong file encryption using OpenSSL, via an easy-to-use PHP wrapper.

One use is to avoid direct OpenSSL command-line interaction, such as:

        openssl enc -e -aes-256-cbc -in abc.txt -out abc.enc -k password -S deadbeef

and offer something simpler:

        php cmdline_example.php -e abc.txt


## Example

### Encrypt

        php cmdline_example.php -e abc.txt

results in the encrypted file *abc.txt.osl*

### Decrypt

        php cmdline_example.php -d abc.txt.osl

results in *abc.txt* (with the correct password)

-- and ***overwrites*** the original file *abc.txt* if it is present in the same directory.


## Max File Size

The maximum file size that can be processed is approximately 1.8GB.  (With no *php.ini* memory limitations.)

The 1.8GB limit is seemingly dictated by the PHP *openssl* module (OpenSSL itself on the command-line will process 2GB+ files).


## Speed

The counter (CTR) cipher modes appear to be the fastest.

Encryption / decryption rates of approximately 170MB/sec can be expected on mid-range hardware in CTR mode.


## Low Memory Systems

A file-chunking version for limited memory availability works with the non-counter mode ciphers.

Adding the HMAC to the final file and decrypting successfully is not yet ready.


## References

### OpenSSL key derivation

+ [StackExchange](https://security.stackexchange.com/questions/29106/openssl-recover-key-and-iv-by-passphrase)
+ [EVP_BytesToKey](https://www.openssl.org/docs/manmaster/man3/EVP_BytesToKey.html)
+ [OpenSSL source](https://github.com/openssl/openssl/blob/master/apps/enc.c)


## License

OpenSSL File Encrypt is released under the [GPL v.3](https://www.gnu.org/licenses/gpl-3.0.html).
