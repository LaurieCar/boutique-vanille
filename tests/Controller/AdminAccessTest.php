<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AdminAccessTest extends WebTestCase
{
    public function testAdminIsRefusedWithoutRole(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        // pas connecté -> redirigé vers la page de connexion, pas d'accès au back-office
        self::assertResponseRedirects('/connexion');
    }
}
