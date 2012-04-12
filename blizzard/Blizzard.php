<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

namespace blizzard;

use blizzard\Exception;

include_once 'Exception.php';

/**
 * Blizzard package and API manager.
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard
 */
class Blizzard {

	/**
	 * Public key.
	 *
	 * @access private
	 * @var string
	 * @static
	 */
	private static $__publicKey;

	/**
	 * Private key.
	 *
	 * @access private
	 * @var string
	 * @static
	 */
	private static $__privateKey;

	/**
	 * Region.
	 *
	 * @access private
	 * @var string
	 * @static
	 */
	private static $__region = 'us';

	/**
	 * Return the globally set API keys.
	 *
	 * @access public
	 * @param string $key
	 * @return string
	 * @static
	 * @final
	 */
	final public static function getApiKey($key = null) {
		$keys = array(
			'public' => self::$__publicKey,
			'private' => self::$__privateKey
		);

		return isset($keys[$key]) ? $keys[$key] : $keys;
	}

	/**
	 * Return the globally set region.
	 *
	 * @access public
	 * @return string
	 * @static
	 * @final
	 */
	final public static function getRegion() {
		return self::$__region;
	}

	/**
	 * Return the officially supported regions.
	 *
	 * @access public
	 * @return string
	 * @static
	 * @final
	 */
	final public static function getSupportedRegions() {
		return array('us', 'eu', 'kr', 'tw', 'cn');
	}

	/**
	 * Set the API key.
	 *
	 * @access public
	 * @param string $public
	 * @param string $private
	 * @return void
	 * @static
	 * @final
	 */
	final public static function setApiKey($public, $private) {
		self::$__publicKey = (string) $public;
		self::$__privateKey = (string) $private;
	}

	/**
	 * Set the region value; must be one of the supported regions.
	 *
	 * @access public
	 * @param string $region
	 * @return void
	 * @static
	 * @final
	 */
	final public static function setRegion($region) {
		$region = strtolower($region);

		if (!in_array($region, self::getSupportedRegions())) {
			throw new Exception(sprintf('The region %s is not supported.', $region));
		}

		self::$__region = $region;
	}

}