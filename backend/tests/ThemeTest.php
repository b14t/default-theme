<?php

declare(strict_types=1);

namespace ButterflyEffect\DefaultTheme\Tests;

use ButterflyEffect\DefaultTheme\Theme;
use PHPUnit\Framework\TestCase;
use function ucfirst;
use function uniqid;

/**
 * Class ThemeTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package ButterflyEffect\DefaultTheme\Tests
 */
class ThemeTest extends TestCase
{
    /**
     * @var Theme|void The tested class.
     */
    private $fixture;

    /**
     * Returns tests for the properties.
     *
     * @return array
     */
    public function getPropertyAsserts(): array
    {
        return [
            // name, new value, default value
            ['assetPath', uniqid(), 'public'],
            ['cssFiles', [uniqid()]],
            ['jsFiles', [uniqid()]],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new Theme(uniqid());
    }

    /**
     * Checks the name getter.
     *
     * @return void
     */
    public function testGetName()
    {
        $this->fixture = new Theme($name = uniqid());

        static::assertSame($name, $this->fixture->getName());
    }

    /**
     * Checks the getters and setters.
     *
     * @dataProvider getPropertyAsserts
     *
     * @param string $property
     * @param mixed $value
     * @param array $defaultValue
     *
     * @return void
     */
    public function testGettersAndSetters(string $property, $value, $defaultValue = []): void
    {
        static::assertSame(
            $defaultValue,
            $this->fixture->{'get' . ucfirst($property)}(),
            'Wrong default value for ' . $property
        );

        static::assertSame(
            $this->fixture,
            $this->fixture->{'set' . ucfirst($property)}($value),
            'Fluent interface broken for ' . $property
        );

        static::assertSame(
            $value,
            $this->fixture->{'get' . ucfirst($property)}(),
            'Value was not set for ' . $property
        );
    }
}
