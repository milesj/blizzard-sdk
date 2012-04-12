<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

use blizzard\Exception;
use blizzard\api\ApiException;
use blizzard\api\wow\RealmApi;
use blizzard\api\wow\WowApiException;

include_once '../../tests.php';
include_once '../../../api/wow/RealmApi.php';

try {
	// Instantiate the Realm API
	$realm = new RealmApi();

	// Grab a single realm
	debug($realm->filterByName('Lightbringer'));

	// Grab multiple realms
	debug($realm->filterByName(array('Lightbringer', 'Tichondrius')));

	// Grab realms by population
	debug($realm->filterByPopulation(RealmApi::POP_LOW));

	// Grab realms by with a queue
	debug($realm->filterByQueue(RealmApi::QUEUE_YES));

	// Grab realms based on server status
	debug($realm->filterByStatus(RealmApi::STATUS_DOWN));

	// Grab realms based on realm type
	debug($realm->filterByType());
	
} catch (WowApiException $e) {
	debug($e->getMessage());
	
} catch (ApiException $e) {
	debug($e->getMessage());

} catch (Exception $e) {
	debug($e->getMessage());
}