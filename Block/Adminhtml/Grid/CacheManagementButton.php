<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block\Adminhtml\Grid;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class CacheManagementButton extends \Wubinworks\InjectHead\Block\Adminhtml\GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Cache Management'),
            'on_click' => sprintf("window.open('%s', '_blank', 'noreferrer');", $this->getUrl('adminhtml/cache/index')),
            'class' => 'wubinworks-internal-link action-secondary',
            'sort_order' => 20
        ];
    }
}
