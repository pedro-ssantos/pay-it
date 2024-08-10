<?

namespace AppModules\Notification\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use AppModules\Notification\Services\Interfaces\NotificationStrategyInterface;

class SmsNotificationStrategy implements NotificationStrategyInterface
{

    public function __construct(protected Client $client) {}

    public function send(string $recipient, string $message): bool
    {
        try {
            $response = $this->client->post('https://util.devi.tools/api/v1/notify', [
                'json' => [
                    'to' => $recipient,
                    'message' => $message,
                    'type' => 'sms',
                ],
            ]);

            return $response->getStatusCode() === 200;
        } catch (RequestException $e) {
            // Log the error or handle it as needed
            return false;
        }
    }
}
