<?php

namespace Forex\ApplicationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;



class AllowZeroCpfValidator extends ConstraintValidator
{

	private $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function validate($value, Constraint $constraint)
	{
		$aSettings = $this->entityManager->getRepository('ForexApplicationBundle:Parameters')->createQueryBuilder('p')
			->getQuery()
			->getResult();

		$allowed = '';

		foreach ($aSettings as $parameter) {
			if ($parameter->getName() == 'AllowZeroCPF') {
				$allowed = $parameter->getValue();
			}
		}

		if ($allowed == 'No' && (is_numeric($value) == true && intval($value) == 0)) {
			$this->context->buildViolation($constraint->message)
				->setParameter('%string%', $value)
				->addViolation();
		}
	}
}
