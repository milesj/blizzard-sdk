<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

namespace blizzard\api\wow;

use blizzard\api\ApiAbstract;
use blizzard\api\wow\WowApiException;

include_once dirname(__DIR__) . '/ApiAbstract.php';
include_once 'WowApiException.php';

/**
 * Base class for all WoW APIs to extend.
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard.api.wow
 */
abstract class WowApiAbstract extends ApiAbstract {

	/**
	 * Append WoW container to API URL.
	 *
	 * @access public
	 * @param array $config
	 * @return void
	 * @constructor
	 */
	public function __construct(array $config = array()) {
		parent::__construct($config);

		$this->setApiUrl($this->getApiUrl() . 'wow/');
	}

}