<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Tests\Theme\Collection;

use ButterflyEffect\DefaultTheme\Theme;
use ButterflyEffect\DefaultTheme\Theme\Collection\AbstractDecorator;
use ButterflyEffect\DefaultTheme\Theme\CollectionInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use function uniqid;

/**
 * Class AbstractDecoratorTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Tests\Theme\Collection
 */
class AbstractDecoratorTest extends TestCase
{
    /**
     * @var AbstractDecorator The tested class.
     */
    private $fixture;

    /**
     * @var CollectionInterface|PHPUnit_Framework_MockObject_MockObject|void The base collection.
     */
    private $collection;

    /**
     * Sets up the unittest.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = $this->getMockForAbstractClass(
            AbstractDecorator::class,
            [$this->collection = $this->createMock(CollectionInterface::class)]
        );
    }

    /**
     * Checks if the theme is added correctly.
     *
     * @return void
     */
    public function testAddTheme(): void
    {
        $this->collection
            ->expects(static::once())
            ->method('addTheme')
            ->with($theme = new Theme(uniqid()))
            ->willReturnSelf();

        static::assertSame($this->fixture, $this->fixture->addTheme($theme), 'Fluent interface failed.');
    }

    /**
     * Checks if the base collection is returned.
     *
     * @return void
     */
    public function testGetBaseCollection(): void
    {
        static::assertSame($this->collection, $this->fixture->getBaseCollection());
    }

    /**
     * Checks if the correct api is called.
     *
     * @return void
     */
    public function testGetTheme(): void
    {
        $this->collection
            ->expects(static::once())
            ->method('getTheme')
            ->with($themeName = uniqid())
            ->willReturn($theme = new Theme($themeName));

        static::assertSame($theme, $this->fixture->getTheme($themeName), 'Wrong return value.');
    }

    /**
     * Checks if the api is called correctly.
     *
     * @return void
     */
    public function testHasTheme(): void
    {
        $this->collection
            ->expects(static::once())
            ->method('hasTheme')
            ->with($themeName = uniqid())
            ->willReturn(true);

        static::assertTrue($this->fixture->hasTheme($themeName), 'Wrong return value.');
    }

    /**
     * Checks the instance of this class.
     *
     * @return void
     */
    public function testInstance(): void
    {
        static::assertInstanceOf(CollectionInterface::class, $this->fixture);
    }
}
