<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Creators
 * @package App\Models
 */
class Creators extends Model
{
    /**
     * @var string
     */
    protected $table = 'creators';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'is_active',
        'created_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comments', 'creators_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Posts', 'creators_id');
    }

    /**
     * @return mixed
     */
    public function getCreators()
    {
        $query = $this;
        return $query->paginate(5);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCreator($id)
    {
        return $this->find($id);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getCreatorByName($name)
    {
        return $this->where('name', $name);
    }

    /**
     * @param $input
     * @return mixed
     */
    public function createCreator($input)
    {
        return $this->create($input->all());
    }

    /**
     * @param $id
     * @param $input
     * @return bool
     */
    public function updateCreator($id, $input)
    {
        $updated = $this->find($id)->update($input);
        $creator = $this->find($id);

        if($updated) {
            return $creator;
        }

        return false;
    }
}