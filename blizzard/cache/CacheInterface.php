<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

namespace blizzard\cache;

/**
 * Interface for all caching engines.
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard.cache
 */
interface CacheInterface {

	/**
	 * Get a cached item.
	 *
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public function get($key);

	/**
	 * Check if a cached item exists.
	 *
	 * @access public
	 * @param string $key
	 * @return boolean
	 */
	public function has($key);

	/**
	 * Format the cache key.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $args
	 * @return string
	 */
	public function key($key, $args);
		
	/**
	 * Set a cached item.
	 *
	 * @access public
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function set($key, $value);

}