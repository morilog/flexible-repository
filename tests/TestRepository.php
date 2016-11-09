<?php
namespace Morilog\FlexibleRepository\Tests;

use Illuminate\Container\Container;

class TestRepository extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserRepository
     */
    protected $repository;

    public function setUp()
    {
        parent::setUp();
        $this->repository = new InMemoryUserRepository(new Container());
    }

    public function testFind()
    {
        $user = $this->repository->find(1);

        $this->assertTrue($user['id'] === 1);
        $this->assertTrue($user['first_name'] === 'Andrew');

        $nonExistedUser = $this->repository->find(100);
        $this->assertNull($nonExistedUser);
    }

    public function testTakeSkip()
    {
        $users = $this->repository->take(30)->all();
        $this->assertEquals($users->count(), 30);

        $users = $this->repository->take(3)->all();
        $this->assertEquals($users->count(), 3);

        $users = $this->repository->take(3)->skip(3)->all();
        $this->assertEquals($users->count(), 3);
        $this->assertEquals($users->first()['id'], 4);

    }

    public function testFindBy()
    {
        $user = $this->repository->findBy('email', 'jgibsonq@list-manage.com');
        $this->assertEquals($user['id'], 27);
    }

    public function testDelete()
    {
        $user = $this->repository->find(1);
        $this->assertNotNull($user);

        $this->repository->delete(1);
        $user = $this->repository->find(1);
        $this->assertNull($user);
    }

    public function testPaginate()
    {
        $users = $this->repository->paginate(5);

        $this->assertEquals($users->perPage() , 5);
        $this->assertEquals($users->items()[0], $this->repository->find(1));
    }
}