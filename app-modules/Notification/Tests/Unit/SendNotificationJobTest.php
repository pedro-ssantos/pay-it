<?php

namespace AppModules\Notification\Tests\Unit;

use Exception;
use Mockery;
use Tests\TestCase;
use AppModules\User\Models\User;
use AppModules\Wallet\Models\Wallet;
use Illuminate\Support\Facades\Queue;
use AppModules\User\Models\CommonUser;
use AppModules\User\Models\MerchantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AppModules\Notification\Jobs\SendNotificationJob;
use AppModules\Notification\Services\Factories\NotificationStrategyFactory;
use AppModules\Notification\Services\Interfaces\NotificationStrategyInterface;

class SendNotificationJobTest extends TestCase
{
    use RefreshDatabase;

    private static $emailCounter = 0;
    private static $cpfCounter = 10000000000;
    private static $cnpjCounter = 10000000000000;

    public function test_it_sends_notification_via_email()
    {
        $sender = $this->createUser();
        $receiver = $this->createUser();
        $amount = 100.00;

        $mockedStrategy = Mockery::mock(NotificationStrategyInterface::class);
        $mockedStrategy->shouldReceive('send')
            ->once()
            ->with($receiver->email, "Você recebeu uma transferência de R$ {$amount} de {$sender->name}.")
            ->andReturn(true);

        $mockedFactory = Mockery::mock(NotificationStrategyFactory::class);
        $mockedFactory->shouldReceive('make')
            ->once()
            ->with($receiver->notification_type)
            ->andReturn($mockedStrategy);

        $job = new SendNotificationJob($sender, $receiver, $amount);

        $job->handle($mockedFactory);
        $this->assertTrue(true);
    }

    public function test_it_releases_job_on_failure()
    {
        $sender = $this->createUser();
        $receiver = $this->createUser();
        $amount = 100.00;

        $mockedStrategy = Mockery::mock(NotificationStrategyInterface::class);
        $mockedStrategy->shouldReceive('send')
            ->once()
            ->andReturn(false);

        $mockedFactory = Mockery::mock(NotificationStrategyFactory::class);
        $mockedFactory->shouldReceive('make')
            ->once()
            ->with($receiver->notification_type)
            ->andReturn($mockedStrategy);

        $job = Mockery::mock(SendNotificationJob::class, [$sender, $receiver, $amount])->makePartial();
        $job->shouldAllowMockingProtectedMethods();
        $job->shouldReceive('release')
            ->once()
            ->with(1800);

        $job->handle($mockedFactory);

        $this->assertTrue(true);
    }

    public function test_job_is_dispatched()
    {
        Queue::fake();

        $sender = $this->createUser();
        $receiver = $this->createUser();
        $amount = 100.00;

        SendNotificationJob::dispatch($sender, $receiver, $amount);

        // Verifica se o job foi enfileirado
        Queue::assertPushed(SendNotificationJob::class, function ($job) use ($sender, $receiver, $amount) {
            $reflection = new \ReflectionClass($job);

            $senderProperty = $reflection->getProperty('sender');
            $senderProperty->setAccessible(true);
            $jobSender = $senderProperty->getValue($job);

            $receiverProperty = $reflection->getProperty('receiver');
            $receiverProperty->setAccessible(true);
            $jobReceiver = $receiverProperty->getValue($job);

            $amountProperty = $reflection->getProperty('amount');
            $amountProperty->setAccessible(true);
            $jobAmount = $amountProperty->getValue($job);

            return $jobSender->is($sender) && $jobReceiver->is($receiver) && $jobAmount === $amount;
        });
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function createUser($type = 'common')
    {
        self::$emailCounter++;
        $email = 'test' . self::$emailCounter . '@example.com';

        if ($type === 'common') {
            $user = new CommonUser();
            $user->cpf = self::$cpfCounter++;
        } else {
            $user = new MerchantUser();
            $user->cnpj = self::$cnpjCounter++;
        }

        $user->name = 'Test User';
        $user->email = $email;
        $user->password = bcrypt('password');
        $user->notification_type = 'email';
        $user->save();

        return $user;
    }

    protected function createWallet($userId, $balance = 100)
    {
        $wallet = new Wallet();
        $wallet->user_id = $userId;
        $wallet->balance = $balance;
        $wallet->save();

        return $wallet;
    }

    protected function createCommonUser()
    {
        return $this->createUser('common');
    }

    protected function createMerchantUser()
    {
        return $this->createUser('merchant');
    }
}
