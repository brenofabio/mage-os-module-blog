<?php

declare(strict_types=1);

namespace MageOS\Blog\Test\Unit\ViewModel\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Registry;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use MageOS\Blog\Api\Data\PostInterface;
use MageOS\Blog\Model\Post\PostsByAssignmentProvider;
use MageOS\Blog\ViewModel\Product\RelatedPosts;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RelatedPostsTest extends TestCase
{
    #[Test]
    public function returns_empty_when_current_product_is_missing(): void
    {
        $registry = $this->createMock(Registry::class);
        $registry->method('registry')->with('current_product')->willReturn(null);
        $provider = $this->createMock(PostsByAssignmentProvider::class);
        $provider->expects(self::never())->method('byProduct');

        $viewModel = new RelatedPosts(
            $registry,
            $this->createMock(StoreManagerInterface::class),
            $provider,
        );

        self::assertSame([], $viewModel->getPosts());
    }

    #[Test]
    public function returns_posts_assigned_to_current_product_and_store(): void
    {
        $product = $this->createMock(ProductInterface::class);
        $product->method('getId')->willReturn(42);

        $registry = $this->createMock(Registry::class);
        $registry->method('registry')->with('current_product')->willReturn($product);

        $store = $this->createMock(StoreInterface::class);
        $store->method('getId')->willReturn(3);
        $storeManager = $this->createMock(StoreManagerInterface::class);
        $storeManager->method('getStore')->willReturn($store);

        $posts = [$this->createMock(PostInterface::class)];
        $provider = $this->createMock(PostsByAssignmentProvider::class);
        $provider->expects(self::once())
            ->method('byProduct')
            ->with(42, 3, 4)
            ->willReturn($posts);

        $viewModel = new RelatedPosts($registry, $storeManager, $provider);

        self::assertSame($posts, $viewModel->getPosts());
        self::assertSame($posts, $viewModel->getPosts());
    }
}
