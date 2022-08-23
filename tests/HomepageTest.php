<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
    public function testHomepageIsWellDisplayed(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }

    /**
     * @dataProvider helloWorldPageProvider
     */
    public function testHelloWorldPageIsWellDisplayed(string $expectedName, ?string $displayedName = null): void
    {
        if($displayedName === null) {
            $displayedName = ltrim($expectedName, '/');
        }

        $client = static::createClient();
        $client->request('GET', '/hello'.$expectedName);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $displayedName);
    }

    public function helloWorldPageProvider(): array
    {
        return [
            'only letters' => ['/Adrien'],
            'only numbers' => ['/1234567890'],
            'empty' => ['', 'World'],
        ];
    }
}
