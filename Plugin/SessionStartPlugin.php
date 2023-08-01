<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Plugin;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Session\SessionStartChecker;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;
use Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Phrase;
use Psr\Log\LoggerInterface;

/**
 * Check this file \Magento\Framework\Stdlib\Cookie\PhpCookieManager, too
 */
class SessionStartPlugin
{
	public const INJECTHEAD_SESSION = 'wubinworks_injecthead_session';
	public const HASH_ALGORITHM = 'sha256';
	public const COOKIE_SAMESITE_STRICT = 'Strict';
	public const COOKIE_SAMESITE_LAX = 'Lax';
	
    /**
     * @var SessionStartChecker
     */
    private $sessionStartChecker;
	
    /**
     * Cookie Manager
     *
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;
	
    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    protected $cookieMetadataFactory;
	
    /**
     * Session config
     *
     * @var Config\ConfigInterface
     */
    protected $sessionConfig;

    /**
     * Logger for warning details.
     *
     * @var LoggerInterface
     */
    private $logger;

	/**
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param \Magento\Framework\Session\Config\ConfigInterface $sessionConfig
	 * @param \Magento\Framework\Session\SessionStartChecker|null $sessionStartChecker
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        ConfigInterface $sessionConfig,
        SessionStartChecker $sessionStartChecker = null,
        LoggerInterface $logger = null
    ) {
        $this->sessionStartChecker = $sessionStartChecker ?: \Magento\Framework\App\ObjectManager::getInstance()->get(
            SessionStartChecker::class
        );
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionConfig = $sessionConfig;
		$this->logger = $logger ?: ObjectManager::getInstance()->get(LoggerInterface::class);
    }

	/**
     * Add INJECTHEAD_SESSION to Http Header Set-Cookie
     *
     * @param \Magento\Framework\Session\SessionManagerInterface $subject
     * @param callable $proceed
     * @return \Magento\Framework\Session\SessionManagerInterface
     */
    public function aroundStart(
        \Magento\Framework\Session\SessionManagerInterface $subject,
        callable $proceed
    ) {
		$result = $subject;
        $sessionWillStart = false;
        if ($this->sessionStartChecker->check()) {
            if (!$subject->isSessionExists()) {
                $sessionWillStart = true;
            }
        }
        $result = $proceed();
        if($sessionWillStart &&
            $subject->isSessionExists() && // check if session really started and is correct
            $subject->getName() &&
            $subject->getSessionId() &&
            $this->sessionConfig->getCookieLifetime()
        ) {
            $expire = $this->sessionConfig->getCookieLifetime() + time();
            $this->setCookie(
                self::INJECTHEAD_SESSION,
                hash(self::HASH_ALGORITHM, $subject->getSessionId()),
                $expire,
                false,
                self::COOKIE_SAMESITE_STRICT
            );
            $this->setCookie(
                $subject->getName(),
                $this->cookieManager->getCookie($subject->getName()) ?: $subject->getSessionId(),
                $expire,
                $this->sessionConfig->getCookieHttpOnly(),
                $this->sessionConfig->getCookieSameSite()
            );
        }
        return $result;
    }

    /**
     * Set a value in a cookie with the given $name $value pairing.
     *
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param bool $httpOnly
     * @param string $sameSite
     * @return void
     * @throws FailureToSendException If cookie couldn't be sent to the browser.
     * @throws CookieSizeLimitReachedException Thrown when the cookie is too big to store any additional data.
     * @throws InputException If the cookie name is empty or contains invalid characters.
     */
    protected function setCookie($name, $value, $expire, $httpOnly = false, $sameSite = self::COOKIE_SAMESITE_LAX)
    {
        $this->checkAbilityToSendCookie($name, $value);
        $phpSetcookieSuccess = setcookie(
            $name,
            $value,
            [
                'expires' => $expire,
                'path' => $this->sessionConfig->getCookiePath(),
                'domain' => $this->sessionConfig->getCookieDomain(),
                'secure' => $this->sessionConfig->getCookieSecure(),
                'httponly' => $httpOnly,
                'samesite' => $sameSite
            ]
        );

        if (!$phpSetcookieSuccess) {
            $params['name'] = $name;
            if ($value == '') {
                throw new FailureToSendException(
                    new Phrase('The cookie with "%name" cookieName couldn\'t be deleted.', $params)
                );
            } else {
                throw new FailureToSendException(
                    new Phrase('The cookie with "%name" cookieName couldn\'t be sent. Please try again later.', $params)
                );
            }
        }
    }

    /**
     * Retrieve the size of a cookie.
     *
     * The size of a cookie is determined by the length of 'name=value' portion of the cookie.
     *
     * @param string $name
     * @param string $value
     * @return int
     */
    private function sizeOfCookie($name, $value)
    {
        // The constant '1' is the length of the equal sign in 'name=value'.
        return strlen($name) + 1 + strlen($value);
    }

    /**
     * Determines ability to send cookies, based on the number of existing cookies and cookie size
     *
     * @param string $name
     * @param string|null $value
     * @return void if it is possible to send the cookie
     * @throws CookieSizeLimitReachedException Thrown when the cookie is too big to store any additional data.
     * @throws InputException If the cookie name is empty or contains invalid characters.
     */
    private function checkAbilityToSendCookie($name, $value)
    {
        if ($name == '' || preg_match("/[=,; \t\r\n\013\014]/", $name)) {
            throw new InputException(
                new Phrase(
                    'Cookie name cannot be empty and cannot contain these characters: =,; \\t\\r\\n\\013\\014'
                )
            );
        }

        $numCookies = count($_COOKIE);

        if (!isset($_COOKIE[$name])) {
            $numCookies++;
        }

        $sizeOfCookie = $this->sizeOfCookie($name, $value);

        if ($numCookies > PhpCookieManager::MAX_NUM_COOKIES) {
            $this->logger->warning(
                new Phrase('Unable to send the cookie. Maximum number of cookies would be exceeded.'),
                [
                    'cookies' => $_COOKIE,
                    'user-agent' => $this->httpHeader->getHttpUserAgent()
                ]
            );
        }

        if ($sizeOfCookie > PhpCookieManager::MAX_COOKIE_SIZE) {
            throw new CookieSizeLimitReachedException(
                new Phrase(
                    'Unable to send the cookie. Size of \'%name\' is %size bytes.',
                    [
                        'name' => $name,
                        'size' => $sizeOfCookie,
                    ]
                )
            );
        }
    }
}
