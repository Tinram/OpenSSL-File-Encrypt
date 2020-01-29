<?php

declare(strict_types=1);

final class OpenSSLFile
{
    /**
        * Encrypt and decrypt files with OpenSSL module.
        *
        * Coded to PHP 7.2
        *
        * @author      Martin Latter
        * @copyright   Martin Latter 20/02/2018
        * @version     0.09
        * @license     GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
        * @link        https://github.com/Tinram/OpenSSL-File-Encrypt.git
    */

    /** @const CIPHER encryption algorithm */
    const CIPHER = 'AES-256-CTR';
        /* other strong ciphers: AES-256-CBC, CAMELLIA-256-CBC */

    /** @const KEY_HASH, hash for password hashing and stretching */
    const KEY_HASH = 'SHA512';

    /** @const KEY_STRETCHES, default number of key stretch iterations used when not user-defined  */
    const KEY_STRETCHES = 2 ** 16;

    /** @const SALT, default salt for hash_pbkdf2() when not defined by calling script */
    const SALT = '♟⚡╀♦╋┌⚪♵┟◔━─┽♙⒉▦◐○♉◚ⓡⓚ♔┈Ⓢ┣╱♹⒖ⓔ╊⓻♧ⓛ┉♬┢☄◤⚧▣◵⚗⓭♋ⓛ☌⚵◜Ⓒ☶ⓟ⚄⚈┎☕♍☜╉█♫ⓧ⒛⓭';

    /** @const HMAC_HASH, hash for HMAC */
    const HMAC_HASH = 'SHA512';

    /** @const HMAC_LEN, HMAC_HASH byte length */
    const HMAC_LEN = 64;

    /** @const FILE_EXT, encrypted file extension suffix */
    const FILE_EXT = '.osl';


    /**
        * Encrypt a file.
        *
        * @param   string $sFilename, the file to encrypt
        * @param   string $sPassword, the password
        * @param   integer $iKeyStretches, number of key derivation iterations
        * @param   string $sSalt, a CSPRNG-generated string used in key derivation (should be user-defined and not default self::SALT)
        *
        * @return  string, message
    */

    public static function encrypt(string $sFilename = '', string $sPassword = '', int $iKeyStretches = self::KEY_STRETCHES, string $sSalt = self::SALT): string
    {
        self::checkArgs(__METHOD__, func_get_args());

        if ($sSalt === self::SALT)
        {
             echo PHP_EOL . ' Warning: using the default ' . __CLASS__ . ' key derivation salt. A different CSPRNG-generated salt should be used.' . PHP_EOL;
        }

        $sPlaintext = file_get_contents($sFilename);

        $sKey = hash_pbkdf2(self::KEY_HASH, $sPassword, $sSalt, $iKeyStretches);

        $iIVLen = openssl_cipher_iv_length(self::CIPHER);
        $IV = random_bytes($iIVLen);

        $sCiphertextRaw = openssl_encrypt($sPlaintext, self::CIPHER, $sKey, OPENSSL_RAW_DATA, $IV);

        $HMAC = hash_hmac(self::HMAC_HASH, $sCiphertextRaw, $sKey, $binary=true);
        $sCiphertext = $IV . $HMAC . $sCiphertextRaw;

        $iF = file_put_contents($sFilename . self::FILE_EXT, $sCiphertext);

        if ($iF !== false)
        {
            return PHP_EOL . ' \'' . $sFilename . self::FILE_EXT . '\' saved' . PHP_EOL;
        }
        else
        {
            return PHP_EOL . ' encryption failed' . PHP_EOL;
        }
    }


    /**
        * Decrypt a file.
        *
        * @param   string $sFilename, the file to decrypt
        * @param   string $sPassword, the password
        * @param   integer $iKeyStretches, number of key derivation iterations
        * @param   string $sSalt, a CSPRNG-generated string used in key derivation
        *
        * @return  string, message
    */

    public static function decrypt(string $sFilename = '', string $sPassword = '', int $iKeyStretches = self::KEY_STRETCHES, string $sSalt = self::SALT): string
    {
        self::checkArgs(__METHOD__, func_get_args());

        $sCiphertext = file_get_contents($sFilename);

        $sKey = hash_pbkdf2(self::KEY_HASH, $sPassword, $sSalt, $iKeyStretches);

        $iIVLen = openssl_cipher_iv_length(self::CIPHER);
        $IV = substr($sCiphertext, 0, $iIVLen);
        $HMAC = substr($sCiphertext, $iIVLen, self::HMAC_LEN);

        $sCiphertextRaw = substr($sCiphertext, $iIVLen + self::HMAC_LEN);
        $sDecryptedText = openssl_decrypt($sCiphertextRaw, self::CIPHER, $sKey, OPENSSL_RAW_DATA, $IV);
        $sCalcMAC = hash_hmac(self::HMAC_HASH, $sCiphertextRaw, $sKey, $binary=true);

        if (hash_equals($HMAC, $sCalcMAC))
        {
            $iF = file_put_contents(basename($sFilename, self::FILE_EXT), $sDecryptedText);

            if ($iF !== false)
            {
                return PHP_EOL . ' decrypted file \'' . basename($sFilename, self::FILE_EXT) . '\' saved' . PHP_EOL;
            }
            else
            {
                return PHP_EOL . ' file creation failed' . PHP_EOL;
            }
        }
        else
        {
            return PHP_EOL . ' authentication failure: incorrect password (or file corruption) ' . PHP_EOL;
        }
    }


    /**
        * Passed arguments checker.
        *
        * @param   string $sMethodName, name of the method - for error output
        * @param   array $aArgs<string>, method arguments
    */

    private static function checkArgs(string $sMethodName = '', array $aArgs = []): void
    {
        if (count($aArgs[0]) === 0)
        {
            die(__CLASS__ . '::' . $sMethodName . '(): $sFilename is missing!' . PHP_EOL);
        }
        else if (count($aArgs[1]) === 0)
        {
            die(__CLASS__ . '::' . $sMethodName . '(): $sPassword is missing!' . PHP_EOL);
        }
    }
}
