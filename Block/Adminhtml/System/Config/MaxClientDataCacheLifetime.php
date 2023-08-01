<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class MaxClientDataCacheLifetime extends \Wubinworks\InjectHead\Block\Adminhtml\System\Config\AbstractField
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
                'In seconds<br />The maximum duration for <strong>browsers</strong> to cache head content<br />Set to 0 to let browsers not cache(Performance impact, not recommanded)<br /><a class="wubinworks-internal-link" href="%1" target="_blank">`Configuration->General->Web->Default Cookie setting->Cookie Lifetime`</a> must be longer than this value',
                $this->getUrl(
                    'adminhtml/system_config/edit/section/web',
                    ['_fragment' => 'row_web_cookie_cookie_lifetime']
                )
            )
        );
        return $element->getElementHtml();
    }
}
