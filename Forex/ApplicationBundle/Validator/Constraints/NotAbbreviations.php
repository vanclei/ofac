<?php

namespace Forex\ApplicationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotAbbreviations extends Constraint
{
	public $message = '"%string%" cannot be abbreviate, start with space or containing invalid characters';
}

