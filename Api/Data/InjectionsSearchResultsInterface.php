<?php
/**
 * Copyright © Wubinworks All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Api\Data;

interface InjectionsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Injections list.
     * @return \Wubinworks\InjectHead\Api\Data\InjectionsInterface[]
     */
    public function getItems();

    /**
     * Set enabled list.
     * @param \Wubinworks\InjectHead\Api\Data\InjectionsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

