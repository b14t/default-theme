<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Theme\Collection;

use ButterflyEffect\DefaultTheme\Theme;
use ButterflyEffect\DefaultTheme\Theme\CollectionInterface;
use DomainException;

/**
 * A Basic decorator for the collections.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Theme\Collection
 */
abstract class AbstractDecorator implements CollectionInterface
{
    /**
     * @var CollectionInterface The used base collection.
     */
    private $baseCollection;

    /**
     * AbstractDecorator constructor.
     *
     * @param CollectionInterface $baseCollection
     */
    public function __construct(CollectionInterface $baseCollection)
    {
        $this->baseCollection = $baseCollection;
    }

    /**
     * Adds the given theme to the collection.
     *
     * @param Theme $theme
     * @return CollectionInterface
     */
    public function addTheme(Theme $theme): CollectionInterface
    {
        $this->baseCollection->addTheme($theme);

        return $this;
    }

    /**
     * Returns the base collection.
     *
     * @return CollectionInterface
     */
    public function getBaseCollection(): CollectionInterface
    {
        return $this->baseCollection;
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
        return $this->baseCollection->getTheme($themeName);
    }

    /**
     * Returns true if the theme with the given name exists.
     *
     * @param string $themeName
     * @return bool
     */
    public function hasTheme(string $themeName): bool
    {
        return $this->baseCollection->hasTheme($themeName);
    }
}
