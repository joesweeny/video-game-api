<?php

namespace App\Http\Controllers;

use App\Boundary\User\UserService;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class UserControllerTest extends TestCase
{
    /**
     * @var UserService|ObjectProphecy
     */
    private $userService;
    /**
     * @var UserController
     */
    private $controller;

    public function setUp(): void
    {
        $this->userService = $this->prophesize(UserService::class);
        $this->controller = new UserController($this->userService->reveal());
    }

    public function test_get_returns_a_json_response_containing_user_scalar_user_data()
    {
        $users = [
            (object) [
                'id' => 'f83aea35-162f-4fd2-9bb0-574947792402',
                'name' => 'Joe Sweeny'
            ],
            (object) [
                'id' => 'dc35715d-89be-4d8d-bbe4-2c72df7c10e0',
                'name' => 'Hulk Hogan'
            ],
        ];

        $this->userService->getAllUsers()->willReturn($users);

        $response = $this->controller->get();
        $body = json_decode($response->getContent());
        
        $expected = (object) [
            'users' => $users,
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expected, $body);
    }
}
