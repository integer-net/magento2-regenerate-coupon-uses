<?php
declare(strict_types=1);

namespace IntegerNet\RegenerateCouponUses\Command;

use Magento\Framework\App\ResourceConnection;

class UpdateSalesruleCouponUsageTimesUsed
{
    public function __construct(
        private readonly ResourceConnection $resourceConnection
    ) {
    }

    /**
     * @param array|int[][] $usedQtyByCouponIdAndCustomerId
     */
    public function execute(array $usedQtyByCouponIdAndCustomerId): void
    {
        if (empty($usedQtyByCouponIdAndCustomerId)) {
            return;
        }

        $rowsToInsert = [];
        foreach ($usedQtyByCouponIdAndCustomerId as $couponId => $usedQtyByCustomerId) {
            foreach ($usedQtyByCustomerId as $customerId => $usedQty) {
                if ($customerId == 0) {
                    continue;
                }
                $rowsToInsert[] = [
                    'coupon_id' => $couponId,
                    'customer_id' => $customerId,
                    'times_used' => $usedQty,
                ];
            }
        }

        echo json_encode($rowsToInsert, JSON_PRETTY_PRINT);

        $connection = $this->resourceConnection->getConnection();

        $connection->insertArray(
            $connection->getTableName('salesrule_coupon_usage'),
            ['coupon_id', 'customer_id', 'times_used'],
            $rowsToInsert,
            \Magento\Framework\DB\Adapter\AdapterInterface::REPLACE
        );
    }
}
