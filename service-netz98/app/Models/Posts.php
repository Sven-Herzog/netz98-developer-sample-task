<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Posts
 * @package App\Models
 */
class Posts extends Model
{
    /**
     * @var string
     */
    protected $table = 'posts';

    /**
     * @var array
     */
    protected $fillable = [
        'post_title',
        'post_link',
        'post_description',
        'post_content',
        'post_comments_count',
        'creators_id',
        'created_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comments', 'post_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creators()
    {
        return $this->belongsTo('App\Models\Creators', 'id');
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        $query = $this;
        return $query->paginate(5);
    }

    /**
     * @return mixed
     */
    public function getPostsComplete()
    {
        $query = $this;
        return $query->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPost($id)
    {
        return $this->find($id);
    }

    /**
     * @param $input
     * @return mixed
     */
    public function createPost($input)
    {
        return $this->create($input->all());
    }

    /**
     * @param $id
     * @param $input
     * @return bool
     */
    public function updatePost($id, $input)
    {
        $updated = $this->find($id)->update($input);
        $post = $this->find($id);

        if($updated) {
            return $post;
        }

        return false;
    }
}