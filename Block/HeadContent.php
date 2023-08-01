<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block;

//use Magento\Framework\App\RouterListInterface;
use Magento\Framework\App\ResponseInterface;
use Wubinworks\InjectHead\Api\InjectionsRepositoryInterface;

class HeadContent extends \Wubinworks\InjectHead\Block\FrontendBlock
{
    public const NO_ROUTE_FULL_ACTION_NAME = 'cms/noroute/index';

    /**
     * Router list interface
     * @var RouterListInterface
     */
    //protected $routerList;

    /**
     * Response interface
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Injections
     * @var \Wubinworks\InjectHead\Api\Data\InjectionsInterface;
     */
    protected $injections;

	/**
     * Injections repository
     * @var InjectionsRepositoryInterface
     */
    protected $injectionsRepository;
	
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Wubinworks\InjectHead\Api\InjectionsRepositoryInterface $injectionsRepository
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        //RouterListInterface $routerList,
        ResponseInterface $response,
		InjectionsRepositoryInterface $injectionsRepository,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        //$this->routerList = $routerList;
        $this->response = $response;
		$this->injectionsRepository = $injectionsRepository;
        parent::__construct($context, $data);
    }

	/**
     * @return int
     */
    protected function _getCacheLifetime()
    {
        return $this->helper->getBlockCacheLifetime() ?? parent::_getCacheLifetime();
    }
	
    /**
     * Not used. Debug purpose only
     * @param string|null $pattern
     *
     * @return boolean
     */
    protected function isUriMatched($pattern)
    {
        if(is_null($pattern) || $pattern === '' || $pattern === '/') {
            return true;
        }
        return preg_match('#' . $pattern . '#', $this->getRequest()->getRequestUri());
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $html = $this->injectionsRepository->getPublicHeadHtml(
            $this->getRequest()->getRequestUri(),
            $this->getRequest()->getFullActionName('/')
        );
        return $this->injectionsRepository->getFilteredHeadHtml($html);
    }
}
