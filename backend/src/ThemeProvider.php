<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\ServiceProvider;
use function GuzzleHttp\json_decode;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use ReflectionClass;
use ReflectionException;

/**
 * Provides the basic calls for a butterfly effect theme.
 *
 * @author blange <lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme
 */
class ThemeProvider extends ServiceProvider
{
    /**
     * @var string|void Where to find the composer file for this theme.
     */
    protected $composerFile;

    /**
     * @var string|void The root folder for this theme.
     */
    protected $themeRootFolder;

    /**
     * @var array|void The json of the composer file.
     */
    private $composerJSON = null;

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
     * Publishes the public files of this theme.
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function publishPublicFiles(): void
    {
        $composerJSON = $this->getComposerJSON();
        $assetFolder = $this->getThemeRootFolder() .
            (@$composerJSON['extra']['butterfly-effect']['theme']['asset-folder'] ?: 'public');

        $this->publishes(
            [$assetFolder  => public_path('vendor/' . $this->app->make(Theme::class)->getName())],
            'public'
        );
    }

    /**
     * Returns the data of the composer.json file from this package.
     *
     * @throws ValidationException
     *
     * @return array
     */
    protected function getComposerJSON(): array
    {
        if ($this->composerJSON === null) {
            $this->composerJSON = [];

            $this->loadComposerJSON();
        }

        return $this->composerJSON;
    }

    /**
     * Loads the composer json data for this package.
     *
     * @throws ValidationException
     *
     * @return ThemeProvider
     */
    private function loadComposerJSON(): self
    {
        $composerJSON = json_decode(file_get_contents($this->getComposerFilePath()), true) ?: [];

        $this->validateComposerScheme($composerJSON);

        return $this->setComposerJSON($composerJSON);
    }

    /**
     * Returns the file path to the composer file.
     *
     * @return string
     */
    protected function getComposerFilePath(): string
    {
        if (!$this->composerFile) {
            $themeRootFolder = $this->getThemeRootFolder();

            $this->composerFile = $themeRootFolder . 'composer.json';
        }

        return $this->composerFile;
    }

    /**
     * Returns the root folder of the theme.
     *
     * @throws ReflectionException
     *
     * @return string
     */
    protected function getThemeRootFolder(): string
    {
        if (!$this->themeRootFolder) {
            $reflection = new ReflectionClass($this);

            $this->themeRootFolder = dirname($reflection->getFileName()) . '/../..' . DIRECTORY_SEPARATOR;
        }

        return $this->themeRootFolder;
    }

    /**
     * Validates the required json data.
     *
     * @param array $composerJSON
     * @throws ValidationException
     */
    private function validateComposerScheme(array $composerJSON): void
    {
        /** @var Validator $validator */
        $validator = ValidatorFacade::make($composerJSON, [
            'extra.butterfly-effect.theme' => 'required|array',
            'extra.butterfly-effect.theme.css' => 'sometimes|array',
            'extra.butterfly-effect.theme.js' => 'sometimes|array',
            'name' => 'required'
        ]);

        if (!$validator->passes()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Sets the json data of the composer file.
     *
     * @param array $composerJSON
     *
     * @return $this
     */
    private function setComposerJSON(array $composerJSON): self
    {
        $this->composerJSON = $composerJSON;

        return $this;
    }

    /**
     * Registers the theme as a service.
     *
     * @return void
     */
    public function register(): void
    {
        // TODO: Add factory.
        $this->app->singleton(Theme::class, function () {
            $composerJSON = $this->getComposerJSON();

            $theme = new Theme($themeName = $composerJSON['name']);

            $theme->setCssFiles(array_map(
                $parseAssetPath = function (string $path) use ($themeName): string {
                    if (strpos($path, './') === 0) {
                        $path = url('vendor/' . $themeName . '/' . substr($path, 2));
                    }

                    return $path;
                },
                $composerJSON['extra']['butterfly-effect']['theme']['css'] ?? []
            ));

            $theme->setJsFiles(array_map(
                $parseAssetPath,
                $composerJSON['extra']['butterfly-effect']['theme']['js'] ?? []
            ));

            return $theme;
        });
    }

    /**
     * Returns the theme name.
     *
     * @throws ValidationException
     *
     * @return string
     */
    protected function getThemeName(): string
    {
        return $this->getComposerJSON()['name'];
    }
}
