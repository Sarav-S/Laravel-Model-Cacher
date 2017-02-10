<?php

namespace Sarav\Observers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EventObserver
{
    /**
     * This will hold the created/updated/deleted
     * record
     */
    protected $record;

    /**
     * Listen to the Model saved event.
     *
     * @param  Model  $record
     * @return void
     */
    public function saved(Model $record)
    {
        $this->record = $this->getFullRecord($record);

        $this->assignCache();
    }

    /**
     * Listen to the Model deleted event.
     *
     * @param  Model  $record
     * @return void
     */
    public function deleted(Model $record)
    {
        $this->record = $record;

        if ($this->record->isIndividualCacheEnabled()) {
            cache()->forget($this->getCacheName());
        } else {
            $this->setCache($this->removeFromCache($this->record));
        }
    }

    /**
     * Assigns the cache
     *
     * @return  mixed
     */
    protected function assignCache()
    {
        if ($this->record->isIndividualCacheEnabled()) {
            return $this->setCache($this->record);
        }

        if ($this->record->isCacheAllEnabled()) {

            if (cache($this->getCacheName())) {
                $records = $this->updateExistingCache($this->record);
            } else {
                $records = $this->record->fetchRecords();
            }
            
            return $this->setCache($records);
        }
    }

    /**
     * Checks if existing caches has given record, if so it 
     * removes and appends the newly created/updated record.
     *
     * @return Collection
     */
    protected function updateExistingCache() {

        return $this->removeFromCache($this->record)->push($this->record);
    }

    /**
     * Removes record from cache.
     *
     * @return  Collection
     */
    protected function removeFromCache() {

        $record = $this->record;

        if (!count(cache($this->getCacheName()))) {
            $records = $record->fetchRecords();
            return collect($records);
        }

        return collect(cache($this->getCacheName()))->reject(function($value) use ($record) {
            return $value->{$record->getKeyName()} === $record->{$record->getKeyName()};
        });
    }

    /**
     * Sets the cache.
     *
     * @param  Model  $record
     *
     * @return boolean
     */
    protected function setCache($record) {

        $expiresAt = Carbon::now()->addMinutes(config('cacheable.minutes'));

        return cache()->put($this->getCacheName(), $record, $expiresAt);
    }

    /**
     * Gets the full record. This query is performed just because when data
     * updated, we won't be getting full record data. So, performing query
     * to get full data.
     *
     * @param   Model  $record  The record
     *
     * @return  Model  The full record.
     */
    protected function getFullRecord($record) {

        $class = get_class($record);
        
        return $class::find($record->{$record->getKeyName()});
    }

    /**
     * Gets the cache name.
     *
     * @return string
     */
    public function getCacheName() {
        return ($this->record->isCacheAllEnabled()) ? 
            $this->record->getCacheName() : 
            $this->record->getCacheName().'.'.$this->record->{$this->record->getKeyName()};
    }
}