<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Theme\Composer;

use ButterflyEffect\DefaultTheme\Theme;
use ButterflyEffect\DefaultTheme\Theme\Collection\AbstractDecorator;
use ButterflyEffect\DefaultTheme\Theme\CollectionInterface;
use Illuminate\Validation\ValidationException;

/**
 * Class CollectionDecorator.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Theme\Composer
 */
class CollectionDecorator extends AbstractDecorator
{
    /**
     * @var Factory The factory to load the themes.
     */
    private $factory;

    /**
     * CollectionDecorator constructor.
     *
     * @param CollectionInterface $baseCollection
     * @param Factory $factory
     */
    public function __construct(CollectionInterface $baseCollection, Factory $factory)
    {
        parent::__construct($baseCollection);

        $this->factory = $factory;
    }

    /**
     * Adds a theme loaded with its composer file.
     *
     * @param string $composerFile
     *
     * @throws ValidationException
     *
     * @return Theme
     */
    public function addWithComposer(string $composerFile): Theme
    {
        $this->getBaseCollection()->addTheme($theme = $this->factory->loadTheme($composerFile));

        return $theme;
    }
}
