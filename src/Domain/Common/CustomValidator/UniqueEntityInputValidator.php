<?php

namespace App\Domain\Common\CustomValidator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class UniqueEntityInputValidator
 * @package App\Domain\Common\Validators
 */
class UniqueEntityInputValidator extends ConstraintValidator
{

    /** @var EntityManagerInterface */
    private $em;

    /**
     * UniqueEntityInputValidator constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEntityInput) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueEntityInput');
        }

        if (null === $value || '' === $value) {
            return;
        }

        $fields = (array) $constraint->fields;

        foreach ($fields as $name) {
            $fieldValue = $value->{'get' . ucfirst($name)}();

            /** @phpstan-ignore-next-line */
            $object = $this->em->getRepository($constraint->class)
                ->findOneBy(
                    [
                        $name => $fieldValue,
                    ]
                )
            ;

            if ($object && 0 === $this->context->getViolations()->count()) {
                $this->context->buildViolation($constraint->message)
                    ->atPath($name)
                    ->addViolation();
            }
        }
    }
}
