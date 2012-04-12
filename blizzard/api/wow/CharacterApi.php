<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

namespace blizzard\api\wow;

use blizzard\api\wow\WowApiAbstract;
use blizzard\api\wow\WowApiException;

include_once 'WowApiAbstract.php';
include_once 'WowApiException.php';

/**
 * @todo
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard.api.wow
 */
class CharacterApi extends WowApiAbstract {

	/**
	 * Append character and realm to API URL.
	 *
	 * @access public
	 * @param array $config
	 * @return void
	 * @constructor
	 */
	public function __construct(array $config = array()) {
		/*if (empty($config['character']) || empty($config['realm'])) {
			throw new WowApiException('Please provide a character name and realm.');
		}

		parent::__construct($config);

		$this->setApiUrl($this->getApiUrl() . sprintf('character/%s/%s/', $config['realm'], $config['character']));*/
	}

}