<?php

declare(strict_types=1);

namespace IntegerNet\RegenerateCouponUses\Console\Command;

use IntegerNet\RegenerateCouponUses\Command\UpdateSalesruleCouponUsageTimesUsed;
use IntegerNet\RegenerateCouponUses\Query\CouponCodesQuery;
use IntegerNet\RegenerateCouponUses\Command\UpdateSalesruleCouponTimesUsed;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegenerateCouponCodes extends Command
{
    public function __construct(
        private readonly State $state,
        private readonly CouponCodesQuery $couponCodesQuery,
        private readonly UpdateSalesruleCouponTimesUsed $updateSalesruleCouponTimesUsed,
        private readonly UpdateSalesruleCouponUsageTimesUsed $updateSalesruleCouponUsageTimesUsed
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('coupon:usage:regenerate');
        $this->setDescription('Regenerate coupon uses so coupons cannot be used twice. ');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->state->setAreaCode(Area::AREA_GLOBAL);

        $couponCodesByQtyUsed = $this->couponCodesQuery->getCouponCodesByQtyUsed();
        $this->updateSalesruleCouponTimesUsed->execute($couponCodesByQtyUsed);

        $usedQtyByCouponIdAndCustomerId = $this->couponCodesQuery->getUsedQtyGroupedByCouponIdAndCustomerId();
        $this->updateSalesruleCouponUsageTimesUsed->execute($usedQtyByCouponIdAndCustomerId);

        $output->writeln('Finished.');
    }
}
