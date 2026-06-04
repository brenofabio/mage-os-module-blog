<?php

declare(strict_types=1);

namespace MageOS\Blog\Model\UrlKeyGenerator;

// phpcs:ignore Magento2.NamingConvention.InterfaceName.WrongInterfaceName -- tracked for rename in a follow-up
interface CollisionChecker
{
    public function isTaken(string $urlKey, string $entityType, ?int $storeId, ?int $excludingEntityId = null): bool;
}
