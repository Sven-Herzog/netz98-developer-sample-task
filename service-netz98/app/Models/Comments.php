<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comments
 * @package App\Models
 */
class Comments extends Model
{
    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var array
     */
    protected $fillable = [
        'comment_title',
        'comment_link',
        'comment_description',
        'comment_content', 
        'creators_id',
        'post_id',
        'created_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function posts()
    {
        return $this->belongsTo('App\Models\Posts', 'id');
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
    public function getComments()
    {
        $query = $this;
        return $query->paginate(20);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getComment($id)
    {
        return $this->find($id);
    }

    /**
     * @param $input
     * @return mixed
     */
    public function createComment($input)
    {
        return $this->create($input->all());
    }

    /**
     * @param $id
     * @param $input
     * @return bool
     */
    public function updateComment($id, $input)
    {
        $updated = $this->find($id)->update($input);
        $comment = $this->find($id);

        if($updated) {
            return $comment;
        }

        return false;
    }
}