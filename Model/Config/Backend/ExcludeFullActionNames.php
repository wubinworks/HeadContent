<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model\Config\Backend;

use Magento\Framework\Exception\LocalizedException;

/**
 * Backend model for system.xml exclude_full_action_names
 */
class ExcludeFullActionNames extends \Magento\Framework\App\Config\Value
{
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * Validate Full Action Name format
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        if(is_null($value)) {
            return parent::beforeSave();
        }
        $value = preg_replace('/\s+/u', '', $value);
        if(!strlen($value)) {
            return parent::beforeSave();
        }
        $msg = [];
        $invalidFormat = false;
        foreach(explode(',', $value) as $fullActionName) {
            if(!(
                preg_match('#^[A-Za-z0-9_\-]{1,}/[A-Za-z0-9]{1,}/[A-Za-z0-9]{1,}$#', $fullActionName)
                || preg_match('#^[A-Za-z0-9_\-]{1,}/[A-Za-z0-9]{1,}$#', $fullActionName)
                || preg_match('#^[A-Za-z0-9_\-]{1,}$#', $fullActionName)
                || preg_match('#^\*/[A-Za-z0-9]{1,}/[A-Za-z0-9]{1,}$#', $fullActionName)
                || preg_match('#^[A-Za-z0-9_\-]{1,}/\*/[A-Za-z0-9]{1,}$#', $fullActionName)
                || preg_match('#^[A-Za-z0-9_\-]{1,}/[A-Za-z0-9]{1,}/\*$#', $fullActionName)
                || preg_match('#^\*/\*/[A-Za-z0-9]{1,}$#', $fullActionName)
                || preg_match('#^\*/[A-Za-z0-9]{1,}/\*$#', $fullActionName)
                || preg_match('#^[A-Za-z0-9_\-]{1,}/\*/\*$#', $fullActionName)
            )) {
                $invalidFormat = true;
                $msg[] = __('"%1" is invalid format.', $fullActionName);
            }
        }
        if($invalidFormat) {
            $msg = implode(__('<br />', ['br']), $msg);
            throw new LocalizedException(__($msg));
        } else {
            return parent::beforeSave();
        }
    }
}
