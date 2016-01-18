<?php

namespace Forex\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="forexOfac")
 */
class Ofac
{

	public function __toString()
	{
		return (string)$this->getFirstname() . ' ' . $this->getLastname();
	}

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=200, nullable=true)
	 */
	private $firstname;

	/**
	 * @ORM\Column(type="string", length=200, nullable=true)
	 */
	private $lastname;

	/**
	 * @ORM\Column(type="string", length=40, nullable=true)
	 */
	private $type;


	/**
	 * @ORM\Column(type="json_array",  nullable=true)
	 */
	private $details;


	/**
	 * @var datetime $created
	 *
	 * @ORM\Column(type="datetime",  nullable=true)
	 */
	private $created;

	/**
	 * @var datetime $updated
	 *
	 * @ORM\Column(type="datetime",  nullable=true)
	 */
	private $updated;

	/**
	 * @var User $createdBy
	 *
	 * @ORM\ManyToOne(targetEntity="Forex\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
	 */
	private $createdBy;

	/**
	 * @var User $updatedBy
	 *
	 * @ORM\ManyToOne(targetEntity="Forex\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
	 */
	private $updatedBy;


	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set firstname
	 *
	 * @param string $firstname
	 * @return Ofac
	 */
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;

		return $this;
	}

	/**
	 * Get firstname
	 *
	 * @return string
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * Set lastname
	 *
	 * @param string $lastname
	 * @return Ofac
	 */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;

		return $this;
	}

	/**
	 * Get lastname
	 *
	 * @return string
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * Set type
	 *
	 * @param string $type
	 * @return Ofac
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * Get type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set details
	 *
	 * @param array $details
	 * @return Ofac
	 */
	public function setDetails($details)
	{
		$this->details = $details;

		return $this;
	}

	/**
	 * Get details
	 *
	 * @return array
	 */
	public function getDetails()
	{
		return $this->details;
	}

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 * @return Ofac
	 */
	public function setCreated($created)
	{
		$this->created = $created;

		return $this;
	}

	/**
	 * Get created
	 *
	 * @return \DateTime
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * Set updated
	 *
	 * @param \DateTime $updated
	 * @return Ofac
	 */
	public function setUpdated($updated)
	{
		$this->updated = $updated;

		return $this;
	}

	/**
	 * Get updated
	 *
	 * @return \DateTime
	 */
	public function getUpdated()
	{
		return $this->updated;
	}

	/**
	 * Set createdBy
	 *
	 * @param \Forex\UserBundle\Entity\User $createdBy
	 * @return Ofac
	 */
	public function setCreatedBy(\Forex\UserBundle\Entity\User $createdBy = null)
	{
		$this->createdBy = $createdBy;

		return $this;
	}

	/**
	 * Get createdBy
	 *
	 * @return \Forex\UserBundle\Entity\User
	 */
	public function getCreatedBy()
	{
		return $this->createdBy;
	}

	/**
	 * Set updatedBy
	 *
	 * @param \Forex\UserBundle\Entity\User $updatedBy
	 * @return Ofac
	 */
	public function setUpdatedBy(\Forex\UserBundle\Entity\User $updatedBy = null)
	{
		$this->updatedBy = $updatedBy;

		return $this;
	}

	/**
	 * Get updatedBy
	 *
	 * @return \Forex\UserBundle\Entity\User
	 */
	public function getUpdatedBy()
	{
		return $this->updatedBy;
	}
}
