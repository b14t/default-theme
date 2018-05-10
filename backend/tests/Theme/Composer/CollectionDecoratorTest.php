<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Tests\Theme\Composer;

use ButterflyEffect\DefaultTheme\Theme;
use ButterflyEffect\DefaultTheme\Theme\Collection\AbstractDecorator;
use ButterflyEffect\DefaultTheme\Theme\CollectionInterface;
use ButterflyEffect\DefaultTheme\Theme\Composer\CollectionDecorator;
use ButterflyEffect\DefaultTheme\Theme\Composer\Factory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use function uniqid;

class CollectionDecoratorTest extends TestCase
{
    /**
     * @var CollectionDecorator|void The tested class.
     */
    private $fixture;

    /**
     * @var CollectionInterface|PHPUnit_Framework_MockObject_MockObject|void The used collection.
     */
    private $collection;

    /**
     * @var Factory|PHPUnit_Framework_MockObject_MockObject|void The used factory.
     */
    private $factory;

    /**
     * Sets up the unit test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new CollectionDecorator(
            $this->collection = $this->createMock(CollectionInterface::class),
            $this->factory = $this->createMock(Factory::class)
        );
    }

    /**
     * Checks if a theme gets loaded with the composer factory.
     *
     * @return void
     */
    public function testAddWithComposer(): void
    {
        $this->collection
            ->expects(static::once())
            ->method('addTheme')
            ->with($theme = new Theme(uniqid()))
            ->willReturnSelf();

        $this->factory
            ->expects(static::once())
            ->method('loadTheme')
            ->with($composerFile = uniqid())
            ->willReturn($theme);

        static::assertSame(
            $theme,
            $this->fixture->addWithComposer($composerFile),
            'Fluent interface failed.'
        );
    }

    /**
     * Checks the instance of this class.
     *
     * @return void
     */
    public function testInstance(): void
    {
        static::assertInstanceOf(AbstractDecorator::class, $this->fixture);
    }
}
