<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block\Adminhtml\Messages;

use Magento\Security\Model\ResourceModel\AdminSessionInfo\Collection as AdminSessionInfoCollection;
use Magento\Backend\Model\UrlInterface;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Wubinworks\InjectHead\Helper\Data as Helper;

class MagentoVersionMessage implements \Magento\Framework\Notification\MessageInterface
{
	/**
     * Backend url
     * @var UrlInterface
     */
    protected $backendUrl;
	
	/**
     * Admin session info collection
     * @var AdminSessionInfoCollection
     */
    private $adminSessionInfoCollection;
	
	/**
     * Auth session
     * @var AuthSession
     */
    private $authSession;
	
    /**
     * Helper
     * @var Helper
     */
    public $helper;

	/**
	 * Constructor
	 *
	 * @param \Magento\Security\Model\ResourceModel\AdminSessionInfo\Collection $adminSessionInfoCollection
	 * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Magento\Backend\Model\Auth\Sessiont $authSession
     * @param \Wubinworks\InjectHead\Helper\Data $helper
     */
    public function __construct(
        AdminSessionInfoCollection $adminSessionInfoCollection,
        UrlInterface $backendUrl,
        AuthSession $authSession,
        Helper $helper
    ) {
        $this->authSession = $authSession;
        $this->backendUrl = $backendUrl;
        $this->adminSessionInfoCollection = $adminSessionInfoCollection;
        $this->helper = $helper;
    }

	/**
     * @return string
     */
    public function getText()
    {
        $message = __('WARNING: Magento Version %1 is not compatible with Wubinworks_InjectHead. Main functions have been disabled automatically', $this->helper->getMagentoVersion()) . '<br />' .
            __(
                'More details <a class="wubinworks-external-link" href="%1" target="_blank" rel="noopener noreferrer">here</a>',
                Helper::getProjectUrl('system_requirements')
            );
        return $message;
    }

	/**
     * @return string
     */
    public function getIdentity()
    {
        return 'POPUP_' . md5('Wubinworks_InjectHead_Magento_Version' . $this->authSession->getUser()->getLogdate());
    }

	/**
     * @return boolean
     */
    public function isDisplayed()
    {
        if($this->helper->isMagentoVersionCompatible()) {
            return false;
        }
        return true;
    }

	/**
     * @return int
     */
    public function getSeverity()
    {
        return \Magento\Framework\Notification\MessageInterface::SEVERITY_CRITICAL;
    }
}
