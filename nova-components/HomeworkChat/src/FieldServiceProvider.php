<?php

namespace CreaceptLms\HomeworkChat;

use CreaceptLms\HomeworkChat\Repositories\ChatMessageRepository;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('homework-chat', __DIR__.'/../dist/js/field.js');
            Nova::style('homework-chat', __DIR__.'/../dist/css/field.css');
        });


        $this->app->booted(function () {
            \Route::middleware(['nova'])
                ->prefix('nova-vendor/homework-chat')
                ->group(__DIR__ . '/../routes/api.php');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ChatMessageRepository::class);
    }
}