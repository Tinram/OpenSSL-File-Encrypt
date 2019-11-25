
# OpenSSL File Encrypt

#### Simple symmetric file encryption using OpenSSL.


## Purpose

Provide simple-to-use and strong file encryption with OpenSSL and HMAC authentication, via an easy-to-use PHP wrapper.


## Background

OpenSSL includes tools for encrypting files; however, its command-line usage could be considered 'unfriendly':

```console
    openssl enc -e -aes-256-cbc -in abc.txt -out abc.enc -k password -S deadbeef
```

This package can replace the above file encryption command with something simpler:

```console
    php cmdline_example.php -e abc.txt
```


## Example

### Encrypt

```console
    php cmdline_example.php -e abc.txt
```

results in the encrypted file *abc.txt.osl*

### Decrypt

```console
    php cmdline_example.php -d abc.txt.osl
```

results in *abc.txt* (with the correct password)

&ndash; and ***overwrites*** the original file *abc.txt* if it is present in the same directory.


## Set-up

### Improve Encryption Security

In *cmdline_example.php* (and any new files based on this file):

+ increase the value of `MY_KEY_STRETCHES`
    + high values will cause a noticeable processing delay &ndash; which is desirable to slow brute-force attacks against encrypted files
+ replace `MY_SALT` string with a new CSPRNG-generated string of random bytes, separating your key-derivation salt from the publicly-available (GitHub) default values
    + ideally the `MY_SALT` string should be unique for each encryption transaction, voiding a rainbow table created against a static salt
    + however, in a command-line script context, this impedes usability (effectively two passwords, one always different per transaction)
+ securely backup the new `MY_KEY_STRETCHES` and `MY_SALT` values
    + if the the new values are lost, the ***encrypted data will be unrecoverable***.


## Testing

```bash
    cd tests/

    sh test_openssl-file-encrypt.sh
```

or

```bash
    ./test_openssl-file-encrypt.sh
```


## Max File Size

The maximum file size that can be processed is approximately 1.8GB (with no *php.ini* memory limitations).

The 1.8GB limit is apparently dictated by the PHP *openssl* module (the OpenSSL executable will process files larger than 2GB).


## Speed

Counter (CTR) cipher modes appear to be the fastest.

Encryption and decryption rates of approximately 170MB/sec are possible on mid-range hardware in CTR mode.


## Low Memory Systems

A file-chunking version for limited memory availability works with the non-counter mode ciphers.

Adding the HMAC to the final file and decrypting successfully is not yet ready.


## References

### OpenSSL

+ [Usability](https://jameshfisher.com/2017/12/02/the-sorry-state-of-openssl-usability)

### Key Derivation

+ [StackExchange](https://security.stackexchange.com/questions/29106/openssl-recover-key-and-iv-by-passphrase)
+ [EVP_BytesToKey](https://www.openssl.org/docs/manmaster/man3/EVP_BytesToKey.html)
+ [Source](https://github.com/openssl/openssl/blob/master/apps/enc.c)


## License

OpenSSL File Encrypt is released under the [GPL v.3](https://www.gnu.org/licenses/gpl-3.0.html).
