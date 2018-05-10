<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Theme;

use ButterflyEffect\DefaultTheme\Theme;
use DomainException;

/**
 * The theme collection.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Theme
 */
class Collection implements CollectionInterface
{
    /**
     * @var Theme[] The used collections.
     */
    private $themes = [];

    /**
     * Adds the given theme to the collection.
     *
     * @param Theme $theme
     * @return CollectionInterface
     */
    public function addTheme(Theme $theme): CollectionInterface
    {
        $this->themes[$theme->getName()] = $theme;

        return $this;
    }

    /**
     * Returns the theme for the given name.
     *
     * @param string $themeName
     * @return Theme
     * @throws DomainException If the theme is missing.
     */
    public function getTheme(string $themeName): Theme
    {
        if (!$this->hasTheme($themeName)) {
            throw new DomainException("The theme {$themeName} is not registered.");
        }

        return $this->themes[$themeName];
    }

    /**
     * Returns true if the theme with the given name exists.
     *
     * @param string $themeName
     * @return bool
     */
    public function hasTheme(string $themeName): bool
    {
        return array_key_exists($themeName, $this->themes);
    }
}
