<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme;

use ButterflyEffect\DefaultTheme\Theme\Collection;
use function dirname;
use Illuminate\Support\ServiceProvider;
use function public_path;
use ReflectionClass;
use ReflectionException;

/**
 * Provides the basic calls for a butterfly effect theme.
 *
 * @author blange <lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme
 */
abstract class ThemeProvider extends ServiceProvider
{
    /**
     * @var string|void Where to find the composer file for this theme.
     */
    private $composerFile;

    /**
     * @var string|void The root folder for this theme.
     */
    protected $themeRootFolder;

    /**
     * @var Theme The theme for this provider.
     */
    private $theme;

    /**
     * Boots the package.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishPublicFiles();
    }

    /**
     * Returns the file path to the composer file.
     *
     * @return string
     */
    public function getComposerFile(): string
    {
        if (!$this->composerFile) {
            $this->setComposerFile($this->loadComposerFile());
        }

        return $this->composerFile;
    }

    /**
     * Returns the theme of this package.
     *
     * @return Theme
     */
    protected function getTheme(): Theme
    {
        if (!$this->theme) {
            $this->setTheme($this->loadTheme());
        }

        return $this->theme;
    }

    /**
     * Returns the root folder of the theme.
     *
     * @throws ReflectionException
     *
     * @return string
     */
    private function getThemeRootFolder(): string
    {
        if (!$this->themeRootFolder) {
            $reflection = new ReflectionClass($this);

            $this->themeRootFolder = realpath(dirname($reflection->getFileName()) . '/../..') . DIRECTORY_SEPARATOR;
        }

        return $this->themeRootFolder;
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    private function loadComposerFile(): string
    {
        $themeRootFolder = $this->getThemeRootFolder();

        $composerFile = $themeRootFolder . 'composer.json';

        return $composerFile;
    }

    /**
     * Loads the theme by the bundles composer file.
     *
     * @return Theme
     */
    private function loadTheme(): Theme
    {
        return $this->app->make(Collection::class)->addWithComposer($this->getComposerFile());
    }

    /**
     * Publishes the public files of this theme.
     *
     * @return void
     */
    private function publishPublicFiles(): void
    {
        $theme = $this->getTheme();
        $assetFolder = $this->getThemeRootFolder() . $theme->getAssetPath();

        $this->publishes(
            [$assetFolder => public_path('vendor/' . $theme->getName())],
            'public'
        );
    }

    /**
     * Sets the theme for this package.
     *
     * @param Theme $theme
     *
     * @return ThemeProvider
     */
    private function setTheme(Theme $theme): ThemeProvider
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Sets the composer file.
     *
     * @param string $composerFile
     * @return ThemeProvider
     */
    public function setComposerFile(string $composerFile): self
    {
        $this->composerFile = $composerFile;

        return $this;
    }
}
