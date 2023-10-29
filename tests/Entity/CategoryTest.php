<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;

use App\Entity\Category;

class CategoryTest extends TestCase
{
    private Category $category;

    protected function setUp(): void
    {
        $this->category = new Category();
    }
    
    public function testGetLabel(): void
    {
        $response = $this->category->setLabel('CATEGORYLabel');

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals('CATEGORYLabel', $this->category->getLabel());
    }
    
    public function testGetParentCategoryId(): void
    {
        $response = $this->category->setParentCategoryId(0);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals(0, $this->category->getParentCategoryId());
    }
}
