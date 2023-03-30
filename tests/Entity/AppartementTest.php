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
       

        $entityManager = self::$container->get('doctrine')->getManager();
        $entityManager->persist($appartement);
        $entityManager->flush();

        $this->assertNotNull($appartement->getId());
    }
}
