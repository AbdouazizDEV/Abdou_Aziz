<?php

namespace App\Providers;

use App\Repositories\Contracts\PromotionRepositoryInterface;
use App\Repositories\FirebasePromotionRepository;
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
use App\Repositories\MySQLPromotionRepository;
use App\Repositories\ReferentielRepository;
use App\Repositories\MySQLReferentielRepository;
use App\Repositories\Contracts\ApprenantRepositoryInterface;
use App\Repositories\MySQLApprenantRepository;
use App\Repositories\FirebaseApprenantRepository;
use Kreait\Firebase\Database\UrlBuilder\DefaultUrlBuilder;
use App\Repositories\MySQLNotesRepository;
use App\Repositories\FirebaseNotesRepository;
use App\Repositories\Contracts\NotesRepositoryInterface;
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
        $this->app->bind(ReferentielRepositoryInterface::class, MySQLReferentielRepository::class);
        if (env('PROMOTION_DATA_SOURCE', 'mysql') === 'firebase') {
            $this->app->bind(PromotionRepositoryInterface::class, FirebasePromotionRepository::class);
        } else {
            $this->app->bind(PromotionRepositoryInterface::class, MySQLPromotionRepository::class);
        }
        
        $this->app->bind(ApprenantRepositoryInterface::class, function ($app) {
            return env('APPRENANT_DATA_SOURCE', 'mysql') === 'firebase'
                ? new FirebaseApprenantRepository()
                : new MySQLApprenantRepository();
        });
        // Choisir la source de données pour Apprenants (MySQL ou Firebase)
        $this->app->bind(ApprenantRepositoryInterface::class, function () {
            if (env('APPRENANT_DATA_SOURCE', 'mysql') === 'firebase') {
                return new FirebaseApprenantRepository();
            }
            return new MySQLApprenantRepository();
        });

        // Choisir la source de données pour Notes (MySQL ou Firebase)
        $this->app->bind(NotesRepositoryInterface::class, function () {
            if (env('NOTE_DATA_SOURCE', 'mysql') === 'firebase') {
                return new FirebaseNotesRepository();
            }
            return new MySQLNotesRepository();
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
