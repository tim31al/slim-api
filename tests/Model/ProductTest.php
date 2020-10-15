<?php

namespace Test\Model;

use App\Model\Product;
use DI\Container;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = require __DIR__ . '/../bootstrap.php';

    }

    /**
     * @covers
     */
    public function testCrud()
    {
        $product = new Product($this->container);
        $this->assertInstanceOf(Product::class, $product);

        $name = 'New test product';
        $description = 'New test product description';
        $price = 124.56;

        $product->save([
            'name' => $name,
            'description' => $description,
            'price' => $price
            ]);

        $this->assertSame($name, $product->getName());
        $this->assertSame($description, $product->getDescription());
        $this->assertSame($price, $product->getPrice());

        $name = 'Updated name';
        $description = 'Updated description';
        $price = 1.25;

        $product->update([
            'name' => $name,
            'description' => $description,
            'price' => $price
        ]);

        $this->assertSame($name, $product->getName());
        $this->assertSame($description, $product->getDescription());
        $this->assertSame($price, $product->getPrice());

        $rows = $product->delete();

        $this->assertSame(1, $rows);

    }

}
