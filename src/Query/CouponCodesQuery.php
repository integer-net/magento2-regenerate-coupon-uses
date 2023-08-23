<?php
declare(strict_types=1);

namespace IntegerNet\RegenerateCouponUses\Query;

use Magento\Framework\App\ResourceConnection;

class CouponCodesQuery
{
    private ?array $couponDataRows = null;

    public function __construct(
        private readonly ResourceConnection $resourceConnection
    ) {
    }

    /**
     * @return array|string[][]
     */
    public function getCouponCodesByQtyUsed(): array
    {
        $couponCodesByQty = [];
        foreach ($this->getUsedQtyGroupedByCouponCode() as $couponCode => $qty) {
            $couponCodesByQty[$qty][] = $couponCode;
        }
        return $couponCodesByQty;
    }

    /**
     * @return array|int[]
     */
    public function getUsedQtyGroupedByCouponCode(): array
    {
        $couponUsageByCodeAndCustomerId = [];
        foreach ($this->getCouponDataRows() as $row) {
            $couponCode = $row['coupon_code'];
            $customerId = $row['customer_id'] ?? 0;
            $couponUsageByCodeAndCustomerId[$couponCode][$customerId]
                = ($couponUsageByCodeAndCustomerId[$couponCode][$customerId] ?? 0) + 1;
        }

        return array_map(
            function (array $qtyByCustomerId): int {
                return array_sum($qtyByCustomerId);
            },
            $couponUsageByCodeAndCustomerId
        );
    }

    /**
     * @return array|int[][]
     */
    public function getUsedQtyGroupedByCouponIdAndCustomerId(): array
    {
        $couponUsageByCouponIdAndCustomerId = [];
        foreach ($this->getCouponDataRows() as $row) {
            $couponId = $row['coupon_id'];
            $customerId = $row['customer_id'] ?? 0;
            $couponUsageByCouponIdAndCustomerId[$couponId][$customerId]
                = ($couponUsageByCouponIdAndCustomerId[$couponId][$customerId] ?? 0) + 1;
        }

        return $couponUsageByCouponIdAndCustomerId;
    }

    /**
     * @return array|int[][]
     */
    public function getUsedQtyGroupedByRuleIdAndCustomerId(): array
    {
        $couponUsageByRuleIdAndCustomerId = [];
        foreach ($this->getCouponDataRows() as $row) {
            $ruleId = $row['rule_id'];
            $customerId = $row['customer_id'] ?? 0;
            $couponUsageByRuleIdAndCustomerId[$ruleId][$customerId]
                = ($couponUsageByRuleIdAndCustomerId[$ruleId][$customerId] ?? 0) + 1;
        }

        return $couponUsageByRuleIdAndCustomerId;
    }

    /**
     * @return array
     */
    private function getCouponDataRows(): array
    {
        if ($this->couponDataRows === null) {
            $connection = $this->resourceConnection->getConnection();

            $query = $connection->select()
                ->from(
                    ['order' => $connection->getTableName('sales_order')],
                    ['coupon_code', 'customer_id']
                )
                ->where(
                    'order.state NOT IN (\'canceled\')'
                )
                ->joinInner(
                    ['coupon' => $connection->getTableName('salesrule_coupon')],
                    'order.coupon_code = coupon.code',
                    ['rule_id', 'coupon_id']
                );

            $this->couponDataRows = $connection->fetchAll($query);
        }

        return $this->couponDataRows;
    }
}
