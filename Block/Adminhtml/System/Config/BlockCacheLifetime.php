<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class BlockCacheLifetime extends \Wubinworks\InjectHead\Block\Adminhtml\System\Config\AbstractField
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
     * Retrieve element HTML markup
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->setComment(
            __(
                'In seconds<br />Leave it empty to use <a class="wubinworks-internal-link" href="%1" target="_blank">`Configuration->Advanced settings->System->Full Page Cache->TTL`</a> value<br />Set to 0 to disable Block HTML Cache',
                $this->getUrl(
                    'adminhtml/system_config/edit/section/system',
                    ['_fragment' => 'row_system_full_page_cache_ttl']
                )
            )
        );
        return $element->getElementHtml();
    }
}
