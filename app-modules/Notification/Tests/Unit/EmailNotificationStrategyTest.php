<?

namespace AppModules\Notification\Tests\Unit;

use Mockery;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use AppModules\Notification\Services\EmailNotificationStrategy;

class EmailNotificationStrategyTest extends TestCase
{
    public function test_send_email_successful()
    {
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')->andReturn(new Response(200));

        $strategy = new EmailNotificationStrategy($client);
        $result = $strategy->send('recipient@example.com', 'Test Message');

        $this->assertTrue($result);
    }

    public function test_send_email_failed()
    {
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')->andThrow(new RequestException('Error', new Request('POST', '/')));

        $strategy = new EmailNotificationStrategy($client);
        $result = $strategy->send('recipient@example.com', 'Test Message');

        $this->assertFalse($result);
    }
}
