# Blizzard SDK v0.1.0 #

An official Blizzard PHP SDK to interact with the World of Warcraft API.

## Requirements ##

* PHP 5.3.x
* JSON - http://php.net/manual/book.json.php
* cURL - http://php.net/manual/book.curl.php

### Manual Installation ###

Download and extract the contents and the resulting "blizzard" folder to your servers vendors directory.

	http://github.com/milesj/php-blizzard_sdk/zipball/master

### GIT Installation ###

Clone the repo into your servers vendors directory.

	git clone git://github.com/milesj/php-blizzard_sdk.git blizzard

## Usage ##

### 1 - Setting your region and API key ###

You may set your API key (optional) and region (defaults to "us") globally by using the Blizzard class or you can overwrite on a per instance basis. These settings will be used for all API calls.

	// Globally
	blizzard\Blizzard::setApiKey('yourApiKey');
	blizzard\Blizzard::setRegion('us');

	// Instance
	$realm = new blizzard\api\wow\RealmApi(array(
		'apiKey' => 'yourApiKey',
		'region' => 'us'
	));

### 2 - Using the source APIs ###

Each type of API call will have an associated class: realm, character, guild, etc. You may instantiate any of these classes to fetch the data you desire. Once instantiated, use the results() method to return the default result set.

	$realm = new blizzard\api\wow\RealmApi();
	$results = $realm->results();

### 3 - Filtering the results ###

Each class will have a set of filter methods built in that you may use to filter down the result set. Additionally, you can use the other built in methods to modify the result set to your needs.

	use blizzard\api\wow\RealmApi;

	$realm = new RealmApi();
	$results = $realm->filterByStatus(RealmApi::STATUS_DOWN);
	$results = $realm->filterByName(array('Lightbringer', 'Tichondrius'));

### 4 - Caching your data ###

By default, every API call will be cached in memory depending on the filter parameters provided. This speeds up the data mining process by not triggering the same HTTP request over and over for the exact same data. Cached items will last for the duration of the HTTP request. If you want to keep an indefinite cache, you can provide your own caching engine. Your custom caching engine must implement the blizzard\cache\CacheInterface.

	// Custom cache engine
	class MemcacheEngine extends blizzard\cache\CacheInterface { 
		// Overwrite get(), set(), has(), key()
	}

	// Use your class
	$realm = new blizzard\api\wow\RealmApi();
	$realm->setCacheEngine(new MemcacheEngine());

## Todo ##

* Character API
* Guild API
* Arena Team API
* Arena Ladder API
* Item API
* Any API
