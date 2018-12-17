<?php 

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Comments;

use App\Helpers\Response;

/**
 * Class CommentsController
 * @package App\Http\Controllers
 */
class CommentsController extends Controller
{
    /**
     * @var Comments
     */
	protected $comments;

    /**
     * @var Request
     */
    protected $request;

    /**
     * CommentsController constructor.
     * @param Comments $comments
     * @param Request $request
     */
    public function __construct(Comments $comments, Request $request)
    {
        $this->comments = $comments;
        $this->request = $request; 
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComments()
    {
        $comments = $this->comments->getComments();
        if($comments) {
            return Response::json($comments);
        }

        return Response::internalError('Comments not found');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComment($id)
    {
        $comment = $this->comments->getComment($id);
        if( !$comment ) {
            return Response::notFound('Comment not found');
        }

        return Response::json($comment);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function createComment()
    {
        $validator = Validator::make($this->request->all(), [
            'comment_content'	=> 'required',
            'user_id'	=> 'required', 
	        'post_id'	=> 'required',
        ]);

        if ($validator->errors()->count()) {
            return Response::badRequest($validator->errors());
        }

        $comment = $this->comments->createComment($this->request);
        if ($comment) {
            return Response::created($comment);
        } 

        return Response::internalError('Unable to create the comment');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment($id)
    {
        $comment = $this->comments->find($id);
        if(!$comment) {
            return Response::notFound('Comment not found');
        }

        if( !$comment->delete() ) {
            return Response::internalError('Unable to delete the comment');
        }

        return Response::deleted();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateComment($id)
    {
        $comment = $this->comments->find($id);
        if(!$comment) {
            return Response::notFound('Comment not found');
        }
        
        $validator = Validator::make($this->request->all(), [
            'comment_content'	=> 'required',
            'user_id'	=> 'required', 
	        'post_id'	=> 'required',
        ]);

        if ($validator->errors()->count()) {
            return Response::badRequest($validator->errors());
        }

        $comment = $this->comments->updateComment($id, $this->request->all());
        if ($comment) {
            return Response::json($comment);
        }

        return Response::internalError('Unable to update the comment');
    }
}