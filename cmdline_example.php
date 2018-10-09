#!/usr/bin/env php
<?php

/**
    * Command-line example usage of OpenSSL-File-Encrypt class.
    *
    * Processes up to 1.8GB on CLI, subject to memory availability.
    *
    * Usage:
    *        php <thisfilename> -e|-d <filename>
    *
    * @author        Martin Latter <copysense.co.uk>
    * @copyright     Martin Latter 20/02/2018
    * @version       0.05
    * @license       GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
    * @link          https://github.com/Tinram/OpenSSL-File-Encrypt.git
*/


declare(strict_types=1);


define('DUB_EOL', PHP_EOL . PHP_EOL);
define('LINUX', (stripos(php_uname(), 'linux') !== FALSE) ? TRUE : FALSE);


if ( ! extension_loaded('openssl'))
{
    die(PHP_EOL . ' OpenSSL library not available!' . DUB_EOL);
}


require('classes/openssl_file.class.php');


$sUsage =
    PHP_EOL . ' ' . basename(__FILE__, '.php') .
    DUB_EOL . "\tusage: php " . basename(__FILE__) . ' -e|-d <file> ' . ( ! LINUX ? '<password>' : '') . DUB_EOL;

$sMode = null;
$aOptions = getopt('h::e::d::', ['help::', 'h::']);

if ( ! empty($aOptions))
{
    $sOpt = key($aOptions);

    switch ($sOpt)
    {
        case 'h':
            die($sUsage);
        break;

        case 'e':
        case 'd':
           $sMode = $sOpt;
        break;
    }
}
else
{
    die($sUsage);
}

if ( ! isset($_SERVER['argv'][2]))
{
    echo PHP_EOL . ' missing filename!' . PHP_EOL;
    die($sUsage);
}

$sFilename = $_SERVER['argv'][2];

if ( ! file_exists($sFilename))
{
    die(PHP_EOL . ' \'' . $sFilename . '\' does not exist in this directory!' . DUB_EOL);
}


if (LINUX)
{
    echo ' password: ';
    `/bin/stty -echo`;
    $sPassword = trim(fgets(STDIN));
    `/bin/stty echo`;

    if ($sMode === 'e')
    {
        echo PHP_EOL . ' re-enter password: ';
        `/bin/stty -echo`;
        $sPassword2 = trim(fgets(STDIN));
        `/bin/stty echo`;

        if ($sPassword !== $sPassword2)
        {
            die(PHP_EOL . ' entered passwords do not match!' . DUB_EOL);
        }
    }
}
else
{
    if ( ! isset($_SERVER['argv'][3]))
    {
        die(PHP_EOL . ' missing password!' . DUB_EOL . "\tusage: " . basename(__FILE__) . ' -e|-d <file> <password>' . DUB_EOL);
    }
    else
    {
        $sPassword = $_SERVER['argv'][3];
    }
}


if ($sMode === 'e')
{
    echo OpenSSLFile::encrypt($sFilename, $sPassword);
}
else if ($sMode === 'd')
{
    echo OpenSSLFile::decrypt($sFilename, $sPassword);
}
