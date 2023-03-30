<?php
namespace App\Tests\Entity;

use App\Entity\Appartement;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppartementTest extends KernelTestCase
{
    public function testCreateAndSave()
    {
        self::bootKernel();

        $appartement = new Appartement();
        $appartement->setAdresse('Test Name');
        $appartement->setComplement('Test Description');
        $appartement->setVille('Test ville');
        $appartement->setCodePostal('Test cp');
        $appartement->setLoyer(500);
        $appartement->setCharges(500);

        $entityManager = self::$container->get('doctrine')->getManager();
        $entityManager->persist($appartement);
        $entityManager->flush();

        $this->assertNotNull($appartement->getId());
    }
}
