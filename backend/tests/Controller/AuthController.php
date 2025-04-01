<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();
        $client->request('POST', '/api/register', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'email' => 'badbunny@localhost.com',
            'password' => 'pitorrodecoco',
            'name' => 'Bad Bunny'
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testLogin()
    {
        $client = static::createClient();
        $client->request('POST', '/api/login', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'email' => 'tameimpala@localhost.com',
            'password' => 'isittru3?tellmen0w'
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testForgotPassword()
    {
        $client = static::createClient();
        $client->request('POST', '/api/forgot-password', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'email' => 'linkinpark@localhost.com'
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testResetPassword()
    {
        $client = static::createClient();
        $token = 'valid-reset-token';
        $client->request('POST', '/api/reset-password/' . $token, server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'password' => 'asanavitoria'
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
}