<?php

declare(strict_types=1);

namespace MageOS\Blog\ViewModel\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use MageOS\Blog\Api\Data\PostInterface;
use MageOS\Blog\Model\Post\PostsByAssignmentProvider;

class RelatedPosts implements ArgumentInterface
{
    /**
     * @var PostInterface[]|null
     */
    private ?array $posts = null;

    public function __construct(
        private readonly Registry $registry,
        private readonly StoreManagerInterface $storeManager,
        private readonly PostsByAssignmentProvider $postsProvider,
    ) {
    }

    /**
     * @return PostInterface[]
     */
    public function getPosts(int $limit = 4): array
    {
        if ($this->posts !== null) {
            return $this->posts;
        }

        $product = $this->registry->registry('current_product');
        if (!$product instanceof ProductInterface || $product->getId() === null) {
            return $this->posts = [];
        }

        return $this->posts = $this->postsProvider->byProduct(
            (int) $product->getId(),
            (int) $this->storeManager->getStore()->getId(),
            $limit,
        );
    }
}
