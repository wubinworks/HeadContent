<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Ui\Component\Listing\Column;

use Wubinworks\InjectHead\Helper\Data as Helper;

class ColumnActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    public const URL_PATH_DELETE = 'wubinworks_injecthead/injections/delete';
    public const URL_PATH_EDIT = 'wubinworks_injecthead/injections/edit';
    public const URL_PATH_DETAILS = 'wubinworks_injecthead/injections/details';
    public const URL_PATH_DUPLICATE = 'wubinworks_injecthead/injections/duplicate';
	
	/**
     * Helper
     * @var Helper
     */
    protected $helper;
	
	/**
     * Url builder
     * @var urlBuilder
     */
    protected $urlBuilder;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Wubinworks\InjectHead\Helper\Data $helper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
		Helper $helper,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
		$this->helper = $helper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['injections_id'])) {
                    $duplicateMultiplier = $this->helper->getDuplicationMultiplier();
                    if($duplicateMultiplier) {
                        $duplicate = [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DUPLICATE,
                                [
                                    'injections_id' => $item['injections_id'],
                                    'multiplier' => $duplicateMultiplier
                                ]
                            ),
                            'label' => __('Duplicate') . " {$duplicateMultiplier}x",
                            'confirm' => [
                                '__disableTmpl' => ['title' => false, 'message' => false],
                                'title' => __('Duplicate ID: "${ $.recordId }"'),
                                'message' => __('Are you sure you wan\'t to duplicate record ID="${ $.recordId }" %1 time(s)?', $duplicateMultiplier)
                            ]
                        ];
                    } else {
                        $duplicate = [
                            'href' => '#',
                            'label' => __('Duplicate') . '(' . __('Disabled') . ')',
                        ];
                    }
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'injections_id' => $item['injections_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'duplicate' => $duplicate,
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'injections_id' => $item['injections_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete "${ $.$data.title }"'),
                                'message' => __('Are you sure you wan\'t to delete a "${ $.$data.title }" record?')
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
