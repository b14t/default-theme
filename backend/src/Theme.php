<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme;

class Theme
{
    /**
     * @var array The used css files.
     */
    private $cssFiles = [];

    /**
     * @var array The used javascript files.
     */
    private $jsFiles = [];

    /**
     * @var string The name of the theme.
     */
    private $name;

    /**
     * Theme constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the used css files.
     *
     * @return array
     */
    public function getCssFiles(): array
    {
        return $this->cssFiles;
    }

    /**
     * Sets the used css files.
     *
     * @param array $cssFiles
     *
     * @return Theme
     */
    public function setCssFiles(array $cssFiles): Theme
    {
        $this->cssFiles = $cssFiles;
        return $this;
    }

    /**
     * Returns the used javascript files.
     *
     * @return array
     */
    public function getJsFiles(): array
    {
        return $this->jsFiles;
    }

    /**
     * Sets the used javascript files.
     *
     * @param array $jsFiles
     *
     * @return Theme
     */
    public function setJsFiles(array $jsFiles): Theme
    {
        $this->jsFiles = $jsFiles;
        return $this;
    }

    /**
     * Returns the name of the theme.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}