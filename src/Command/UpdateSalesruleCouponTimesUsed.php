<?php
declare(strict_types=1);

namespace IntegerNet\RegenerateCouponUses\Command;

use Magento\Framework\App\ResourceConnection;

class UpdateSalesruleCouponTimesUsed
{
    public function __construct(
        private readonly ResourceConnection $resourceConnection
    ) {
    }

    /**
     * @param array|int[] $couponCodesByQtyUsed
     */
    public function execute(array $couponCodesByQtyUsed): void
    {
        if (empty($couponCodesByQtyUsed)) {
            return;
        }
        $connection = $this->resourceConnection->getConnection();

        foreach ($couponCodesByQtyUsed as $qtyUsed => $couponCodes) {
            $connection->update(
                $connection->getTableName('salesrule_coupon'),
                ['times_used' => $qtyUsed],
                ['code IN (?)' => $couponCodes]
            );
        }
    }
}
