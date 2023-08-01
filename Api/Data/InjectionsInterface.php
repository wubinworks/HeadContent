<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Api\Data;

interface InjectionsInterface
{
    public const INJECTIONS_ID = 'injections_id';
    public const NAME = 'name';
    public const ENABLED = 'enabled';
    public const STORE_IDS = 'store_ids';
    public const CUSTOMER_GROUPS = 'customer_groups';
	public const MATCH_MODE = 'match_mode';
	public const URI_PATTERN = 'uri_pattern';
	public const FULL_ACTION_NAME = 'full_action_name';
    public const START_DATETIME = 'start_datetime';
    public const END_DATETIME = 'end_datetime';
    public const CONTENT = 'content';
    public const UPDATE_AT = 'update_at';

    /**
     * Get injections_id
     * @return string|null
     */
    public function getInjectionsId();

    /**
     * Set injections_id
     * @param string $injectionsId
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setInjectionsId($injectionsId);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setName($name);

    /**
     * Get enabled
     * @return string|null
     */
    public function getEnabled();

    /**
     * Set enabled
     * @param string $enabled
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setEnabled($enabled);

    /**
     * Get store_ids
     * @return string|null
     */
    public function getStoreIds();

    /**
     * Set store_ids
     * @param string $storeIds
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setStoreIds($storeIds);
	
	/**
     * Get customer_groups
     * @return string|null
     */
    public function getCustomerGroups();

    /**
     * Set customer_groups
     * @param string $customerGroups
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setCustomerGroups($customerGroups);

	/**
     * Get match_mode
     * @return string|null
     */
    public function getMatchMode();

    /**
     * Set match_mode
     * @param string $matchMode
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setMatchMode($matchMode);
	
    /**
     * Get uri_pattern
     * @return string|null
     */
    public function getUriPattern();

    /**
     * Set uri_pattern
     * @param string $uriPattern
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setUriPattern($uriPattern);

	/**
     * Get full_action_name
     * @return string|null
     */
    public function getFullActionName();

    /**
     * Set full_action_name
     * @param string $fullActionName
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setFullActionName($fullActionName);
	
	/**
     * Get start_datetime
     * @return string|null
     */
    public function getStartDatetime();

    /**
     * Set start_datetime
     * @param string $startDatetime
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setStartDatetime($startDatetime);
	
	/**
     * Get end_datetime
     * @return string|null
     */
    public function getEndDatetime();

    /**
     * Set end_datetime
     * @param string $endDatetime
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setEndDatetime($endDatetime);
	
    /**
     * Get content
     * @return string|null
     */
    public function getContent();

    /**
     * Set content
     * @param string $content
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setContent($content);
	
	/**
     * Get update_at
     * @return string|null
     */
    public function getUpdateAt();

    /**
     * Set update_at
     * @param string $updateAt
     * @return \Wubinworks\InjectHead\Injections\Api\Data\InjectionsInterface
     */
    public function setUpdateAt($updateAt);
}
