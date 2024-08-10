<?

namespace AppModules\Notification\Providers;

use Illuminate\Support\ServiceProvider;
use AppModules\Notification\Services\SmsNotificationStrategy;
use AppModules\Notification\Services\EmailNotificationStrategy;
use AppModules\Notification\Services\Factories\NotificationStrategyFactory;
use AppModules\Notification\Services\Decorators\RetryableNotificationStrategy;
use AppModules\Notification\Services\Interfaces\NotificationStrategyInterface;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Registrar NotificationStrategyFactory no service container
        $this->app->singleton(NotificationStrategyFactory::class, function ($app) {
            return new NotificationStrategyFactory(
                $app->make(SmsNotificationStrategy::class),
                $app->make(EmailNotificationStrategy::class),
                $app->make(RetryableNotificationStrategy::class)
            );
        });
    }
}
