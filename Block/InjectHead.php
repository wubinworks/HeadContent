<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block;

class InjectHead extends \Wubinworks\InjectHead\Block\FrontendBlock
{
	/**
     * Section Names(via di.xml)
     * @var array
     */
	protected $sectionNames;
	
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
	 * @param array $sectionNames
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = [],
        array $sectionNames = []
    ) {
        $this->sectionNames = $sectionNames;
        parent::__construct($context, $data);
    }
	
	/**
     * @return int
     */
    protected function _getCacheLifetime()
    {
        return $this->helper->getBlockCacheLifetime() ?? parent::_getCacheLifetime();
    }

    /**
     * @return string
     */
    public function getRequestUri()
    {
        return $this->getRequest()->getRequestUri();
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->getRequest()->getAlias(\Magento\Framework\UrlInterface::REWRITE_REQUEST_PATH_ALIAS);
    }

    /**
     * @return string
     */
    public function getPathInfo()
    {
        return $this->getRequest()->getPathInfo();
    }

    /**
     * @return string
     */
    public function getCanonicalUri()
    {
        $pos = strpos($this->getRequest()->getRequestUri(), '?');
        return $this->getRequest()->getPathInfo() . ($pos !== false ? substr($this->getRequest()->getRequestUri(), $pos) : '');
    }

    /**
     * @return string
     */
    public function getRequestString()
    {
        return $this->getRequest()->getRequestString();
    }

    /**
     * @return string
     */
    public function getRefererFullActionName($delimiter = '/')
    {
        return $this->getRequest()->getFullActionName($delimiter);
    }

    /**
     * @return string
     */
    public function base64Encode($str)
    {
        return $this->helper->getUrlEncoder()->encode($str);
    }

    /**
     * @return array
     */
    public function getSectionNames()
    {
        return array_values($this->sectionNames);
    }

    /**
     * Get url for InjectHead customer data ajax requests. Returns url with protocol matching used to request page.
     *
     * @param string $route
     * @return string InjectHead customer data url.
     */
    public function getInjectHeadDataUrl($route)
    {
        return $this->getUrl($route, [
            '_secure' => $this->getRequest()->isSecure()
        ]) . 'referer/' . $this->base64Encode($this->getRequestUri()) . '/'
            . 'refererFullActionName/' . $this->base64Encode($this->getFullActionName()) . '/';
    }

    /**
     * Get url for customer data ajax requests. Returns url with protocol matching used to request page.
     *
     * @param string $route
     * @return string Customer data url.
     */
    public function getCustomerDataUrl($route)
    {
        return $this->getUrl($route, ['_secure' => $this->getRequest()->isSecure()]);
    }
}
