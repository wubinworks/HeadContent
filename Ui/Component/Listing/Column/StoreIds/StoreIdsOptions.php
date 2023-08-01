<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Ui\Component\Listing\Column\StoreIds;

/**
 * Store Ids Options for filters
 */
class StoreIdsOptions extends \Magento\Store\Ui\Component\Listing\Column\Store\Options
{
    /**
     * All Store Views value
     */
    public const ALL_STORE_VIEWS = '0';

    /**
     * Generate current options
     *
     * @return void
     */
    protected function generateCurrentOptions(): void
    {
        $websiteCollection = $this->systemStore->getWebsiteCollection();
        $groupCollection = $this->systemStore->getGroupCollection();
        $storeCollection = $this->systemStore->getStoreCollection();

        foreach ($websiteCollection as $website) {
            $groups = [];
            foreach ($groupCollection as $group) {
                if ($group->getWebsiteId() === $website->getId()) {
                    $stores = [];
                    foreach ($storeCollection as $store) {
                        if ($store->getGroupId() === $group->getId()) {
                            $stores[] = [
                                'label' => '[' . $store->getId() . ']' . $this->sanitizeName($store->getName()),
                                'value' => $store->getId(),
                            ];
                        }
                    }
                }
            }
            $this->currentOptions['All Store Views']['label'] = __('All Store Views');
            $this->currentOptions['All Store Views']['value'] = self::ALL_STORE_VIEWS;
            $this->currentOptions = array_merge($this->currentOptions, $stores);
        }
    }
}
