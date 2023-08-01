<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Controller\Adminhtml;

abstract class Injections extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Wubinworks::top_level';
	
	/**
     * Navigation array
     * @var string[]
     */
	protected $navArr;
	
	/**
     * Core registry
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
		$this->navArr = [__($this->extractVendorName(get_class($this)))];
		$this->navArr[] = __('Head Content');
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

	/**
     * Extract vendor name from specified block class name
     *
     * @param string $className
     * @return string
     */
    public static function extractVendorName($className)
    {
        if (!$className) {
            return '';
        }
        return explode('\\', $className)[0];
    }
	
    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
		foreach($this->navArr as $nav){
			$resultPage->addBreadcrumb($nav, $nav);
			$resultPage->getConfig()->getTitle()->prepend($nav);
		}
        return $resultPage;
    }
}
