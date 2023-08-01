<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\SimpleHTMLDom;

const DS = DIRECTORY_SEPARATOR;

require_once __DIR__ . DS . '..' . DS . 'lib' . DS . 'SimpleHTMLDom' . DS . 'simple_html_dom.php';

/**
 * https://simplehtmldom.sourceforge.io/docs/1.9/index.html
 */
class SimpleDomParser
{
	/**
	 * file_get_html ( string $url [, bool $use_include_path = false [, resouce $context = null [, int $offset = 0 [, int $maxLen = -1 [, bool $lowercase = true [, bool $forceTagsClosed = true [, string $target_charset = DEFAULT_TARGET_CHARSET [, bool $stripRN = true [, string $defaultBRText = DEFAULT_BR_TEXT [, string $defaultSpanText = DEFAULT_SPAN_TEXT ]]]]]]]]]] )
	 */
    public static function file_get_html()
    {
        return call_user_func_array('\file_get_html', func_get_args());
    }

	/**
	 * str_get_html ( string $str [, bool $lowercase = true [, bool $forceTagsClosed = true [, string $target_charset = DEFAULT_TARGET_CHARSET [, bool $stripRN = true [, string $defaultBRText = DEFAULT_BR_TEXT [, string $defaultSpanText = DEFAULT_SPAN_TEXT ]]]]]] )
	 */
    public static function str_get_html()
    {
        return call_user_func_array('\str_get_html', func_get_args());
    }
}
