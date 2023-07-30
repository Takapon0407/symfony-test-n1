<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LuckyControllerTest extends WebTestCase
{
    public function testLuckyController(): void
    {
        $client = static::createClient();
        $response = $client->request('GET', '/lucky/number');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'User1');
        $this->assertSelectorTextContains('body', 'Message from User1');
    }
}