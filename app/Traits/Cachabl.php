<?php

namespace App\Traits;
use Illuminate\Support\Facades\Cache;
trait Cachabl
{
    /*
    * retrieve data taking into account the cache
    * key        : unique identifier for the cached data entry
    * expiration : The validity period of cached data
    * queryCallback : Retrieve data from DB
    */
    public function getCachedData($key, $expiration, $queryCallback)
    {   
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $data = $queryCallback();
        Cache::put($key, $data, $expiration);
        return $data;

    }

    /*
    * use fo data updates
    * key  : unique identifier for the cached data entry
    * data : data you want to store in the cache
    */
    public function updateCachedData($key, $data)
    {
        Cache::put($key, $data, now()->addHours(1));
    }
}
