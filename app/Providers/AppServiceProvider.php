<?php
 
namespace App\Providers;
 
use App\Repositories\Contracts\TextileRepositoryInterface;
use App\Repositories\Eloquent\TextileRepository;
use Illuminate\Support\ServiceProvider;
 
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding Interface ke Implementasi Eloquent
        // Setiap kali TextileRepositoryInterface di-resolve dari container,
        // Laravel akan otomatis memberikan instance TextileRepository.
        $this->app->bind(
            TextileRepositoryInterface::class,
            TextileRepository::class
        );
    }
 
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}