<?php

/*
 * This file is part of the Extractor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Vzaica\Extractor\Exception;

use Exception;

/**
 * Class ExtensionNotSupportedException
 */
class ExtensionNotSupportedException extends Exception
{
    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Construct the exception. Note: The message is NOT binary safe.
     *
     * @link http://php.net/manual/en/exception.construct.php
     *
     * @param string    $message  [optional] The Exception message to throw.
     * @param int       $code     [optional] The Exception code.
     * @param Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        $message = 'Extension "' . $message . '" is currenly not supported. Extensions supported are [RAR, ZIP, PHAR, TAR, GZ, BZ2]';

        parent::__construct($message, $code, $previous);
    }
}
