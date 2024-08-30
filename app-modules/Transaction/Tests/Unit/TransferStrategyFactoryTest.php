<?php

namespace AppModules\Transaction\Test\Unit;

use Mockery;
use Tests\TestCase;
use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use AppModules\Wallet\Services\Interfaces\WalletServiceInterface;
use AppModules\Wallet\Services\Interfaces\BalanceValidatorInterface;
use AppModules\Transaction\Exceptions\InvalidTransferStrategyException;
use AppModules\Wallet\Repositories\Interfaces\WalletRepositoryInterface;
use AppModules\Transaction\Services\TrasnferStrategy\TransferStrategyFactory;
use AppModules\Transaction\Services\TrasnferStrategy\CommonToCommonTransferStrategy;
use AppModules\Transaction\Services\TrasnferStrategy\CommonToMerchantTransferStrategy;

class TransferStrategyFactoryTest extends TestCase
{
    protected $walletService;
    protected $walletRepository;
    protected $balanceValidator;
    protected $transferStrategyFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletService = Mockery::mock(WalletServiceInterface::class);
        $this->walletRepository = Mockery::mock(WalletRepositoryInterface::class);
        $this->balanceValidator = Mockery::mock(BalanceValidatorInterface::class);

        $this->app->instance(WalletServiceInterface::class, $this->walletService);
        $this->app->instance(WalletRepositoryInterface::class, $this->walletRepository);
        $this->app->instance(BalanceValidatorInterface::class, $this->balanceValidator);

        $this->transferStrategyFactory = new TransferStrategyFactory();
    }

    public function test_it_creates_common_to_merchant_strategy()
    {
        $sender = Mockery::mock(CommonUser::class);
        $receiver = Mockery::mock(MerchantUser::class);

        $strategy = $this->transferStrategyFactory->make($sender, $receiver);

        $this->assertInstanceOf(CommonToMerchantTransferStrategy::class, $strategy);
    }

    public function test_it_creates_common_to_common_strategy()
    {
        $sender = Mockery::mock(CommonUser::class);
        $receiver = Mockery::mock(CommonUser::class);


        $strategy = $this->transferStrategyFactory->make($sender, $receiver);

        $this->assertInstanceOf(CommonToCommonTransferStrategy::class, $strategy);
    }

    public function test_it_throws_exception_for_invalid_strategy()
    {
        $sender = Mockery::mock(MerchantUser::class);
        $receiver = Mockery::mock(CommonUser::class);

        $this->expectException(InvalidTransferStrategyException::class);
        $this->expectExceptionMessage('Invalid transfer strategy.');

        $this->transferStrategyFactory->make($sender, $receiver);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
