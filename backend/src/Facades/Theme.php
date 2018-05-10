<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Facades;

use function app;
use function array_key_exists;
use function config;
use function date;
use ButterflyEffect\DefaultTheme\Theme as ThemeInstance;
use ButterflyEffect\DefaultTheme\Theme\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * Helps you access the theme for your butterfly effect.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @method static string getName() Returns the name of this theme.
 * @package ButterflyEffect\DefaultTheme\Facades
 */
class Theme extends Facade
{
    /**
     * Returns the used theme.
     *
     * @return ThemeInstance
     */
    protected static function getFacadeAccessor(): ThemeInstance
    {
        $date = date('n');
        $theme = config('butterfly-effect-theme.default');
        $themesByDate = config('butterfly-effect-theme.by-month', []);

        if (array_key_exists($date, $themesByDate)) {
            $theme = $themesByDate[$date];
        }

        return app(Collection::class)->getTheme($theme);
    }

    /**
     * Returns the instance for the used theme.
     *
     * @return ThemeInstance
     */
    public static function getInstance(): ThemeInstance
    {
        return static::getFacadeAccessor();
    }
}