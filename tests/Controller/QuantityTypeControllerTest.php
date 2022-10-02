<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuantityTypeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/quantity-type/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des types de quantité');
        $this->assertSelectorTextContains('#items_total', '8 types de quantité trouvés');
        $this->assertCount(8, $crawler->filter('tbody tr'));
    }
}
