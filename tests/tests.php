<?php
/**
 * Blizzard API SDK
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2012, Miles Johnson
 * @license 	http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 */

use blizzard\Blizzard;

include_once '../blizzard/Blizzard.php';

// Set your API key and region globally
Blizzard::setApiKey('public', 'private');
Blizzard::setRegion('us');

/**
 * Debug a variable by printing it to the screen.
 *
 * @param mixed $data
 */
function debug($data) {
	echo '<pre>'. print_r($data, true) .'</pre>';
}