<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Model;

use Wubinworks\InjectHead\Api\InjectionsRepositoryInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\Phrase;
use Magento\Framework\Webapi\Exception as WebapiException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Url\DecoderInterface;
use Wubinworks\InjectHead\Helper\Data as Helper;

/**
 * Currently WebapiException is not used
 * Frontend just checkes code value
 */
class InjectHeadDataManagement implements \Wubinworks\InjectHead\Api\InjectHeadDataManagementInterface
{
    /**
     * Injections repository
     * @var InjectionsRepositoryInterface
     */
    protected $injectionsRepository;
	
	/**
	 * Webapi Rest Request
     * @var \Magento\Framework\Webapi\Rest\Request
     */
    protected $request;
	
	/**
     * Decoder interface
     * @var DecoderInterface
     */
    protected $decoder;
	
	/**
     * Helper
     * @var Helper
     */
    protected $helper;

	/**
     * Constructor
	 *
     * @param \Wubinworks\InjectHead\Api\InjectionsRepositoryInterface $injectionsRepository
     * @param \Magento\Framework\Webapi\Rest\Request $request
     * @param \Magento\Framework\Url\DecoderInterface $decoder
	 * @param \Wubinworks\InjectHead\Helper\Data $helper
     */
    public function __construct(
        InjectionsRepositoryInterface $injectionsRepository,
        Request $request,
        DecoderInterface $decoder,
        Helper $helper
    ) {
        $this->injectionsRepository = $injectionsRepository;
        $this->request = $request;
        $this->decoder = $decoder;
        $this->helper = $helper;
    }

    /**
	 * data_id: current timestamp
	 * expire_before: sever pushed timestamp, MAY NOT EXISTS. Browser should not rely on this value
	 * If something went wrong, let browser wait 10 minutes
	 *
     * {@inheritdoc}
     */
    public function getInjectHeadData(?string $referer = null, ?string $refererFullActionName = null): array
    {
        if(!$referer || !$refererFullActionName) {
            return [
				'injecthead' => [
					'code' => static::CODE_PARAMETER_MISSING,
					'message' => new Phrase('Parameters Missing'),
					'data_id' => time(),
					'expire_before' => time() + 10 * 60
				],
				'type' => 'simple_array'
            ];
        }
        return $this->renderResponseBody(
			$this->decoder->decode($referer),
            $this->decoder->decode($refererFullActionName)
        );
    }
	
	/**
     * @return array
     */
	private function renderResponseBody(string $referer, string $refererFullActionName) : array
	{
		try{
			$expireBefore = $this->injectionsRepository->getExpireBefore($referer, $refererFullActionName);
			$html = $this->injectionsRepository->getPrivateHeadHtml($referer, $refererFullActionName);
			$html = $this->injectionsRepository->getFilteredHeadHtml($html);
			$data = $this->injectionsRepository->getHeadData($html);
			return $this->renderSuccessJson($data, $expireBefore);
		}
		catch(LocalizedException $e){ // Parse failed
			return $this->renderFailedJson($e);
		}
	}
	
	/**
     * @return array
     */
	private function renderSuccessJson(array $data, $expireBefore) : array
	{
		$result =  [
			'injecthead' => [
				'code' => static::CODE_SUCCESS,
				'data_id' => time()
			],
			'type' => 'simple_array'
        ];
		//No content, so no data, but it is not an error
		if(empty($data)){
			$result['injecthead']['message'] = new Phrase('No Content');
		}
		else{
			$result['injecthead']['message'] = new Phrase('Success');
			$result['injecthead']['data'] = $data;
		}
		if($expireBefore && strlen($expireBefore)){
			$result['injecthead']['expire_before'] = strtotime($expireBefore);
		}
		return $result;
	}
	
	/**
     * @return array
     */
	private function renderFailedJson(LocalizedException $e) : array
	{
		$result =  [
			'injecthead' => [
				'code' => $e->getCode(),
				'message' => $e->getMessage(),
				'data_id' => time(),
				'expire_before' => time() + 10 * 60
			],
			'type' => 'simple_array'
        ];
		return $result;
	}
}
