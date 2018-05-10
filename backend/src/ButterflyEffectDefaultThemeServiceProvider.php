<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme;

use ButterflyEffect\DefaultTheme\Theme\Collection;
use ButterflyEffect\DefaultTheme\Theme\Composer\CollectionDecorator;
use ButterflyEffect\DefaultTheme\Theme\Composer\Factory;
use const DIRECTORY_SEPARATOR;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

/**
 * Class ButterflyEffectDefaultThemeServiceProvider.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme
 */
class ButterflyEffectDefaultThemeServiceProvider extends ServiceProvider
{
    /**
     * Boots this package.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'b14t/default-theme');

        $this->publishes(
            [
                __DIR__ . DIRECTORY_SEPARATOR . 'config.php' => config_path('butterfly-effect-theme.php'),
            ],
            'config'
        );
    }

    /**
     * Registers the basic services for the butterfly effect themes.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . DIRECTORY_SEPARATOR . 'config.php',
            'butterfly-effect-theme'
        );

        $this->app->singleton(Factory::class, function (Container $app): Factory {
            return new Factory($app->make('validator'));
        });

        $this->app->singleton(Collection::class, function (): Collection {
            return new Collection();
        });

        $this->app->extend(Collection::class, function (Collection $collection, Container $app): CollectionDecorator {
            return new CollectionDecorator($collection, $app->make(Factory::class));
        });
    }
}
