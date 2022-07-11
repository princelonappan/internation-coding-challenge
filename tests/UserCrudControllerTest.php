<?php
// tests/Controller/AdminControllerTest.php
namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserCrudControllerTest extends WebTestCase
{

    public function testVisitingWhileLoggedIn()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('john.doe@example.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the admin page
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(403);
    }
}