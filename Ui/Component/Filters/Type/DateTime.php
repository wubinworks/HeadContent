<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Ui\Component\Filters\Type;

/**
 * Added time support, so it is Date + Time = DateTime
 */
class DateTime extends \Magento\Ui\Component\Filters\Type\Date
{
    /**
     * Apply filter
     *
     * @return void
     */
    protected function applyFilter()
    {
        if (isset($this->filterData[$this->getName()])) {
            $value = $this->filterData[$this->getName()];

            if (empty($value)) {
                return;
            }

            if (is_array($value)) {
                if (isset($value['from'])) {
                    $this->applyFilterByType(
                        'gteq',
                        $this->wrappedComponent->convertDatetime(
                            (string)$value['from'],
                            !$this->getData('config/skipTimeZoneConversion')
                        )
                    );
                }

                if (isset($value['to'])) {
                    $this->applyFilterByType(
                        'lteq',
                        $this->wrappedComponent->convertDatetime(
                            (string)$value['to'],
                            !$this->getData('config/skipTimeZoneConversion')
                        )
                    );
                }
            } else {
                $this->applyFilterByType(
                    'eq',
                    $this->wrappedComponent->convertDatetime(
                        (string)$value
                    ),
                    !$this->getData('config/skipTimeZoneConversion')
                );
            }
        }
    }
}
