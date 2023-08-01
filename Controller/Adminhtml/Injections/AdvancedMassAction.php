<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Controller\Adminhtml\Injections;

class AdvancedMassAction extends \Wubinworks\InjectHead\Controller\Adminhtml\Injections
{
	/**
	 * Result page factory
	 * @var \Magento\Framework\View\Result\PageFactory $resultPageFactory
	 */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }
	
	/**
     * @return boolean
     */
    /*
	public function _isAllowed()
    {
        return true;
        //return $this->_authorization->isAllowed('Wubinworks::top_level');
    }
    */
	
    /**
     * Advanced mass action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
		$this->navArr[] = __('Head Content Advanced Mass Action dont use');
		$this->initPage($resultPage);
        return $resultPage;
    }
}
