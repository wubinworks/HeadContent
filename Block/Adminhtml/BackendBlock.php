<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block\Adminhtml;

use Magento\Framework\DataObject\IdentityInterface;

abstract class BackendBlock extends \Magento\Backend\Block\Template implements IdentityInterface
{
	/**
     * Helper factory
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_helperFactory;
	
	/**
     * Default helper
     * @var Helper
     */
    public $helper;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
		$this->_helperFactory = \Magento\Framework\App\ObjectManager::getInstance();
        $defaultHelperClassName = '\\' . str_replace('_', '\\', $this->getModuleName()) . '\\Helper\\' . 'Data';
        $this->helper = class_exists($defaultHelperClassName) ? $this->helper($defaultHelperClassName) : null;
        parent::__construct($context, $data);
    }

    /**
	 * Block html cache related settings
     * @return void
     */
    final protected function _construct()
    {
        parent::_construct();
        $cacheData = [
            'cache_lifetime' => $this->_getCacheLifetime(),
            'cache_tags' => $this->_getCacheTags(),
            'cache_key'  => $this->_getCacheKey()
        ];
		//var_dump($cacheData);die;
        $this->addData($cacheData);
    }

    /**
	 * For IdentityInterface
     * @return string[]
     */
    public function getIdentities()
    {
        return [$this->_getCacheKey()];
    }

	/**
     * @return int
     */
    final protected function _getCacheLifetime()
    {
        return 7200;
    }
	
	/**
     * @return string
     */
    protected function _getBlockName()
    {
		//$this->getNameInLayout() can be null
        return $this->getNameInLayout() ?? str_replace('\\', '_', strtolower(get_class($this)));
    }
	
	/**
     * @return string
     */
    protected function _getCacheKey()
    {
        $identities = [
            'block_name' => $this->_getBlockName(),
            'store_id' => $this->getArea() == 'frontend' ? $this->_storeManager->getStore()->getId() : 'admin',
            'uri_hash' => dechex(crc32($this->_request->getRequestUri()))
        ];
        if($this->_request->getPost('page_number', null)) {
            $identities['page_number'] = $this->_request->getPost('page_number');
        }
        return implode('_', array_values($identities));
    }
	
	/**
     * @return string[]
     */
    protected function _getCacheTags()
    {
        return [$this->_getCacheKey()];
    }
	
    /**
     * Get helper singleton
     *
     * @param string $className
     * @return \Magento\Framework\App\Helper\AbstractHelper
     * @throws \LogicException
     */
    public function helper($className)
    {
        $helper = $this->_helperFactory->get($className);
        if (false === $helper instanceof \Magento\Framework\App\Helper\AbstractHelper) {
            throw new \LogicException($className . ' doesn\'t extends Magento\Framework\App\Helper\AbstractHelper');
        }

        return $helper;
    }
}
