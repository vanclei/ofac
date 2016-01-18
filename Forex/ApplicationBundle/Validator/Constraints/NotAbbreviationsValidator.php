<?php

namespace Forex\ApplicationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;



class NotAbbreviationsValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{

		if (strpos($value, '.') !== FALSE ||
			strpos($value, '_') !== FALSE ||
			substr($value, 0, 1) == ' ' ||
			preg_match("/[^-a-zA-Z0-9 ]/i", $value)
		) {

			$this->context->buildViolation($constraint->message)
				->setParameter('%string%', $value)
				->addViolation();
		}
	}
}
