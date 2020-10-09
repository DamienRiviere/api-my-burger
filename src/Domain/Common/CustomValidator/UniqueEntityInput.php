<?php

namespace App\Domain\Common\CustomValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * Class UniqueEntityInput
 * @package App\Domain\Common\CustomValidator
 * @Annotation
 */
class UniqueEntityInput extends Constraint
{
    /** @var string  */
    public $message = "Cette valeur doit être unique !";

    /** @var string */
    public $class;

    /** @var array */
    public $fields = [];

    /**
     * UniqueEntityInput constructor.
     * @param null $options
     */
    public function __construct($options = null)
    {
        /** @phpstan-ignore-next-line */
        if (!is_null($options) && !\is_array($options)) {
            $options = [
                'class' => $options,
            ];
        }
        parent::__construct($options);
        if (!$this->class) {
            throw new MissingOptionsException(
                sprintf("Either option 'class' must be define for constraint %s", __CLASS__),
                ['class']
            );
        }
    }

    /**
     * @return array
     */
    public function getRequiredOptions(): array
    {
        return [
            'fields',
            'class',
        ];
    }

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
