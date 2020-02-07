<?php

namespace App\Boundary\Comment;

use App\Domain\Comment\Comment;
use App\Domain\Comment\Persistence\CommentRepository;
use App\Domain\User\Persistence\UserRepository;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class CommentServiceTest extends TestCase
{
    /**
     * @var CommentRepository|ObjectProphecy
     */
    private $repository;
    /**
     * @var UserRepository|ObjectProphecy
     */
    private $userRepository;
    /**
     * @var CommentService
     */
    private $service;

    public function setUp(): void
    {
        $this->repository = $this->prophesize(CommentRepository::class);
        $this->userRepository = $this->prophesize(UserRepository::class);
        $this->service = new CommentService(
            $this->repository->reveal(),
            $this->userRepository->reveal(),
            new CommentPresenter()
        );
    }

    public function test_getByUserId_returns_an_array_of_scalar_comment_objects()
    {
        $userId = Uuid::fromString('1da15c2e-f2fa-45ca-9c71-277428258931');

        $comments = [
            new Comment(
                Uuid::fromString('80b5894b-25c2-4a0c-926b-0f476e92f788'),
                $userId,
                Uuid::fromString('e09313c7-b55f-471f-81c2-47d879ad8dfd'),
                'Was hoping for something more'
            ),
            new Comment(
                Uuid::fromString('48f76f41-79f7-4279-bb47-2b61df42e1b0'),
                $userId,
                Uuid::fromString('e09313c7-b55f-471f-81c2-47d879ad8dfd'),
                'Pretty awesome'
            )
        ];

        $this->userRepository->getById($userId)->willReturn(new User($userId, 'Joe', 'joe@email.com'));
        $this->repository->getByUserId($userId)->willReturn($comments);

        $fetched = $this->service->getByUserId('1da15c2e-f2fa-45ca-9c71-277428258931');

        $this->assertEquals('80b5894b-25c2-4a0c-926b-0f476e92f788', $fetched[0]->id);
        $this->assertEquals('Joe', $fetched[0]->user);
        $this->assertEquals('Was hoping for something more', $fetched[0]->text);

        $this->assertEquals('48f76f41-79f7-4279-bb47-2b61df42e1b0', $fetched[1]->id);
        $this->assertEquals('Joe', $fetched[1]->user);
        $this->assertEquals('Pretty awesome', $fetched[1]->text);
    }

    public function test_getByUserId_throws_exception_if_invalid_uuid_string_provided_as_argument()
    {
        $this->expectException(InvalidUuidStringException::class);
        $this->service->getByUserId('1');
    }
}
