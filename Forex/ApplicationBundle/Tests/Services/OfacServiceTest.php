<?php

namespace Forex\ApplicationBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Forex\ApplicationBundle\Entity\Beneficiaries;
use Forex\ApplicationBundle\Entity\Clients;

class OfacServiceTest extends WebTestCase
{
    static $container;
    static $ofacService;

    public static function setUpBeforeClass()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        self::$container = $kernel->getContainer();

        self::$ofacService = self::$container->get('ofac');
    }

    public function testCheckClientOfacList() {

        $clientTest = new Clients();

        $clientTest->setFirstName('Osama');
        $clientTest->setLastName('Laden');

        self::$ofacService->checkClientOfacList($clientTest);

        $this->assertTrue(self::$ofacService->checkClientOfacList($clientTest), 'Check someone that exists on Ofac List');

        $clientTest = new Clients();

        $clientTest->setFirstName('Vanclei');
        $clientTest->setLastName('Picolli');

        self::$ofacService->checkClientOfacList($clientTest);

        $this->assertFalse(self::$ofacService->checkClientOfacList($clientTest), 'Check someone that does not exist on Ofac List');
    }

    public function testCheckBeneficiaryOfacList() {

        $beneficiaryTest = new Beneficiaries();

        $beneficiaryTest->setFirstName('Osama');
        $beneficiaryTest->setLastName('Laden');

        self::$ofacService->checkbeneficiaryOfacList($beneficiaryTest);

        $this->assertTrue(self::$ofacService->checkbeneficiaryOfacList($beneficiaryTest), 'Check someone that exists on Ofac List');

        $beneficiaryTest = new Beneficiaries();

        $beneficiaryTest->setFirstName('Vanclei');
        $beneficiaryTest->setLastName('Picolli');
        $beneficiaryTest->setAddress('London');

        self::$ofacService->checkbeneficiaryOfacList($beneficiaryTest);

        $this->assertFalse(self::$ofacService->checkbeneficiaryOfacList($beneficiaryTest), 'Check someone that does not exist on Ofac List');
    }

    public function testRetrieveMostUpToDateOfacList() {

        $this->assertTrue(self::$ofacService->retrieveMostUpToDateOfacList() !== FALSE, 'Check file is an Ofac List');
    }
}
