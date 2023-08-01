<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model;

use Magento\Framework\Model\AbstractModel;
use Wubinworks\InjectHead\Api\Data\InjectionsInterface;

class Injections extends AbstractModel implements InjectionsInterface
{
    /**
     * Wubinworks InjectHead cache tag
     */
    public const CACHE_TAG = 'wubinworks_injecthead_cache';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'wubinworks_injecthead';

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Wubinworks\InjectHead\Model\ResourceModel\Injections::class);
    }

    /**
     * @inheritDoc
     */
    public function getInjectionsId()
    {
        return $this->getData(self::INJECTIONS_ID);
    }

    /**
     * @inheritDoc
     */
    public function setInjectionsId($injectionsId)
    {
        return $this->setData(self::INJECTIONS_ID, $injectionsId);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getEnabled()
    {
        return $this->getData(self::ENABLED);
    }

    /**
     * @inheritDoc
     */
    public function setEnabled($enabled)
    {
        return $this->setData(self::ENABLED, $enabled);
    }

    /**
     * @inheritDoc
     */
    public function getStoreIds()
    {
        return $this->getData(self::STORE_IDS);
    }

    /**
     * @inheritDoc
     */
    public function setStoreIds($storeIds)
    {
        return $this->setData(self::STORE_IDS, $storeIds);
    }

	/**
     * @inheritDoc
     */
    public function getCustomerGroups()
    {
        return $this->getData(self::CUSTOMER_GROUPS);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerGroups($customerGroups)
    {
        return $this->setData(self::CUSTOMER_GROUPS, $customerGroups);
    }
	
	/**
     * @inheritDoc
     */
    public function getMatchMode()
    {
        return $this->getData(self::MATCH_MODE);
    }

    /**
     * @inheritDoc
     */
    public function setMatchMode($matchMode)
    {
        return $this->setData(self::MATCH_MODE, $matchMode);
    }
	
    /**
     * @inheritDoc
     */
    public function getUriPattern()
    {
        return $this->getData(self::URI_PATTERN);
    }

    /**
     * @inheritDoc
     */
    public function setUriPattern($uriPattern)
    {
        return $this->setData(self::URI_PATTERN, $uriPattern);
    }

	/**
     * @inheritDoc
     */
    public function getFullActionName()
    {
        return $this->getData(self::FULL_ACTION_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setFullActionName($fullActionName)
    {
        return $this->setData(self::FULL_ACTION_NAME, $fullActionName);
    }
	
	/**
     * @inheritDoc
     */
    public function getStartDatetime()
    {
        return $this->getData(self::START_DATETIME);
    }

    /**
     * @inheritDoc
     */
    public function setStartDatetime($startDatetime)
    {
        return $this->setData(self::START_DATETIME, $startDatetime);
    }
	
	/**
     * @inheritDoc
     */
    public function getEndDatetime()
    {
        return $this->getData(self::RND_DATETIME);
    }

    /**
     * @inheritDoc
     */
    public function setEndDatetime($endDatetime)
    {
        return $this->setData(self::END_DATETIME, $endDatetime);
    }
	
    /**
     * @inheritDoc
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * @inheritDoc
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }
	
	/**
     * @inheritDoc
     */
    public function getUpdateAt()
    {
        return $this->getData(self::UPDATE_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdateAt($updateAt)
    {
        return $this->setData(self::UPDATE_AT, $updateAt);
    }
}
