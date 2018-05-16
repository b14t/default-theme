<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Tests\Theme\Composer;

use ButterflyEffect\DefaultTheme\Theme;
use ButterflyEffect\DefaultTheme\Theme\Composer\Factory;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function array_map;
use function basename;
use function file_get_contents;
use function glob;
use function json_decode;
use function uniqid;

/**
 * Class FactoryTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Tests\Theme\Composer
 */
class FactoryTest extends TestCase
{
    /**
     * @var Factory|void The tested class.
     */
    private $fixture;

    /**
     * @var ValidatorFactory|void|PHPUnit_Framework_MockObject_MockObject The stubbed factory.
     */
    private $validatorFactoryStub;

    /**
     * Returns file paths to tested composer files.
     *
     * @return array
     */
    public function getComposerFiles(): array
    {
        $files = glob(__DIR__ . '/_fixtures/*.json');

        return array_map(function (string $composerFile) {
            return [$composerFile];
        }, $files);
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->validatorFactoryStub = $this->createMock(ValidatorFactory::class);

        $this->fixture = new Factory($this->validatorFactoryStub);
    }

    /**
     * Checks if the theme is correctly loaded.
     *
     * @dataProvider getComposerFiles
     * @param string $composerFile
     *
     * @return void
     */
    public function testLoadTheme(string $composerFile): void
    {
        $access = PropertyAccess::createPropertyAccessor();
        $fileName = basename($composerFile);
        $json = json_decode(file_get_contents($composerFile), true);

        $this->validatorFactoryStub
            ->expects(static::once())
            ->method('make')
            ->with(
                $json,
                [
                    'extra.butterfly-effect.theme' => 'required|array',
                    'extra.butterfly-effect.theme.asset-path' => 'sometimes|string',
                    'extra.butterfly-effect.theme.css' => 'sometimes|array',
                    'extra.butterfly-effect.theme.js' => 'sometimes|array',
                    'name' => 'required'
                ]
            )
            ->willReturn($validatorStub = $this->createMock(Validator::class));

        $validationCall = $validatorStub
            ->expects(static::once())
            ->method('validate');

        if (!$access->getValue($json, '[name]') ||
            $access->getValue($json, '[extra][butterfly-effect][theme]')) {
            $this->expectException(ValidationException::class);

            $validationCall->willThrowException(new ValidationException(uniqid()));
        }

        static::assertInstanceOf(
            Theme::class,
            $theme = $this->fixture->loadTheme($composerFile),
            'The theme was not loaded for ' . $composerFile
        );

        $map = [
            '[name]' => ['name'],
            '[extra][butterfly-effect][theme][asset-path]' => ['assetPath', 'public'],
            '[extra][butterfly-effect][theme][css]' => ['cssFiles', []],
            '[extra][butterfly-effect][theme][js]' => ['jsFiles', []]
        ];

        foreach ($map as $field => $themeRules) {
            @list($property, $default) = $themeRules;

            $composerValue = $access->getValue($json, $field) ?? $default;

            static::assertSame(
                $composerValue,
                $theme->{'get' . ucfirst($property)}(),
                'Wrong value for ' . $composerFile . ' and field ' . $field
            );
        }
    }
}
