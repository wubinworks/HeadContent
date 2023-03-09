<?php
/**
 * Copyright © Wubinworks All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model;

use Magento\Framework\Model\AbstractModel;
use Wubinworks\InjectHead\Api\Data\InjectionsInterface;

class Injections extends AbstractModel implements InjectionsInterface
{

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
}

