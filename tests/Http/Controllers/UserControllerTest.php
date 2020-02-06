<?php

namespace App\Http\Controllers;

use App\Boundary\Comment\CommentService;
use App\Boundary\User\UserService;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

class UserControllerTest extends TestCase
{
    /**
     * @var UserService|ObjectProphecy
     */
    private $userService;
    /**
     * @var CommentService|ObjectProphecy
     */
    private $commentService;
    /**
     * @var UserController
     */
    private $controller;

    public function setUp(): void
    {
        $this->userService = $this->prophesize(UserService::class);
        $this->commentService = $this->prophesize(CommentService::class);
        $this->controller = new UserController(
            $this->userService->reveal(),
            $this->commentService->reveal()
        );
    }

    public function test_list_returns_a_json_response_containing_scalar_user_data()
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

        $response = $this->controller->list();
        $body = json_decode($response->getContent());

        $expected = (object) [
            'users' => $users,
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expected, $body);
    }

    public function test_comments_returns_a_json_response_containing_scalar_comment_data()
    {
        $comments = [
            (object) [
                'id' => '80b5894b-25c2-4a0c-926b-0f476e92f788',
                'user_id' => '1da15c2e-f2fa-45ca-9c71-277428258931',
                'game_id' => 'e09313c7-b55f-471f-81c2-47d879ad8dfd',
                'text' =>  'Was hoping for something more',
            ],
            (object) [
                'id' => '48f76f41-79f7-4279-bb47-2b61df42e1b0',
                'user_id' => '1da15c2e-f2fa-45ca-9c71-277428258931',
                'game_id' => 'e09313c7-b55f-471f-81c2-47d879ad8dfd',
                'text' =>  'Pretty awesome',
            ],
        ];

        $this->commentService->getByUserId('1da15c2e-f2fa-45ca-9c71-277428258931')->willReturn($comments);

        $response = $this->controller->comments('1da15c2e-f2fa-45ca-9c71-277428258931');
        $body = json_decode($response->getContent());

        $expected = (object) [
            'comments' => $comments,
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expected, $body);
    }

    public function test_comments_returns_422_response_if_id_passed_is_an_invalid_uuid_string()
    {
        $this->commentService->getByUserId('1')->willThrow(new InvalidUuidStringException('Invalid uuid'));

        $response = $this->controller->comments('1');
        $body = json_decode($response->getContent());

        $expected = (object) [
            'error' => 'Invalid uuid',
        ];

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals($expected, $body);
    }
}
