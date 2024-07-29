<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GetCommentSWithRepliesService;
use App\Services\TagClosedService;
use App\Services\FileService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GetCommentSWithRepliesService::class, function() {
            return new GetCommentSWithRepliesService(25);
        });
        $this->app->bind(TagClosedValidationService::class, function() {
            return new TagClosedValidationService;
        });
        $this->app->bind(FileService::class, function() {
            return new FileService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
