<?php
namespace LaccUser\Providers;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Jrean\UserVerification\UserVerificationServiceProvider;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\FilesystemCache;
use Illuminate\Support\ServiceProvider;
use LaccUser\Annotations\Mapping\Controller;
use LaccUser\Annotations\PermissionReader;
use LaccUser\Http\Controllers\UsersController;

class LaccUserServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->publishMigrationsAndSeeders();
        /** @var PermissionReader $reader */
        //$reader = app( PermissionReader::class );
        //dd($reader->getPermissions());
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register( UserVerificationServiceProvider::class );
        $this->app->register( RepositoryServiceProvider::class );
        $this->app->register( RouteServiceProvider::class );
        $this->app->register( AuthServiceProvider::class );
        $this->registerAnnotations();
        $this->app->bind( Reader::class, function () {
            return new CachedReader(
              new AnnotationReader(),
              new FilesystemCache( storage_path( 'framework/cache/doctrine-annotations' ) ),
              $debug = env( 'APP_DEBUG' )
            );
        } );
    }

    /**
     * Cria os autoload das annotations do doctrine
     */
    protected function registerAnnotations()
    {
        $loader = require __DIR__ . '/../../../vendor/autoload.php';
        AnnotationRegistry::registerLoader( [ $loader, 'loadClass' ] );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes( [
          __DIR__ . '/../Config/config.php' => config_path( 'laccuser.php' ),
        ], 'config' );
        $this->mergeConfigFrom(
          __DIR__ . '/../Config/config.php', 'laccuser'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath   = base_path( 'resources/views/modules/laccuser' );
        $sourcePath = __DIR__ . '/../resources/views';
        $this->publishes( [
          $sourcePath => $viewPath,
        ] );
        $this->loadViewsFrom( array_merge( array_map( function ( $path ) {
            return $path . '/modules/laccuser';
        }, \Config::get( 'view.paths' ) ), [ $sourcePath ] ), 'laccuser' );
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = base_path( 'resources/lang/modules/laccuser' );
        if ( is_dir( $langPath ) ) {
            $this->loadTranslationsFrom( $langPath, 'laccuser' );
        } else {
            $this->loadTranslationsFrom( __DIR__ . '/../resources/lang', 'laccuser' );
        }
    }

    /**
     * Register migrations and seed
     */
    public function publishMigrationsAndSeeders()
    {
        $sourcePathMigrations = __DIR__ . '/../database/migrations';
        $sourcePathSeeders    = __DIR__ . '/../database/seeders';
        $this->publishes( [
          $sourcePathMigrations => database_path( 'migrations' ),
        ], 'migrations' );
        $this->publishes( [
          $sourcePathSeeders => database_path( 'seeds' ),
        ], 'seeders' );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}