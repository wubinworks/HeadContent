<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Api;

interface InjectHeadDataManagementInterface
{
	public const CODE_SUCCESS = 0;
	public const CODE_PARAMETER_MISSING = 1;
	public const CODE_CONTENT_PARSE_FAILED = 2;
	public const CODE_UNKNOWN_ERROR = 9999;
	
    /**
     * GET for InjectHeadData api
     * @param ?string $referer
     * @param ?string $refererFullActionName
     * @return array
     */
    public function getInjectHeadData(?string $referer = null, ?string $refererFullActionName = null): array;
}
