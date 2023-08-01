<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Customer\Block\CustomerData;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Module\FullModuleList;
use Magento\Framework\App\State as AppState;
use Magento\Framework\App\ResourceConnection;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const XML_PATH_MODULE_ENABLED = 'wubinworks_injecthead/general/enabled';
    //const XML_PATH_NOROUTE_BEHAVIOR = 'wubinworks_injecthead/general/noroute_behavior';
    public const XML_PATH_EXCLUDE_FULLACTIONNAME = 'wubinworks_injecthead/general/exclude_fullactionname';
    public const XML_PATH_DUPLICATION_MULTIPLIER = 'wubinworks_injecthead/general/duplication_multiplier';
    public const XML_PATH_BLOCK_CACHE_LIFETIME = 'wubinworks_injecthead/general/block_cache_lifetime';
    public const XML_PATH_FULL_PAGE_CACHE_TTL = 'system/full_page_cache/ttl';
    public const XML_PATH_MAX_CLIENT_DATA_CACHE_LIFETIME = 'wubinworks_injecthead/general/max_client_data_cache_lifetime';
    public const XML_PATH_VERSION_NUMBER = 'wubinworks_injecthead/general/version_number';
    public const XML_PATH_REVERSE_REWRITE_RULE_LOOKUP = 'wubinworks_injecthead/general/reverse_rewrite_rule_lookup';
    public const XML_PATH_DEBUG_MODE = 'wubinworks_injecthead/general/debug_mode';

    public const XML_PATH_GENERAL_STORE_INFORMATION_NAME = 'general/store_information/name';
    public const XML_PATH_GENERAL_STORE_INFORMATION_COUNTRY_ID = 'general/store_information/country_id';
    public const XML_PATH_WEB_UNSECURE_BASE_URL = 'web/unsecure/base_url';

	/**
     * Store manager interface
     * @var StoreManagerInterface
     */
    protected $storeManager;
	
	/**
     * Cookie manager interface
     * @var CookieManagerInterface
     */
    protected $cookieManager;
	
	/**
     * Cookie metadata factory
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;
	
	/**
     * Session manager interface
     * @var SessionManagerInterface
     */
    protected $sessionManager;
	
	/**
     * Customer data
     * @var CustomerData
     */
    protected $customerData;
    /**
     * Customer session
     * @var CustomerSession
     */
    protected $customerSession;
	
	/**
     * Country information acquirer interface
     * @var CountryInformationAcquirerInterface
     */
    protected $countryInformationAcquirer;
	
	/**
     * Product metadata interface
     * @var ProductMetadataInterface
     */
    protected $productMetadata;
	
	/**
     * Module list interface
     * @var ModuleListInterface
     */
    protected $moduleList;
	
	/**
     * Full module list
     * @var FullModuleList
     */
    protected $fullModuleList;

	/**
	 * App state
     * @var AppState
     */
    protected $appState;
	
	/**
     * Resource connection
     * @var ResourceConnection
     */
	protected $resourceConnection;
	
    /**
	 * Constructor
	 *
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
	 * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
	 * @param \Magento\Framework\Session\SessionManagerInterface $sessionManager
	 * @param \Magento\Customer\Block\CustomerData $customerData
	 * @param \Magento\Customer\Model\Session $customerSession
	 * @param \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInformationAcquirer
	 * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
	 * @param \Magento\Framework\Module\ModuleListInterface $moduleList
	 * @param \Magento\Framework\Module\FullModuleList $fullModuleList
	 * @param \Magento\Framework\App\State $appState
	 * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager,
        CustomerData $customerData,
        CustomerSession $customerSession,
        CountryInformationAcquirerInterface $countryInformationAcquirer,
        ProductMetadataInterface $productMetadata,
        ModuleListInterface $moduleList,
        FullModuleList $fullModuleList,
		AppState $appState,
		ResourceConnection $resourceConnection,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->storeManager = $storeManager;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
        $this->customerData = $customerData;
        $this->customerSession = $customerSession;
        $this->countryInformationAcquirer = $countryInformationAcquirer;
        $this->productMetadata = $productMetadata;
        $this->moduleList = $moduleList;
        $this->fullModuleList = $fullModuleList;
		$this->appState = $appState;
		$this->resourceConnection = $resourceConnection;
        parent::__construct($context);
    }

	/**
     * @return string|null
     */
    public function getConfig($path,
		$scope = \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
		$scopeCode = null
		) {
        return $this->scopeConfig->getValue(
            $path,
            $scope,
            $scopeCode
        );
    }

	/**
     * @return \Magento\Framework\Url\EncoderInterface
     */
    public function getUrlEncoder()
    {
        return $this->urlEncoder;
    }
	
	/**
     * @return \Magento\Framework\Url\DecoderInterface
     */
    public function getUrlDecoder()
    {
        return $this->urlDecoder;
    }
	
    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->getConfig(self::XML_PATH_MODULE_ENABLED)
            && $this->isMagentoVersionCompatible();
    }

    /**
     * @return bool
     */
    public function getNoRouteBehavior()
    {
        return (bool)$this->getConfig(self::XML_PATH_NOROUTE_BEHAVIOR);
    }

    /**
     * @return string
     */
    public function getExcludeFullActionName()
    {
        return $this->getConfig(self::XML_PATH_EXCLUDE_FULLACTIONNAME);
    }

    /**
     * @return int
     */
    public function getDuplicationMultiplier()
    {
        return (int)$this->getConfig(self::XML_PATH_DUPLICATION_MULTIPLIER);
    }

    /**
     * @return int|null
     */
    public function getBlockCacheLifetime()
    {
        $value = $this->getConfig(self::XML_PATH_BLOCK_CACHE_LIFETIME);
        if(is_null($value)) {
            return $value;
        }
		else {
            return (int)$value;
        }
    }

    /**
     * @return int
     */
    public function getFullPageCacheTtl()
    {
        return (int)$this->getConfig(self::XML_PATH_FULL_PAGE_CACHE_TTL);
    }

    /**
     * @return int
     */
    public function getMaxClientDataCacheLifetime()
    {
        return (int)$this->getConfig(self::XML_PATH_MAX_CLIENT_DATA_CACHE_LIFETIME);
    }

    /**
     * @return int
     */
    public function getVersionNumber()
    {
        return (int)$this->getConfig(self::XML_PATH_VERSION_NUMBER);
    }

    /**
     * @return bool
     */
    public function getReverseRewriteRuleLookup()
    {
        return (bool)$this->getConfig(self::XML_PATH_REVERSE_REWRITE_RULE_LOOKUP);
    }

    /**
     * @return bool
     */
    public function getDebugMode()
    {
        return (bool)$this->getConfig(self::XML_PATH_DEBUG_MODE);
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return (string)$this->getConfig(self::XML_PATH_GENERAL_STORE_INFORMATION_NAME);
    }

    /**
     * @return string
     */
    public function getStoreCountryId()
    {
        return (string)$this->getConfig(self::XML_PATH_GENERAL_STORE_INFORMATION_COUNTRY_ID);
    }

    /**
     * @return string
     */
    public function getStoreCountry($english = false)
    {
        $data = $this->countryInformationAcquirer->getCountryInfo($this->getStoreCountryId());
        if($english) {
            return $data->getFullNameEnglish();
        }
		else {
            return $data->getFullNameLocale();
        }
    }

    /**
     * @return string
     */
    public function getUnsecureBaseUrl()
    {
        return (string)$this->getConfig(self::XML_PATH_WEB_UNSECURE_BASE_URL);
    }

    /**
     * @return string
     */
    public function getMagentoVersion()
    {
        return (string)$this->productMetadata->getVersion();
    }

    /**
     * @return bool
     */
    public function isMagentoVersionCompatible()
    {
        return \Composer\Semver\Comparator::greaterThan($this->getMagentoVersion(), '2.4.0')
            && \Composer\Semver\Comparator::lessThan($this->getMagentoVersion(), '2.5.0');
    }

    /**
     * @return string
     */
    public function getModuleSetupVersion($moduleName = null)
    {
		if(is_null($moduleName)){
			$moduleName = $this->_getModuleName();
		}
        $moduleInfo = $this->fullModuleList->getOne($moduleName);
        return array_key_exists('setup_version', $moduleInfo) ? $moduleInfo['setup_version'] : __('Unknown');
    }

	/**
     * @return string
     */
    public function getDbTimeZone($key = null)
    {
        $connection = $this->resourceConnection->getConnection();
        $sql = $connection->quoteInto("SHOW Variables LIKE ?", '%time_zone%');
        $arr = $connection->query($sql)->fetchAll();/*var_dump($arr);*/
        $str = '';
        foreach($arr as $row) {
            if($row['Variable_name'] == 'system_time_zone') {
                if($key === 'system_time_zone') {
                    return $row['Value'];
                }
                $str .= '<span>system_time_zone=' . $row['Value'] . '</span>';
            }
            if($row['Variable_name'] == 'time_zone') {
                if($key === 'time_zone') {
                    return $row['Value'];
                }
                $str .= '<span>time_zone=' . $row['Value'] . '</span>';
            }
        }
        return $str;
    }
	
    /**
     * Return `null` for module that does not exist
	 *
     * @return bool|null
     */
    public function isModuleEnabled($moduleName = null)
    {
		if(is_null($moduleName)){
			$moduleName = $this->_getModuleName();
		}
        if(!$this->fullModuleList->getOne($moduleName)) {
            return null;
        }
        return $this->_moduleManager->isEnabled($moduleName);
    }

    /**
     * @return bool
     */
    public function isFullActionNameMatched($fullActionName, $pattern)
    {
        $pattern = explode('/', $pattern);
        $fullActionName = explode('/', $fullActionName);
        if(count($fullActionName) !== 3) {
            return false;
        } elseif(count($pattern) === 1) {
            return $pattern[0] === $fullActionName[0];
        } elseif(count($pattern) === 2) {
            if(($pattern[0] === '*' || $pattern[0] === $fullActionName[0])
                && ($pattern[1] === '*' || $pattern[1] === $fullActionName[1])
            ) {
                return true;
            }
        } elseif(count($pattern) === 3) {
            if(($pattern[0] === '*' || $pattern[0] === $fullActionName[0])
                && ($pattern[1] === '*' || $pattern[1] === $fullActionName[1])
                && ($pattern[2] === '*' || $pattern[2] === $fullActionName[2])
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $fullActionName
     * @return boolean
     */
    public function isFullActionNameExcluded($fullActionName)
    {
        $patternList = explode(',', $this->getExcludeFullActionName());
        foreach($patternList as $pattern) {
            if($this->isFullActionNameMatched($fullActionName, trim($pattern))) {
                return true;
            }
        }
        return false;
    }


    /**
     * @return int
     */
    public function getCurrentCustomerGroupId()
    {
        if($this->customerSession->isLoggedIn()) {
            return (int)$this->customerSession->getCustomer()->getGroupId();
        }
        return 0;
    }

    /**
     * Set public cookie
	 * @return void
     * @throws FailureToSendException If cookie couldn't be sent to the browser.
     * @throws CookieSizeLimitReachedException Thrown when the cookie is too big to store any additional data.
     * @throws InputException If the cookie name is empty or contains invalid characters.
     */
    public function setPublicCookie($cookieName, $value)
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration($this->customerData->getCookieLifeTime()) // Cookie will expire after one day (86400 seconds)
            //->setSecure(true) //the cookie is only available under HTTPS
            ->setPath('/')// The cookie will be available to all pages and subdirectories within the /subfolder path
            ->setSameSite('Strict')
            ->setHttpOnly(false); // cookies can be accessed by JS

        $this->cookieManager->setPublicCookie(
            $cookieName,
            $value,
            $metadata
        );
    }

	/**
     * Delete custom Cookie
	 * @return void
     * @throws FailureToSendException If cookie couldn't be sent to the browser.
     *     If this exception isn't thrown, there is still no guarantee that the browser
     *     received and accepted the request to delete this cookie.
     * @throws InputException If the cookie name is empty or contains invalid characters.
     */
    public function deleteCookie($cookieName)
    {
        if($this->cookieManager->getCookie($cookieName)) {
            $metadata = $this->cookieMetadataFactory
                ->createPublicCookieMetadata();
            $metadata->setPath('/');

            $this->cookieManager->deleteCookie(
                $cookieName,
                $metadata
            );
        }
    }
	
	/**
     * @return string
     */
    private static function addFragment($url, $fragment = null)
    {
        if(strlen((string)$fragment)) {
            $url .= '#' . $fragment;
        }
        return $url;
    }

    /**
     * @return string
     */
    public static function getHomeUrl($fragment = null)
    {
        $url = 'https://github.com/wubinworks/home';
        return self::addFragment($url, $fragment);
    }

    /**
     * @return string
     */
    public static function getProjectUrl($fragment = null)
    {
        $url = 'https://github.com/wubinworks/HeadContent';
        return self::addFragment($url, $fragment);
    }

    /**
     * @return string
     */
    public static function getProjectIssueUrl($fragment = null)
    {
        $url = 'https://github.com/wubinworks/HeadContent/issues';
        return self::addFragment($url, $fragment);
    }
}
