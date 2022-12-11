<?php

namespace App\Repositories;

use App\Models\Follower;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class FollowerRepository
{
    /**
     * model instance
     * 
     * @var $model
     */
    protected $model;

    /**
     * cache module
     * 
     * @var const CACHE_MODULE
     */
    const CACHE_MODULE = 'followers';

    function __construct(Follower $model)
    {
        $this->model = $model;
    }

    /**
     * filter registers os this object model
     * 
     * @param array $filters
     * @param int $page
     * 
     * @return Collection $data
     */
    public function getAll(array $filters)
    {
        $query = $this->model->with('pictures', 'socials');
        //return Cache::rememberForever('creators', function () use ($filters) {
            if(isset($filters['search'])) {
                $query = $query->where("name", 'LIKE', '%'.$filters['search'].'%');
            }

            return  $query->cursorPaginate(50);
        //});
    }

    /**
     * insert new instance os this object on database
     * 
     * @param array $data
     * 
     * @return object $model
     */
    public function store(array $data)
    {
        Cache::forget(self::CACHE_MODULE);

        $model = $this->model->create($data);

        return $model;
    }

    /**
     * get one instance of this object and its relationships
     * 
     * @param string $identify
     * 
     * @return object $model
     */
    public function getByIdentify(string $identify)
    {
        //return Cache::rememberForever('creators'.$identify, function () use ($identify) {
            return $this->model->with('pictures', 'socials')->where('uuid', $identify)->firstOrFail();
        //});
    }

    /**
     * Update a instance of this object
     * 
     * @param array $data
     * @param string identify
     * 
     * @return object $model
     */
    public function update(array $data, string $identify)
    {
        $model = $this->getByIdentify($identify); 

        $model->update($data);

        Cache::forget('creators');
        Cache::forget('creators'.$identify);

        return $model;
    }

    /**
     * Delete a instance of this object
     * 
     * @param string identify
     * 
     * @return object $model
     */
    public function delete(string $indetify)
    {
        $model = $this->getByIdentify($indetify); 

        $model->delete();

        Cache::forget('creators'.$indetify);
        Cache::forget('creators');
    }

    /**
     * Delete a instance of this object
     * 
     * @param string identify
     * 
     * @return object $model
     */
    public function deleteByCustomerAndCreatoridentify(
        string $creatorIdentify, string $customerIdentify
    ) 
    {
        $this->model = Follower::where("creator_identify", $creatorIdentify)
            ->where('customer_identify', $customerIdentify)->firstOrFail();

        $this->model->delete();
        //dd($creatorIdentify, $customerIdentify, $this->model);
        Cache::forget(self::CACHE_MODULE.$this->model->uuid);
        Cache::forget(self::CACHE_MODULE);

        $this->model->delete();
    }

     /**
     * Delete a instance of this object
     * 
     * @param string identify
     * 
     * @return object $model
     */
    public function findByCustomerAndCreatoridentify(
        string $creatorIdentify, string $customerIdentify
    ) 
    {
        $this->model = Follower::where("creator_identify", $creatorIdentify)
            ->where('customer_identify', $customerIdentify)->firstOrFail();

        $this->model;
    }
}
