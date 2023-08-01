<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Ui\Component\Listing\Column\MatchMode;

class MatchModeOptions implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            1 => [
                'label' => __('Match URI in Regular Expression'),
                'value' => 1
            ],
            2 => [
                'label' => __('Match Full Action Name') . __('[Not Working!]'),
                'value' => 2
            ],
            3 => [
                'label' => __('Reserved') . __('[Not Working!]'),
                'value' => 3
            ],
        ];
    }
}
