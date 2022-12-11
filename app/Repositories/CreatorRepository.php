<?php

namespace App\Repositories;

use App\Models\Creator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CreatorRepository
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
    const CACHE_MODULE = 'creators';

    function __construct(Creator $model)
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
        //return Cache::rememberForever('creators', function () use ($filters) {
            $query = $this->model->with('pictures', 'socials');
            $query->each(function($creator){
                return $creator->blocked_by_auth_customer == false;
            });
            //dd($query->get()->toArray());
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
        Cache::forget('creators');

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
}
