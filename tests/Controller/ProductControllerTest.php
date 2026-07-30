<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProductControllerTest extends WebTestCase
{
    public function testCatalogueIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/produits');

        self::assertResponseIsSuccessful();
    }
}
