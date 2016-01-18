<?php

namespace Forex\ApplicationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Forex\UserBundle\Entity\User as User;


class AllowUpdateStatusValidator extends ConstraintValidator
{

	private $entityManager;

	private $container;

	public function __construct(EntityManager $entityManager, $security_context)
	{
		$this->entityManager = $entityManager;
		$this->container = $security_context;
	}


	public function validate($object, Constraint $constraint)
	{

		$user= $this->container->get('security.context')->getToken()->getUser();

		if ($user->getType() != User::TYPE_ADMINISTRATOR && $user->getType() != User::TYPE_MANAGER) {

			$new_value = $object->getStatus();

			$old_data = $this->entityManager
				->getUnitOfWork()
				->getOriginalEntityData($object);

			// $old_data is empty if we create a new NoDecreasingInteger object.
			if (is_array($old_data) and !empty($old_data)) {
				$old_value = $old_data['status'];

				if ($new_value != $old_value) {
					$this->context->buildViolation($constraint->message)
						->setParameter('%string%', $new_value)
						->atPath('status')
						->addViolation();
				}
			}
		}
	}
}
