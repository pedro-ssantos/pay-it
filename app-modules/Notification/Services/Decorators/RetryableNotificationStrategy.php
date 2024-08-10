<?

namespace AppModules\Notification\Services\Decorators;

use AppModules\Notification\Services\Interfaces\NotificationStrategyInterface;

class RetryableNotificationStrategy implements NotificationStrategyInterface
{
    protected NotificationStrategyInterface $strategy;
    protected int $retries;

    public function __construct(NotificationStrategyInterface $strategy, int $retries = 3)
    {
        $this->strategy = $strategy;
        $this->retries = $retries;
    }

    public function send(string $recipient, string $message): bool
    {
        $attempts = 0;

        while ($attempts < $this->retries) {
            if ($this->strategy->send($recipient, $message)) {
                return true;
            }

            $attempts++;
            // Backoff exponencial, por exemplo
            // sleep(pow(2, $attempts));
            sleep(1);
        }

        return false;
    }
}
