<?php

namespace App\Domain\Common\Exception;

/**
 * Class ValidationException
 * @package App\Domain\Common\Exception
 */
final class ValidationException extends \Exception
{

    /** @var array */
    protected $params;

    /**
     * ValidationException constructor.
     * @param string $message
     * @param array $params
     */
    public function __construct($message = "", array $params = [])
    {
        parent::__construct($message);

        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
