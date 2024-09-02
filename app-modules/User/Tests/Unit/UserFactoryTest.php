<?php

namespace AppModules\User\Tests\Unit;

use Mockery;
use Tests\TestCase;
use Mockery\MockInterface;
use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use AppModules\User\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AppModules\Wallet\Services\Interfaces\WalletCreatorInterface;
use AppModules\User\Database\Repositories\Eloquent\CommonUserRepository;
use AppModules\User\Database\Repositories\Eloquent\MerchantUserRepository;

class UserFactoryTest extends TestCase
{
    use RefreshDatabase;

    protected $commonUserRepositoryMock;
    protected $merchantUserRepositoryMock;
    protected $userFactory;
    protected $walletCreatorMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commonUserRepositoryMock = Mockery::mock(CommonUserRepository::class);
        $this->merchantUserRepositoryMock = Mockery::mock(MerchantUserRepository::class);
        $this->walletCreatorMock = Mockery::mock(WalletCreatorInterface::class);

        $this->userFactory = new UserFactory(
            $this->commonUserRepositoryMock,
            $this->merchantUserRepositoryMock,
            $this->walletCreatorMock
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

        $this->walletCreatorMock
            ->shouldReceive('createWalletForUser')
            ->once();

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

        $this->walletCreatorMock
            ->shouldReceive('createWalletForUser')
            ->once();

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
