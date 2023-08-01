<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Plugin\Base;

class ServiceOutputProcessorPlugin
{
    /**
     * Remove the json output outer array
	 * ie: `[{"key": "value"}]` => `{"key": "value"}`
     *
	 * @param \Magento\Framework\Webapi\ServiceOutputProcessor $subject
	 * @param array|object $result
     * @param array $data
     * @param string $type
     * @return array|object
     */
    public function afterConvertValue(
        \Magento\Framework\Webapi\ServiceOutputProcessor $subject,
        $result,
        $data,
        $type
    ) {
        if(is_array($data) && isset($data['type']) && $data['type'] === 'simple_array') {
            unset($data['type']);
            return $data;
        }
        return $result;
    }
}
