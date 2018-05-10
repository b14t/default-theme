<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Tests;

use ButterflyEffect\DefaultTheme\ButterflyEffectDefaultThemeServiceProvider;
use ButterflyEffect\DefaultTheme\Theme\Collection;
use ButterflyEffect\DefaultTheme\Theme\Composer\CollectionDecorator;
use ButterflyEffect\DefaultTheme\Theme\Composer\Factory;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory as ViewFactory;
use PHPUnit\Framework\TestCase;
use function config_path;
use function realpath;

/**
 * Class ButterflyEffectDefaultThemeServiceProviderTest.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Tests
 */
class ButterflyEffectDefaultThemeServiceProviderTest extends TestCase
{
    /**
     * @var ButterflyEffectDefaultThemeServiceProvider The tested class.
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
        $this->fixture = new ButterflyEffectDefaultThemeServiceProvider(
            $this->application = new Application(__DIR__)
        );
    }

    public function testBoot()
    {
        $this->application->instance('view', $viewStub = $this->createMock(ViewFactory::class));

        $viewStub
            ->expects(static::once())
            ->method('addNamespace')
            ->with('b14t/default-theme', realpath(__DIR__ . '/../src/resources/views'));

        $this->fixture->boot();

        static::assertSame(
            [
                realpath(__DIR__ . '/../src/config.php') => config_path('butterfly-effect-theme.php')
            ],
            ButterflyEffectDefaultThemeServiceProvider::pathsToPublish(ButterflyEffectDefaultThemeServiceProvider::class)
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
     * Checks if the services are registered correctly.
     *
     * @return void
     */
    public function testRegister(): void
    {
        $this->application->instance('validator', $this->createMock(ValidatorFactory::class));
        $this->application->instance('config', new Repository());

        $this->fixture->register();

        $map = [
            Factory::class => Factory::class,
            Collection::class => CollectionDecorator::class
        ];

        foreach ($map as $call => $instanceType) {
            static::assertInstanceOf($instanceType, $this->application->make($call));
        }

        static::assertSame('', config('butterfly-effect-theme.default'));
        static::assertSame([], config('butterfly-effect-theme.by-month'));
    }
}
