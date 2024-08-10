<?php

namespace AppModules\User\Tests\Unit;

use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;
use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use AppModules\User\Factories\UserFactory;
use AppModules\User\Database\Repositories\Eloquent\CommonUserRepository;
use AppModules\User\Database\Repositories\Eloquent\MerchantUserRepository;

class UserFactoryTest extends TestCase
{
    protected $commonUserRepositoryMock;
    protected $merchantUserRepositoryMock;
    protected $userFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commonUserRepositoryMock = Mockery::mock(CommonUserRepository::class);
        $this->merchantUserRepositoryMock = Mockery::mock(MerchantUserRepository::class);

        $this->userFactory = new UserFactory(
            $this->commonUserRepositoryMock,
            $this->merchantUserRepositoryMock
        );
    }

    public function test_it_creates_a_common_user_when_cpf_is_provided()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('secret'),
            'cpf' => '12345678901',
        ];

        $this->commonUserRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn(new CommonUser($data));

        $user = $this->userFactory->create($data);

        $this->assertInstanceOf(CommonUser::class, $user);
        $this->assertEquals('12345678901', $user->cpf);
    }

    public function test_it_creates_a_merchant_user_when_cnpj_is_provided()
    {
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('secret'),
            'cnpj' => '12345678901234',
        ];

        $this->merchantUserRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn(new MerchantUser($data));

        $user = $this->userFactory->create($data);

        $this->assertInstanceOf(MerchantUser::class, $user);
        $this->assertEquals('12345678901234', $user->cnpj);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
