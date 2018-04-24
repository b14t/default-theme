<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme;

use Illuminate\Support\ServiceProvider;

class ButterflyEffectDefaultThemeServiceProvider extends ServiceProvider
{
    /**
     * Boots this package.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'b14t/default-theme');
    }
}
