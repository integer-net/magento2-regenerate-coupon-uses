<?php
declare(strict_types=1);

namespace IntegerNet\RegenerateCouponUses\Command;

use Magento\Framework\App\ResourceConnection;

class UpdateSalesruleCustomerTimesUsed
{
    public function __construct(
        private readonly ResourceConnection $resourceConnection
    ) {
    }

    /**
     * @param array|int[][] $usedQtyByRuleIdAndCustomerId
     */
    public function execute(array $usedQtyByRuleIdAndCustomerId): void
    {
        if (empty($usedQtyByRuleIdAndCustomerId)) {
            return;
        }

        $rowsToInsert = [];
        foreach ($usedQtyByRuleIdAndCustomerId as $ruleId => $usedQtyByCustomerId) {
            foreach ($usedQtyByCustomerId as $customerId => $usedQty) {
                if ($customerId == 0) {
                    continue;
                }
                $rowsToInsert[] = [
                    'rule_id' => $ruleId,
                    'customer_id' => $customerId,
                    'times_used' => $usedQty,
                ];
            }
        }

        $connection = $this->resourceConnection->getConnection();

        $connection->insertArray(
            $connection->getTableName('salesrule_customer'),
            ['rule_id', 'customer_id', 'times_used'],
            $rowsToInsert,
            \Magento\Framework\DB\Adapter\AdapterInterface::REPLACE
        );
    }
}
