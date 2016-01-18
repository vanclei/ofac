<?php

namespace Forex\ApplicationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AllowUpdateStatus extends Constraint
{
	public $message = 'You are not allowed to update Status';

	public function getTargets()
	{
		return self::CLASS_CONSTRAINT;
	}

	public function validatedBy() {
		return "allowupdatestatus";
	}

}

