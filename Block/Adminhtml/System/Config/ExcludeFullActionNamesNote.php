<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ExcludeFullActionNamesNote extends \Wubinworks\InjectHead\Block\Adminhtml\System\Config\AbstractField
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
     * Remove scope label
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return 'cms/noroute/index' . '<br />' .
            'category/catelog/*' . ' or ' . 'category/catelog' . '<br />' .
            'account/*/*' . ' or ' . 'account' . '<br />' .
            __(
                'More <a class="wubinworks-external-link" href="%1" target="_blank" rel="noopener noreferrer">here</a>',
                $this->helper->getProjectUrl('excluded_full_action_name')
            );
    }
}
