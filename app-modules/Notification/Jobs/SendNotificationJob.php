<?php

namespace AppModules\Notification\Jobs;

use AppModules\User\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use AppModules\Notification\Services\Factories\NotificationStrategyFactory;

class SendNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User $sender,
        protected User $receiver,
        protected float $amount
    ) {}

    /**
     * Execute the job.
     */
    public function handle(NotificationStrategyFactory $factory): void
    {
        $message = "Você recebeu uma transferência de R$ {$this->amount} de {$this->sender->name}.";
        $strategy = $factory->make($this->receiver->notification_type);

        if (!$strategy->send($this->receiver->email, $message)) {
            throw new \Exception("Failed to send notification");
        }
    }
}
