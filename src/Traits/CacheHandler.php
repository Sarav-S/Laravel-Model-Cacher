<?php

namespace Sarav\Traits;

use Sarav\Exceptions\CacheNameException;

trait CacheHandler {

	/**
	 * Gets the cache name.
	 *
	 * @throws \CacheNameException  
	 * Throws error when cache name isn't defined
	 *
	 * @return mixed
	 */
	public function getCacheName() {

		if (isset($this->cacheName)) {
			return $this->cacheName;
		}

		throw new CacheNameException("Cache name is not defined on ".self::class);
	}

	/**
	 * Determines if individual cache enabled.
	 *
	 * @return  boolean  TRUE if individual cache enabled, FALSE otherwise.
	 */
	public function isIndividualCacheEnabled() {
        return isset($this->individualCache) ? $this->individualCache : false;
    }

    /**
     * Determines if cache all enabled.
     *
     * @return  boolean  TRUE if cache all enabled, FALSE otherwise.
     */
    public function isCacheAllEnabled() {
        return isset($this->cacheAll) ? $this->cacheAll : false;
    }

    /**
     * Fetches records and updates cache if no cache records 
     * found initially. You can override the function with custom
     * query
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function fetchRecords() {
    	return self::all();
    }
}