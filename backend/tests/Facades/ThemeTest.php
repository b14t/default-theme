<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Tests\Facades;

use ButterflyEffect\DefaultTheme\Facades\Theme;
use ButterflyEffect\DefaultTheme\Theme as ThemeInstance;
use ButterflyEffect\DefaultTheme\Theme\Collection;
use ButterflyEffect\DefaultTheme\Theme\CollectionInterface;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;
use function uniqid;

/**
 * Class ThemeTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Tests\Facades
 */
class ThemeTest extends TestCase
{
    /**
     * @var Theme The tested class.
     */
    private $fixture;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new Theme();
    }

    /**
     * Checks the instance getter by direct config hit.
     *
     * @return void
     */
    public function testGetInstanceByDefaultConfig(): void
    {
        $container = app();

        $container->instance(
            'config',
            new Repository([
                'butterfly-effect-theme.default' => $themeName = uniqid()
            ])
        );

        $container->instance(
            Collection::class,
            $collectionStub = $this->createMock(CollectionInterface::class)
        );

        $collectionStub
            ->method('getTheme')
            ->with($themeName)
            ->willReturn($theme = new ThemeInstance($themeName));

        $theme = Theme::getInstance();

        static::assertSame($theme, Theme::getInstance(), 'Wrong instance returned.');
    }

    /**
     * Checks the instance getter by the month config.
     *
     * @return void
     */
    public function testGetInstanceByMonthConfig(): void
    {
        $container = app();

        $container->instance(
            'config',
            new Repository([
                'butterfly-effect-theme.default' => uniqid(),
                'butterfly-effect-theme.by-month' => [date('n') => $themeName = uniqid()],
            ])
        );

        $container->instance(
            Collection::class,
            $collectionStub = $this->createMock(CollectionInterface::class)
        );

        $collectionStub
            ->method('getTheme')
            ->with($themeName)
            ->willReturn($theme = new ThemeInstance($themeName));

        $theme = Theme::getInstance();

        static::assertSame($theme, Theme::getInstance(), 'Wrong instance returned.');
    }

    /**
     * Checks the instance for the class.
     *
     * @return void
     */
    public function testInstance(): void
    {
        static::assertInstanceOf(Facade::class, $this->fixture);
    }
}
