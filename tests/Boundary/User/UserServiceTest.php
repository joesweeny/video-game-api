<?php

namespace App\Boundary\User;

use App\Domain\User\Persistence\UserRepository;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid;

class UserServiceTest extends TestCase
{
    /**
     * @var UserRepository|ObjectProphecy
     */
    private $repository;
    /**
     * @var UserService
     */
    private $service;

    public function setUp(): void
    {
        $this->repository = $this->prophesize(UserRepository::class);
        $this->service = new UserService($this->repository->reveal(), new UserPresenter());
    }

    public function test_getAllUsers_returns_an_array_of_scalar_user_objects()
    {
        $users = [
            new User(Uuid::fromString('f83aea35-162f-4fd2-9bb0-574947792402'), 'Joe Sweeny', 'joe@email.com'),
            new User(Uuid::fromString('dc35715d-89be-4d8d-bbe4-2c72df7c10e0'), 'Hulk Hogan', 'hulk@hogan.com'),
        ];

        $this->repository->get()->willReturn($users);

        $fetched = $this->service->getAllUsers();

        $this->assertEquals('f83aea35-162f-4fd2-9bb0-574947792402', $fetched[0]->id);
        $this->assertEquals('Joe Sweeny', $fetched[0]->name);
        $this->assertEquals('dc35715d-89be-4d8d-bbe4-2c72df7c10e0', $fetched[1]->id);
        $this->assertEquals('Hulk Hogan', $fetched[1]->name);
    }
}
