<?php 

namespace OpenAiPackage\Providers;

use Illuminate\Support\ServiceProvider;

use OpenAiPackage\Services\OpenService;

class OpenAiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register OpenAI service provider
        $this->app->register(OpenAIServiceProvider::class);

        // Bind the OpenService
        $this->app->singleton('openservice', function ($app) {
            return new OpenService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Optional: Publish the OpenAI configuration if needed
        // $this->publishes([
        //     __DIR__ . '/../../config/openai.php' => config_path('openai.php'),
        // ], 'config');
    }
}

?>