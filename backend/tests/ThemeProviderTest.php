<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Tests;

use ButterflyEffect\DefaultTheme\Tests\ThemeProvider\TestThemeProvider;
use ButterflyEffect\DefaultTheme\Theme;
use ButterflyEffect\DefaultTheme\Theme\Collection;
use ButterflyEffect\DefaultTheme\Theme\Composer\CollectionDecorator;
use ButterflyEffect\DefaultTheme\ThemeProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use PHPUnit\Framework\TestCase;
use function realpath;
use function uniqid;
use const DIRECTORY_SEPARATOR;

/**
 * Class ThemeProviderTest.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Tests
 */
class ThemeProviderTest extends TestCase
{
    /**
     * @var ThemeProvider The tested class.
     */
    private $fixture;

    /**
     * @var Container The used application class
     */
    private $application;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new TestThemeProvider(
            $this->application = new Application(__DIR__)
        );

        $this->application->instance('validator', $this->createMock(ValidatorFactory::class));
    }

    /**
     * Checks the boot of the method and which files are published as a result.
     *
     * @return void
     */
    public function testBoot(): void
    {
        $this->application->instance(
            Collection::class,
            $decoratorStub = $this->createMock(CollectionDecorator::class)
        );

        $decoratorStub
            ->expects(static::once())
            ->method('addWithComposer')
            ->willReturn($theme = $this->createMock(Theme::class));


        $theme
            ->expects(static::once())
            ->method('getAssetPath')
            ->willReturn($assetPath = uniqid());

        $theme
            ->expects(static::once())
            ->method('getName')
            ->willReturn($name = uniqid());

        $this->fixture->setComposerFile(__DIR__ . '/ThemeProvider/_fixtures/composer.json');

        $this->fixture->boot();

        static::assertSame(
            [
                (realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR . $assetPath) =>
                    public_path('vendor/' . $name)
            ],
            ThemeProvider::pathsToPublish(TestThemeProvider::class)
        );
    }

    /**
     * Checks the instance of the class.
     *
     * @return void
     */
    public function testInstance(): void
    {
        static::assertInstanceOf(ServiceProvider::class, $this->fixture);
    }

    /**
     * Checks the getter and setter for the cmoposer file.
     *
     * @return void
     */
    public function testSetAndGetComposerFile(): void
    {
        static::assertSame(
            'composer.json',
            basename($this->fixture->getComposerFile()),
            'Wrong default return.'
        );

        static::assertSame(
            $this->fixture,
            $this->fixture->setComposerFile($file = uniqid()),
            'Fluent interface broken.'
        );

        static::assertSame($file, $this->fixture->getComposerFile(), 'Not saved correctly.');
    }
}
