<?php

namespace AwdStudio\Tests\DI\Unit\Storage;

use AwdStudio\DI\Storage\Holder;
use AwdStudio\DI\Storage\ServiceHolder;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \AwdStudio\DI\Storage\Holder
 */
class HolderTest extends TestCase
{

    /**
     * Instance.
     *
     * @var \AwdStudio\DI\Storage\Holder
     */
    private $instance;

    /** @var string */
    private $name;


    /**
     * Settings up.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->name = 'test.name';
        $this->instance = new Holder($this->name);
    }

    /**
     * @covers ::__construct
     */
    public function test__construct()
    {
        $this->assertInstanceOf(ServiceHolder::class, $this->instance);
    }

    /**
     * @covers ::readArguments
     */
    public function testReadArguments()
    {
        $args = ['foo', 'bar', 'baz'];
        $this->instance->arguments($args);

        $this->assertSame($args, $this->instance->readArguments());
    }

    /**
     * @covers ::readFactory
     */
    public function testReadFactory()
    {
        $factory = 'factoryName';
        $this->instance->factory($factory, '');

        $this->assertSame($factory, $this->instance->readFactory());
    }

    /**
     * @covers ::factory
     */
    public function testFactory()
    {
        $holder = $this->instance->factory('', '');

        $this->assertInstanceOf(ServiceHolder::class, $holder);
    }

    /**
     * @covers ::readClass
     */
    public function testReadClass()
    {
        $this->instance->class(\stdClass::class);

        $this->assertSame(\stdClass::class, $this->instance->readClass());
    }

    /**
     * @covers ::readFactoryArguments
     */
    public function testReadFactoryArguments()
    {
        $args = ['foo', 'bar', 'baz'];
        $this->instance->factory('', '', $args);

        $this->assertSame($args, $this->instance->readFactoryArguments());
    }

    /**
     * @covers ::readCallableFactory
     */
    public function testReadCallableFactory()
    {
        $this->instance->fromCallable('phpinfo');

        $callableFactory = $this->instance->readCallableFactory();
        $this->assertTrue(\is_callable($callableFactory));
    }

    /**
     * @covers ::arguments
     */
    public function testArguments()
    {
        $holder = $this->instance->arguments([]);

        $this->assertInstanceOf(ServiceHolder::class, $holder);
    }

    public function typeProvider()
    {
        return [
            [new Holder(\stdClass::class), ServiceHolder::TYPE_CONSTRUCTOR],
            [(new Holder(\stdClass::class))->factory('', '', []), ServiceHolder::TYPE_FACTORY],
            [(new Holder(\stdClass::class))->fromCallable(function () {}), ServiceHolder::TYPE_CALLABLE],
        ];
    }

    /**
     * @covers ::type
     * @dataProvider typeProvider
     */
    public function testType($holder, $type)
    {
        $this->assertSame($type, $holder->type());
    }

    /**
     * @covers ::readFactoryMethod
     */
    public function testReadFactoryMethod()
    {
        $method = 'build';
        $this->instance->factory('', $method);

        $this->assertSame($method, $this->instance->readFactoryMethod());
    }

    /**
     * @covers ::class
     */
    public function testClass()
    {
        $holder = $this->instance->class(\stdClass::class);

        $this->assertInstanceOf(ServiceHolder::class, $holder);
    }

    /**
     * @covers ::fromCallable
     */
    public function testFromCallable()
    {
        $holder = $this->instance->fromCallable('phpinfo');

        $this->assertInstanceOf(ServiceHolder::class, $holder);
    }

    /**
     * @covers ::nameIs
     */
    public function testNameIs()
    {
        $this->assertTrue($this->instance->nameIs($this->name));
        $this->assertFalse($this->instance->nameIs('unknown.service'));

        $this->assertFalse($this->instance->nameIs(\stdClass::class));

        $instance = new Holder(\stdClass::class);
        $this->assertTrue($instance->nameIs(\stdClass::class));
    }

    /**
     * @covers ::classIs
     */
    public function testClassIs()
    {
        $this->assertTrue($this->instance->classIs($this->name));
        $this->assertFalse($this->instance->classIs('unknown.service'));

        $this->assertFalse($this->instance->classIs(\stdClass::class));

        $instance = new Holder(\stdClass::class);
        $this->assertTrue($instance->classIs(\stdClass::class));
    }

    /**
     * @covers ::id
     */
    public function testId()
    {
        $this->assertSame($this->name, $this->instance->id());
    }

}
