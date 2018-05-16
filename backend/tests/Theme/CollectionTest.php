<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Theme\Tests;

use ButterflyEffect\DefaultTheme\Theme;
use ButterflyEffect\DefaultTheme\Theme\Collection;
use ButterflyEffect\DefaultTheme\Theme\CollectionInterface;
use DomainException;
use PHPUnit\Framework\TestCase;
use function uniqid;

/**
 * Class CollectionTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Theme\Tests
 */
class CollectionTest extends TestCase
{
    /**
     * @var Collection The tested class.
     */
    private $fixture;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new Collection();
    }

    /**
     * Checks the fluent interface for the adder.
     *
     * @return void
     */
    public function testAddThemeFluent(): void
    {
        static::assertSame($this->fixture, $this->fixture->addTheme($this->createMock(Theme::class)));
    }

    /**
     * Checks the complete process for the registration of a theme.
     *
     * @return void
     */
    public function testCheckCompleteThemeRegistration(): void
    {
        static::assertFalse($this->fixture->hasTheme($name = uniqid()), 'The checker should return false.');

        $this->fixture->addTheme($theme = new Theme($name));

        static::assertTrue(
            $this->fixture->hasTheme($name),
            'The checker should return true after registration.'
        );

        static::assertSame($theme, $this->fixture->getTheme($name), 'The theme instance should be returned.');
    }

    /**
     * Checks if an exception is thrown, if a non registered theme is called.
     *
     * @return void
     */
    public function testGetThemeExceptionOnEmpty(): void
    {
        $this->expectException(DomainException::class);

        $this->fixture->getTheme(uniqid());
    }

    /**
     * The checker should return false on default.
     *
     * @return void
     */
    public function testHasThemeDefault(): void
    {
        static::assertFalse($this->fixture->hasTheme(uniqid()));
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
