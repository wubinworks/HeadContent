<?php
/**
 * Copyright © Wubinworks All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Observer\Frontend;

class LayoutLoadBefore implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
		$layout = $observer->getData('layout');
		$layout->getUpdate()->addUpdate(
			'<body>     
				<referenceBlock name="head.additional">
					<block class="Wubinworks\InjectHead\Block\HeadContent" name="wubinworks_injecthead_block_headcontent"/>
				</referenceBlock>
			</body>'
		);
    }
}

