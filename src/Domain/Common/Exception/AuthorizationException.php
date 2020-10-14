<?php

namespace App\Domain\Common\Exception;

use Throwable;

/**
 * Class AuthorizationException
 * @package App\Domain\Common\Exception
 */
final class AuthorizationException extends \Exception
{

    /**
     * AuthorizationException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
