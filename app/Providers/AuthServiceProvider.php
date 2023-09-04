<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Policies\BlogPolicy;
use App\Policies\LabelPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Label::class =>LabelPolicy::class,
        Blog::class=>BlogPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        
    }
}
