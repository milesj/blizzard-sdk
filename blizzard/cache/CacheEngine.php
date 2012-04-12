<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

namespace blizzard\cache;

use blizzard\cache\CacheInterface;

/**
 * Basic caching engine that stores all data in memory for the duration of the request.
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard.cache
 */
class CacheEngine implements CacheInterface {

	/**
	 * Stored items.
	 *
	 * @access public
	 * @var array
	 */
	protected $_storage = array();

	/**
	 * Get a cached item.
	 *
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public function get($key) {
		if ($this->has($key)) {
			return $this->_storage[$key];
		}

		return null;
	}

	/**
	 * Check if a cached item exists.
	 *
	 * @access public
	 * @param string $key
	 * @return boolean
	 */
	public function has($key) {
		return isset($this->_storage[$key]);
	}

	/**
	 * Format the cache key.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $args
	 * @return string
	 */
	public function key($key, $args = null) {
		$key = str_replace('::', '.', (string) $key);

		if (!empty($args) || $args === 0) {
			if (is_array($args)) {
				$key .= '-'. implode('-', $args);
			} else {
				$key .= '-'. $args;
			}
		}

		return preg_replace('/[^a-z0-9\-\_]/is', '.', $key);
	}

	/**
	 * Set a cached item.
	 *
	 * @access public
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function set($key, $value) {
		$this->_storage[$key] = $value;
	}
	
}