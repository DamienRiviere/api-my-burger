<?php

namespace App\Domain\Service;

use App\Domain\Common\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class Validator
 * @package App\Domain\Service
 */
final class Validator
{

    /** @var ValidatorInterface */
    protected $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $object
     * @throws ValidationException
     * @phpstan-ignore-next-line
     */
    public function validate($object): void
    {
        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            $messages = [];

            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
            }

            throw new ValidationException("", $messages);
        }
    }
}
