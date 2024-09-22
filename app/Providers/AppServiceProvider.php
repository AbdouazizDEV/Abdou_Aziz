<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AuthService;
use App\Services\Contracts\AuthServiceInterface;
use App\Repositories\UserRepository;
use App\Repositories\FirebaseUserRepository;
use App\Services\UserService;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Kreait\Firebase\Database\UrlBuilder;
use App\Repositories\Contracts\ReferentielRepositoryInterface;
use App\Repositories\FirebaseReferentielRepository;
use App\Repositories\ReferentielRepository;

use Kreait\Firebase\Database\UrlBuilder\DefaultUrlBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind('user.repository', UserRepository::class);
        $this->app->bind('firebase.user.repository', FirebaseUserRepository::class);
        $this->app->bind(UserService::class, function ($app) {
            return new UserService(
                $app->make('user.repository'),
                $app->make('firebase.user.repository'),
                
            );
        });
         // Lier GuzzleHttp\ClientInterface à une instance de GuzzleHttp\Client
         $this->app->bind(ClientInterface::class, function ($app) {
            return new Client();
        });
        // Lier l'interface UriInterface à GuzzleHttp\Psr7\Uri
        $this->app->bind(UriInterface::class, function ($app) {
            return new Uri();  // Utilisation de GuzzleHttp\Psr7\Uri
        });
       // Lier UrlBuilder à l'implémentation par défaut
       $this->app->bind(UrlBuilder::class, function ($app) {
            $firebaseUrl = config('firebase.database_url'); // Assurez-vous que cette config existe
            //dd($firebaseUrl); // Cette ligne doit maintenant afficher une URL valide
            return UrlBuilder::create($firebaseUrl);  // Instanciation du UrlBuilder avec l'URL Firebase
        });
        // Liaison entre l'interface et l'implémentation concrète
        $this->app->bind(ReferentielRepositoryInterface::class, ReferentielRepository::class);

        $this->app->bind(ReferentielRepositoryInterface::class, function ($app) {
            $dataSource = env('USER_DATA_SOURCE', 'mysql');
        
            if ($dataSource === 'firebase') {
                return $app->make(FirebaseReferentielRepository::class); // Utiliser Firebase pour les référentiels
            } else {
                return $app->make(ReferentielRepository::class); // Utiliser MySQL pour les référentiels
            }
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
