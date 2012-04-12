<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

namespace blizzard\api\wow;

use blizzard\api\ApiException;

include_once dirname(__DIR__) . '/ApiException.php';

/**
 * Custom exception class for the WoW API.
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard.api.wow
 */
class WowApiException extends ApiException {
	
}