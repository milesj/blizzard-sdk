<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

namespace blizzard\api;

use blizzard\Exception;

include_once dirname(__DIR__) . '/Exception.php';

/**
 * Custom exception class for the API package.
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard.api
 */
class ApiException extends Exception {
	
}