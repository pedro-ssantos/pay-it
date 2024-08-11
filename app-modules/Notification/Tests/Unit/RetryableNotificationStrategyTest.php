<?php

namespace AppModules\Notification\Tests\Unit;

use Mockery;
use Tests\TestCase;
use AppModules\Notification\Services\Decorators\RetryableNotificationStrategy;
use AppModules\Notification\Services\Interfaces\NotificationStrategyInterface;

class RetryableNotificationStrategyTest extends TestCase
{
    public function test_send_with_retries_successful()
    {
        $strategyMock = Mockery::mock(NotificationStrategyInterface::class);
        $strategyMock->shouldReceive('send')->andReturn(false, false, true);

        $retryableStrategy = new RetryableNotificationStrategy($strategyMock, 3);
        $result = $retryableStrategy->send('recipient@example.com', 'Test Message');

        $this->assertTrue($result);
    }

    public function test_send_with_retries_failed()
    {
        $strategyMock = Mockery::mock(NotificationStrategyInterface::class);
        $strategyMock->shouldReceive('send')->andReturn(false, false, false);

        $retryableStrategy = new RetryableNotificationStrategy($strategyMock, 3);
        $result = $retryableStrategy->send('recipient@example.com', 'Test Message');

        $this->assertFalse($result);
    }
}
