<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ImportantNotes extends \Wubinworks\InjectHead\Block\Adminhtml\System\Config\AbstractField
{
    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return __(
            'You need to <a class="wubinworks-internal-link" href="%1" target="_blank">Clear Cache</a> for these settings to take effect!',
            $this->getUrl('adminhtml/cache/index')
        );
    }
}
