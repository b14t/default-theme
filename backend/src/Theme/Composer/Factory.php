<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Theme\Composer;

use ButterflyEffect\DefaultTheme\Theme;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\ValidationException;

/**
 * Loads the composer json for a theme.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Theme\Composer
 */
class Factory
{
    /**
     * @var ValidatorFactory The used ValidatorFactory.
     */
    private $validatorFactory;

    /**
     * Factory constructor.
     *
     * @param ValidatorFactory $validatorFactory
     */
    public function __construct(ValidatorFactory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * Loads the composer json data for this package.
     *
     * @param string $composerFile
     * @throws ValidationException
     *
     * @return array
     */
    private function loadComposerJSON(string $composerFile): array
    {
        $composerJSON = json_decode(file_get_contents($composerFile), true) ?: [];

        $this->validateComposerScheme($composerJSON);

        return $composerJSON;
    }

    /**
     * Loads the theme for the given composer file.
     *
     * @param string $composerFile
     * @throws ValidationException
     *
     * @return Theme
     */
    public function loadTheme(string $composerFile): Theme
    {
        $json = $this->loadComposerJSON($composerFile);

        return $this->loadThemeByJSON($json);
    }

    /**
     * Loads the theme by its json.
     *
     * @param array $json
     * @return Theme
     */
    private function loadThemeByJSON(array $json): Theme
    {
        $theme = new Theme($themeName = $json['name']);

        $theme->setCssFiles(array_map(
            $parseAssetPath = function (string $path) use ($themeName): string {
                if (strpos($path, './') === 0) {
                    $path = url('vendor/' . $themeName . '/' . substr($path, 2));
                }

                return $path;
            },
            $json['extra']['butterfly-effect']['theme']['css'] ?? []
        ));

        $theme->setJsFiles(array_map(
            $parseAssetPath,
            $json['extra']['butterfly-effect']['theme']['js'] ?? []
        ));

        return $theme;
    }

    /**
     * Validates the required json data.
     *
     * @param array $composerJSON
     * @throws ValidationException
     *
     * @return void
     */
    private function validateComposerScheme(array $composerJSON): void
    {
        $validator = $this->validatorFactory->make($composerJSON, [
            'extra.butterfly-effect.theme' => 'required|array',
            'extra.butterfly-effect.theme.asset-path' => 'sometimes|string',
            'extra.butterfly-effect.theme.css' => 'sometimes|array',
            'extra.butterfly-effect.theme.js' => 'sometimes|array',
            'name' => 'required'
        ]);

        $validator->validate();
    }
}
