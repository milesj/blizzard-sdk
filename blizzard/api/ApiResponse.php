<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

namespace blizzard\api;

/**
 * Instantiated upon every API request to process the current response.
 * Will convert JSON strings into usable arrays.
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard.api
 */
class ApiResponse {

	/**
	 * Headers returned from the cURL request.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_headers;

	/**
	 * The raw response; usually a JSON string.
	 *
	 * @access protected
	 * @var string
	 */
	protected $_raw;

	/**
	 * The processed response; usually an array.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_response;

	/**
	 * Load and parse the response and headers.
	 *
	 * @access public
	 * @param string $response
	 * @param array $headers
	 * @return void
	 * @constructor
	 */
	public function __construct($response, $headers) {
		$this->_raw = $response;
		$this->_headers = $headers;

		if (strpos($headers['content_type'], 'application/json') !== false) {
			$this->_response = json_decode($response, true);
		} else {
			$this->_response = (array) $response;
		}
	}

	/**
	 * Return a header value.
	 *
	 * @access public
	 * @param string $key
	 * @return string|null
	 */
	public function header($key) {
		return isset($this->_headers[$key]) ? $this->_headers[$key] : null;
	}

	/**
	 * Return all headers.
	 *
	 * @access public
	 * @return array
	 */
	public function headers() {
		return $this->_headers;
	}

	/**
	 * Return the raw response.
	 *
	 * @access public
	 * @return string
	 */
	public function raw() {
		return $this->_raw;
	}

	/**
	 * Return the processed response.
	 *
	 * @access public
	 * @return array
	 */
	public function response() {
		return $this->_response;
	}

}