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

include_once 'WowApiAbstract.php';

/**
 * API for generic data results: classes, races, guild perks, guild rewards and items.
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard.api.wow
 */
class DataApi extends WowApiAbstract {
	
	/**
	 * Base WoW API URL.
	 * 
	 * @access protected
	 * @var protected
	 */
	protected $_baseUrl;

	/**
	 * Save the current API url.
	 *
	 * @access public
	 * @param array $config
	 * @return void
	 * @constructor
	 */
	public function __construct(array $config = array()) {
		parent::__construct($config);

		$this->_baseUrl = $this->getApiUrl();
	}
	
	/**
	 * Return the character classes.
	 * 
	 * @access public
	 * @return array
	 */
	public function getClasses() {
		$this->setApiUrl($this->_baseUrl .'data/character/classes');
		
		return $this->requestData(__METHOD__);
	}
	
	/**
	 * Return the guild perks.
	 * 
	 * @access public
	 * @return array
	 */
	public function getGuildPerks() {
		$this->setApiUrl($this->_baseUrl .'data/guild/perks');
		
		return $this->requestData(__METHOD__);
	}
	
	/**
	 * Return the guild rewards.
	 * 
	 * @access public
	 * @return array
	 */
	public function getGuildRewards() {
		$this->setApiUrl($this->_baseUrl .'data/guild/rewards');
		
		return $this->requestData(__METHOD__);
	}
	
	/**
	 * Return an item based on ID.
	 * 
	 * @access public
	 * @param int $id
	 * @return array
	 */
	public function getItem($id) {
		if (empty($id) || !is_numeric($id)) {
			throw new WowApiException(sprintf('Item ID %s invalid for %s.', $id, __METHOD__));
		}
		
		$this->setApiUrl($this->_baseUrl .'data/item/'. $id);
		
		return $this->requestData(__METHOD__, $id);
	}
	
	/**
	 * Return the character races.
	 * 
	 * @access public
	 * @return array
	 */
	public function getRaces() {
		$this->setApiUrl($this->_baseUrl .'data/character/races');
		
		return $this->requestData(__METHOD__);
	}
	
	/**
	 * Request specific API data and cache the result.
	 * 
	 * @access protected
	 * @param string $method
	 * @param mixed $args
	 * @return array
	 */
	protected function requestData($method, $args = null) {
		$engine = $this->getCacheEngine();
		$key = $engine->key($method, $args);

		if ($engine->has($key)) {
			return $engine->get($key);
		}

		$request = $this->request();
		$results = $request->response();

		$engine->set($key, $results);

		return $results;
	}
	
}