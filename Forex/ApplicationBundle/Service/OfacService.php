<?php
/**
 * Created by PhpStorm.
 * User: vancleipicolli
 * Date: 18/01/2016
 * Time: 12:21
 */

namespace Forex\ApplicationBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Forex\ApplicationBundle\Entity\Ofac;
use Forex\ApplicationBundle\Entity\Client;
use Forex\ApplicationBundle\Entity\Beneficiary;


/**
 * Business Rules for Ofac List (Office of Foreign Assets Control), that is a list of people carries out its
 * activities against foreign states as well as a variety of problematic organizations and individuals deemed
 * to be a threat to U.S. national security.
 *
 * Clients and their beneficiaries should be validated against this list for every transaction (orders, exchanges, etc)
 *
 * Class OfacService
 * @package Forex\ApplicationBundle\Controller
 */

class OfacService
{

	protected $em;
	protected $container;

	public function __construct(EntityManager $em, Container $container)
	{
		$this->em = $em;
		$this->container = $container;
	}

	/**
	 * Check if the Client is into the Ofac list, searching by Firstname and/or Lastname
	 *
	 * @param Client $client
	 * @return mixed
	 */
	public function checkClientOfacList(Client $client) {

		$details = array($client->getFirstName(), $client->getLastName());

		return $this->searchOfacList($details);
	}

	/**
	 * Check if the Beneficiary is into the Ofac list, searching by Firstname, Lastname and/or Address
	 *
	 * @param Beneficiary $beneficiary
	 * @return mixed
	 */
	public function checkBeneficiaryOfacList(Beneficiary $beneficiary) {

		$details = array($beneficiary->getFirstName(), $beneficiary->getLastName(), $beneficiary->getAddress());

		return $this->searchOfacList($details);
	}

	/**
	 * Search the Ofac List for any valid array element value
	 *
	 * @param array $details
	 * @return bool
	 */
	public function searchIntoOfacList(array $details = array()) {

		if (count($details) > 0) {

			$query = $this->em->getRepository('ForexApplicationBundle:Ofac')
							  ->createQueryBuilder('o')
			                  ->select('count(o.id)');

			$query->where("o.type = :type");

			$containDetails = false;
			foreach ($details as $key => $detail) {

				if ($detail != '') {
					$query->andWhere("upper(o.details) like :detail_{$key}");
					$query->setParameter("detail_{$key}", '%' . strtoupper($detail) . '%');

					$containDetails = true;
				}
			}

			if ($containDetails == true) {

				return $query->getQuery()->getSingleScalarResult() > 0 ? true : false;

			} else {
				return false;
			}
		}

		return false;
	}

	/**
	 * Update the current Ofac List with the most up to date version
	 *
	 */
	public function updateOfac() {

		$xml = $this->retrieveMostUpToDateOfacList();

		if ($xml !== FALSE) {
			$connection = $this->em->getConnection();
			$platform = $connection->getDatabasePlatform();

			$connection->executeUpdate($platform->getTruncateTableSQL('forexOfac', true /* whether to cascade */));

			$this->em->getConfiguration()->getResultCacheImpl()->delete('AllOfac');

			foreach ($xml->sdnEntry as $sdnEntry) {

				$ofac = new Ofac();

				$ofac->setFirstname($sdnEntry->firstName);
				$ofac->setLastname($sdnEntry->lastName);
				$ofac->setType($sdnEntry->sdnType);
				$ofac->setDetails(json_encode($sdnEntry));

				$this->em->persist($ofac);
			}

			$this->em->flush();

			// Save Ofac List into database and use Cache Strategy (memcached)
			$this->em->getRepository('ForexApplicationBundle:Ofac')->createQueryBuilder('p')
				->getQuery()
				->useResultCache(true)->setResultCacheId('AllOfac');
		}
	}

	/**
	 * Retrieve the most up to date Ofac list available, and convert to simpleXML object
	 * @return \SimpleXMLElement
	 */
	public function retrieveMostUpToDateOfacList() {

		$url = "http://www.treasury.gov/ofac/downloads/sdn.xml";

		$ol = "/var/www/forex/data/sdn.xml";

		copy($url, $ol);

		$xml = simplexml_load_file($ol) or die("Error retrieving File: " . $ol);

		return $xml;
	}
}