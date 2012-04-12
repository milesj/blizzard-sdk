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
use blizzard\api\wow\DataApi;
use blizzard\api\wow\WowApiException;

include_once '../../tests.php';
include_once '../../../api/wow/DataApi.php';

try {
	// Instantiate the Data API
	$data = new DataApi();

	// Get classes
	debug($data->getClasses());

	// Get guild perks
	debug($data->getGuildPerks());

	// Get guild rewards
	debug($data->getGuildRewards());

	// Get races
	debug($data->getRaces());

	// Get item by ID
	debug($data->getItem(49623));
	
} catch (WowApiException $e) {
	debug($e->getMessage());
	
} catch (ApiException $e) {
	debug($e->getMessage());

} catch (Exception $e) {
	debug($e->getMessage());
}