<?php

namespace Forex\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfacType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('firstname')
			->add('lastname')
			->add('type', 'choice', array(
				'choices' => array(
					Orders::RECEIVED_MODE_CASH => Orders::RECEIVED_MODE_CASH,
					Orders::RECEIVED_MODE_BANK_TRANSFER => Orders::RECEIVED_MODE_BANK_TRANSFER,
					Orders::RECEIVED_MODE_BANK_TRANSFER_OTHER => Orders::RECEIVED_MODE_BANK_TRANSFER_OTHER

				),
				'required' => true
			))
			->add('details');
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Forex\ApplicationBundle\Entity\Ofac',
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'ofac';
	}
}
