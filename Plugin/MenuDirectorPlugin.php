<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Plugin;

use Wubinworks\InjectHead\Helper\Data as Helper;

class MenuDirectorPlugin
{
	 /**
	 * Helper
     * @var Helper
     */
    protected $helper;

	/**
	 * Constructor
	 *
     * @param Helper $helper
     */
    public function __construct(
        Helper $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Modifies id in menu.xml in method input. For Wubinworks only
     * Modifies Wubinworks menu item titile to display Magento version incompatible string
	 *
     * @param \Magento\Backend\Model\Menu\Director\Director $subject
     * @param array $config
     * @param \Magento\Backend\Model\Menu\Builder $builder
     * @param \Psr\Log\LoggerInterface $logger
     * @return array
     */
    public function beforeDirect(
        \Magento\Backend\Model\Menu\Director\Director $subject,
        array $config,
        \Magento\Backend\Model\Menu\Builder $builder,
        \Psr\Log\LoggerInterface $logger
    ) {
        $resultConfig = [];
        foreach($config as $data) {
            if(preg_match('#^([^/]+)/MERGE/([^/]+)$#i', $data['id'], $matches)) {
                if(in_array($matches[1], array_column($config, 'id'))) {
                    continue;
                }
                $data['id'] = $matches[1];
            }
            $resultConfig[] = $data;
        }

        if(!$this->helper->isMagentoVersionCompatible()) {
            foreach($resultConfig as &$data) {
                if($data['id'] === 'Wubinworks_InjectHead::injecthead_section'
                    || $data['id'] === 'Wubinworks_InjectHead::injecthead_injections'
                    || $data['id'] === 'Wubinworks_InjectHead::injecthead_advancedmassaction'
                ) {
                    $data['title'] .= '(' . __('%1 Incompatible!', $this->helper->getMagentoVersion()) . ')';
                }
            }
        }

        return [
            $resultConfig,
            $builder,
            $logger
        ];
    }
}
