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
 * API for realm status. Allows you to view the type of realm (pvp, pve, rp, etc),
 * the server population, the server status (up or down), if a queue is currently happening 
 * and the name of the server.
 *
 *		use blizzard\api\wow\RealmApi;
 *
 *		$realm = new RealmApi();
 *		$realm->filterByName(array('Lightbringer', 'Tichondrius'));
 *		$realm->filterByPopulation(RealmApi::POP_HIGH);
 *		$realm->filterByQueue(RealmApi::QUEUE_YES);
 *		$realm->filterByStatus(RealmApi::STATUS_DOWN);
 *		$realm->filterByType(RealmApi::TYPE_PVE);
 *
 * @author		Miles Johnson
 * @version		0.1.0
 * @package		blizzard.api.wow
 */
class RealmApi extends WowApiAbstract {

	/**
	 * Constants for realm type.
	 */
	const TYPE_PVE = 'pve';
	const TYPE_PVP = 'pvp';
	const TYPE_RP = 'rp';
	const TYPE_RPPVP = 'rppvp';

	/**
	 * Constants for server population.
	 */
	const POP_LOW = 'low';
	const POP_MEDIUM = 'medium';
	const POP_HIGH = 'high';

	/**
	 * Constants for server status.
	 */
	const STATUS_UP = 1;
	const STATUS_DOWN = 0;

	/**
	 * Constants for queue status.
	 */
	const QUEUE_YES = 1;
	const QUEUE_NO = 0;

	/**
	 * Realm data structure.
	 *
	 *		name <string> - Name of the realm according to region.
	 *		slug <string> - URL friendly version of the english name.
	 *		type <enum:string> - Enum mapping of possible types.
	 *		queue <boolean> - True if the realm currently requires a queue, else false.
	 *		status <boolean> - True if the realm is up, else false for down.
	 *		population <enum:string> - Enum mapping of population levels.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_schema = array(
		'type' => array(
			self::TYPE_PVE,
			self::TYPE_PVP,
			self::TYPE_RP,
			self::TYPE_RPPVP
		),
		'queue' => array(
			self::QUEUE_YES,
			self::QUEUE_NO
		),
		'status' => array(
			self::STATUS_UP,
			self::STATUS_DOWN
		),
		'population' => array(
			self::POP_LOW,
			self::POP_MEDIUM,
			self::POP_HIGH
		)
	);

	/**
	 * Append realm container to API URL.
	 *
	 * @access public
	 * @param array $config
	 * @return void
	 * @constructor
	 */
	public function __construct(array $config = array()) {
		parent::__construct($config);

		$this->setApiUrl($this->getApiUrl() .'realm/status');
	}

	/**
	 * Store a cache of the base results. We will use this cache to filter upon
	 * instead of doing subsequent HTTP requests.
	 *
	 * @access public
	 * @return boolean
	 * @final
	 */
	final public function cache() {
		if ($this->getCacheEngine()->has(self::CACHE_KEY)) {
			return true;
		}

		$request = $this->request();
		$results = $request->response();

		if (!empty($results)) {
			$this->getCacheEngine()->set(self::CACHE_KEY, $results['realms']);
			return true;
		}

		return false;
	}

	/**
	 * Get realm(s) based on name.
	 *
	 * @access public
	 * @param string|array $name
	 * @return array
	 */
	public function filterByName($name) {
		if (empty($name)) {
			throw new WowApiException(sprintf('Name required for %s.', __METHOD__));
		}

		return $this->filterBy(__METHOD__, 'name', $name);
	}

	/**
	 * Get realm(s) based on population level.
	 *
	 * @access public
	 * @param string $population
	 * @return array
	 */
	public function filterByPopulation($population = self::POP_LOW) {
		if (!in_array($population, $this->schema('population'))) {
			throw new WowApiException(sprintf('Invalid population type for %s.', __METHOD__));
		}

		return $this->filterBy(__METHOD__, 'population', $population);
	}

	/**
	 * Get realm(s) based on queue status.
	 *
	 * @access public
	 * @param string $queue
	 * @return array
	 */
	public function filterByQueue($queue = self::QUEUE_NO) {
		if (!in_array($queue, $this->schema('queue'))) {
			throw new WowApiException(sprintf('Invalid queue status for %s.', __METHOD__));
		}

		return $this->filterBy(__METHOD__, 'queue', $queue);
	}

	/**
	 * Get realm(s) based on server status.
	 *
	 * @access public
	 * @param string $status
	 * @return array
	 */
	public function filterByStatus($status = self::STATUS_UP) {
		if (!in_array($status, $this->schema('status'))) {
			throw new WowApiException(sprintf('Invalid server status for %s.', __METHOD__));
		}

		return $this->filterBy(__METHOD__, 'status', $status);
	}

	/**
	 * Get realm(s) based on realm type.
	 *
	 * @access public
	 * @param string $type
	 * @return array
	 */
	public function filterByType($type = self::TYPE_PVP) {
		if (!in_array($type, $this->schema('type'))) {
			throw new WowApiException(sprintf('Invalid realm type for %s.', __METHOD__));
		}

		return $this->filterBy(__METHOD__, 'type', $type);
	}

}