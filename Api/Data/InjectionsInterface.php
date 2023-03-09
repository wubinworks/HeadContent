<?php
/**
 * Copyright © Wubinworks All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Api\Data;

interface InjectionsInterface
{

    const URI_PATTERN = 'uri_pattern';
    const INJECTIONS_ID = 'injections_id';
    const ENABLED = 'enabled';
    const CONTENT = 'content';

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
}

