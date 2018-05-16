<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Theme;

use ButterflyEffect\DefaultTheme\Theme;
use DomainException;

/**
 * The basic api for the collection.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Theme
 */
interface CollectionInterface
{
    /**
     * Adds the given theme to the collection.
     *
     * @param Theme $theme
     * @return CollectionInterface
     */
    public function addTheme(Theme $theme): CollectionInterface;

    /**
     * Returns the theme for the given name.
     *
     * @param string $themeName
     * @return Theme
     * @throws DomainException If the theme is missing.
     */
    public function getTheme(string $themeName): Theme;

    /**
     * Returns true if the theme with the given name exists.
     *
     * @param string $themeName
     * @return bool
     */
    public function hasTheme(string $themeName): bool;
}