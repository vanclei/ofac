<?php

namespace Forex\ApplicationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AllowZeroCpf extends Constraint
{
	public $message = 'Identity cannot be zeros';

	public function validatedBy() {
		return "allowzerocpf";
	}

}

