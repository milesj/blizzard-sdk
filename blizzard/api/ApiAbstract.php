<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

namespace blizzard\api;

use blizzard\Blizzard;
use blizzard\api\ApiResponse;
use blizzard\api\ApiException;
use blizzard\cache\CacheEngine;
use blizzard\cache\CacheInterface;

include_once dirname(__DIR__) . '/Blizzard.php';
include_once dirname(__DIR__) . '/cache/CacheInterface.php';
include_once dirname(__DIR__) . '/cache/CacheEngine.php';
include_once 'ApiResponse.php';
include_once 'ApiException.php';

/**
 * Primary API class that all children source APIs extend. Provides functionality for
 * setting the API key and region, preparing query string parameters, defining HTTP headers
 * and making HTTP requests using the cURL library.
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard.api
 */
abstract class ApiAbstract {

	/**
	 * Official WoW API URL.
	 */
	const API_URL = 'http://{region}.battle.net/api/';

	/**
	 * Cache key for default result set.
	 */
	const CACHE_KEY = '__cache';

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'apiUrl' => null,
		'publicKey' => null,
		'privateKey' => null,
		'region' => 'us'
	);

	/**
	 * HTTP headers to append to the request.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_headers = array();

	/**
	 * Array of key/value pairs to append as a query string to the request.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_query = array();

	/**
	 * The schema data structure for the response.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_schema = array();

	/**
	 * Whitelist of accepted query parameters for the current request.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_whitelist = array();

	/**
	 * Cache engine instance.
	 *
	 * @access protected
	 * @var CacheInterface
	 */
	private $__cache;

	/**
	 * Store the configuration.
	 *
	 * @access public
	 * @param array $config
	 * @return void
	 * @constructor
	 */
	public function __construct(array $config = array()) {
		if (empty($config['publicKey'])) {
			$config['publicKey'] = Blizzard::getApiKey('public');
		}

		if (empty($config['privateKey'])) {
			$config['privateKey'] = Blizzard::getApiKey('private');
		}

		if (empty($config['region'])) {
			$config['region'] = Blizzard::getRegion();
		}

		$this->setRegion($config['region']);
		$this->setApiKey($config['publicKey'], $config['privateKey']);
		$this->setApiUrl(str_replace('{region}', $this->getRegion(), self::API_URL));

		unset($config['publicKey'], $config['privateKey'], $config['region'], $config['apiUrl']);

		if (!empty($config)) {
			$this->_config = $config + $this->_config;
		}

		$this->setCacheEngine(new CacheEngine());
	}

	/**
	 * Store a cache of the base results. We will use this cache to filter upon
	 * instead of doing subsequent HTTP requests.
	 *
	 * @access public
	 * @return boolean
	 */
	public function cache() {
		return true;
	}

	/**
	 * Filter down the result set on a key basis.
	 *
	 * @access public
	 * @param array $results
	 * @param string $key
	 * @param mixed $filter
	 * @return array
	 */
	public function filter($results, $key, $filter) {
		$clean = array();

		if (!empty($results)) {
			foreach ($results as $result) {
				if (!isset($result[$key])) {
					continue;
				}
				
				if (is_array($filter) && in_array($result[$key], $filter)) {
					$clean[] = $result;

				} else if ($filter instanceof \Closure && $filter($result[$key])) {
					$clean[] = $result;

				} else if ($result[$key] == $filter) {
					$clean[] = $result;
				}
			}
		}

		return $clean;
	}

	/**
	 * Populate a new set of results, based on the default primary cache, by applying filter rules.
	 *
	 * @access public
	 * @param string $method
	 * @param string $field
	 * @param mixed $filter
	 * @return array
	 * @final
	 */
	final public function filterBy($method, $field, $filter) {
		$this->cache();

		$engine = $this->getCacheEngine();

		if ($filter instanceof \Closure) {
			$key = $engine->key($method, array($field, 'Closure'));
		} else {
			$key = $engine->key($method, $filter);
		}

		if ($engine->has($key)) {
			return $engine->get($key);
		}

		$results = $this->filter($engine->get(self::CACHE_KEY), $field, $filter);
		$engine->set($key, $results);

		return $results;
	}
	
	/**
	 * Return the currently set API key.
	 *
	 * @access public
	 * @param string $key
	 * @return string
	 * @final
	 */
	final public function getApiKey($key = null) {
		$keys = array(
			'public' => $this->_config['publicKey'],
			'private' => $this->_config['privateKey']
		);

		return isset($keys[$key]) ? $keys[$key] : $keys;
	}

	/**
	 * Return the currently set API URL.
	 *
	 * @access public
	 * @return string
	 * @final
	 */
	final public function getApiUrl() {
		return $this->_config['apiUrl'];
	}

	/**
	 * Get the cache engine.
	 *
	 * @access public
	 * @return CacheInterface
	 * @final
	 */
	final public function getCacheEngine() {
		return $this->__cache;
	}

	/**
	 * Return the query string as an array. If $build is true, assemble the query string.
	 *
	 * @access public
	 * @param boolean $build
	 * @return string
	 * @final
	 */
	final public function getQuery($build = true) {
		if ($build) {
			$query = array();

			foreach ($this->_query as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $subValue) {
						$query[] = $key .'='. urlencode($subValue);
					}
				} else {
					$query[] = $key .'='. urlencode($value);
				}
			}

			return implode('&', $query);
		}

		return $this->_query;
	}

	/**
	 * Return an individual value from the query string.
	 *
	 * @access public
	 * @param string $param
	 * @return mixed
	 * @final
	 */
	final public function getQueryParam($param) {
		return isset($this->_query[$param]) ? $this->_query[$param] : null;
	}

	/**
	 * Return the whitelisted query params.
	 *
	 * @access public
	 * @return array
	 * @final
	 */
	final public function getQueryWhitelist() {
		return $this->_whitelist;
	}

	/**
	 * Return the currently set region.
	 *
	 * @access public
	 * @return string
	 * @final
	 */
	final public function getRegion() {
		return $this->_config['region'];
	}

	/**
	 * Perform an HTTP GET request using the cURL library and format the response accordingly.
	 *
	 * @access public
	 * @return ApiResponse
	 * @final
	 */
	final public function request() {
		$curl = curl_init();
		$url = $this->getApiUrl();
		$query = $this->getQuery();
		$keys = $this->getApiKey();
		$headers = array();
		
		if (!empty($query)) {
			$url .= '?'. $query;
		}
		
		if (!empty($keys['public']) && !empty($keys['private'])) {
			$date = date(DATE_RFC2822);
			$headers = array(
				'Date: '. $date,
				'Authorization: BNET '. $keys['public'] .':'. base64_encode(hash_hmac('sha1', "GET\n{$date}\n{$url}\n", $keys['private'], true))
			);
		}

		curl_setopt_array($curl, array(
			CURLOPT_URL				=> $url,
			CURLOPT_HEADER			=> false,
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_FOLLOWLOCATION	=> true,
			CURLOPT_AUTOREFERER		=> true,
			CURLOPT_CONNECTTIMEOUT	=> 120,
			CURLOPT_TIMEOUT			=> 30,
			CURLOPT_HTTPGET			=> true,
			CURLOPT_HTTPAUTH		=> CURLAUTH_ANY,
			CURLOPT_HTTP_VERSION	=> CURL_HTTP_VERSION_1_1,
			CURLOPT_HTTPHEADER		=> $headers,
			CURLOPT_SSL_VERIFYHOST	=> false,
			CURLOPT_SSL_VERIFYPEER	=> false,
			CURLOPT_USERAGENT		=> 'Blizzard API SDK Package'
		));

		$request = curl_exec($curl);
		$headers = curl_getinfo($curl);

		if ($request === false) {
			$response = curl_error($curl);
		} else {
			$response = $request;
		}

		curl_close($curl);

		return new ApiResponse($response, $headers);
	}

	/**
	 * Reset the class to perform another request.
	 *
	 * @access public
	 * @return void
	 * @final
	 */
	final public function reset() {
		$this->_query = array();
		$this->_whitelist = array();
	}

	/**
	 * Return all the source results.
	 *
	 * @access public
	 * @return array
	 * @final
	 */
	final public function results() {
		$this->cache();

		return $this->getCacheEngine()->get(self::CACHE_KEY);
	}

	/**
	 * Return the schema structure.
	 *
	 * @access public
	 * @param string $field
	 * @return array
	 * @final
	 */
	final public function schema($field = null) {
		return isset($this->_schema[$field]) ? $this->_schema[$field] : $this->_schema;
	}

	/**
	 * Set the API key.
	 *
	 * @access public
	 * @param string $public
	 * @param string $private
	 * @return void
	 * @final 
	 */
	final public function setApiKey($public, $private) {
		$this->_config['publicKey'] = (string) $public;
		$this->_config['privateKey'] = (string) $private;
	}

	/**
	 * Set the API URL.
	 *
	 * @access public
	 * @param string $url
	 * @return void
	 * @final
	 */
	final public function setApiUrl($url) {
		$this->_config['apiUrl'] = (string) $url;
	}

	/**
	 * Set the cache engine.
	 *
	 * @access public
	 * @param CacheInterface $engine
	 * @return void
	 * @final
	 */
	final public function setCacheEngine(CacheInterface $engine) {
		$this->__cache = $engine;
	}

	/**
	 * Set the region value; must be one of the supported regions.
	 *
	 * @access public
	 * @param string $region
	 * @return void
	 * @final
	 */
	final public function setRegion($region) {
		$region = strtolower($region);

		if (!in_array($region, Blizzard::getSupportedRegions())) {
			throw new ApiException(sprintf('The region %s is not supported.', $region));
		}

		$this->_config['region'] = $region;
	}

	/**
	 * Set multiple values of the query string using an array.
	 * A whitelist can be provided to only accept specific keys.
	 *
	 * @access public
	 * @param array $params
	 * @return void
	 * @final
	 */
	final public function setQuery(array $params) {
		$whitelist = $this->getQueryWhitelist();

		if (!empty($whitelist) && !empty($params)) {
			$params = array_filter(array_intersect_key($params, array_flip($whitelist)));
		}

		if (!empty($params)) {
			foreach ($params as $key => $value) {
				$this->setQueryParam($key, $value);
			}
		}
	}

	/**
	 * Set a single value into the query string.
	 * A whitelist can be provided to only accept specific keys.
	 *
	 * @access public
	 * @param string $param
	 * @param mixed $value
	 * @return void
	 * @final
	 */
	final public function setQueryParam($param, $value) {
		$whitelist = $this->getQueryWhitelist();

		if (!empty($whitelist) && !in_array($param, $whitelist)) {
			throw new ApiException(sprintf('Query param %s is not supported.', $param));
		}

		if (!empty($value)) {
			$this->_query[$param] = $value;
		}
	}

	/**
	 * Set the whitelist of allowed query params.
	 *
	 * @access public
	 * @param array $params
	 * @return void
	 * @final
	 */
	final public function setQueryWhitelist(array $params) {
		$this->_whitelist = $params;
	}

}