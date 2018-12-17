<?php 

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Posts;
use App\Helpers\Response;

/**
 * Class PostsController
 * @package App\Http\Controllers
 */
class PostsController extends Controller
{
    /**
     * @var Posts
     */
    protected $posts;

    /**
     * @var Request
     */
    protected $request;

    /**
     * PostsController constructor.
     * @param Posts $posts
     * @param Request $request
     */
    public function __construct(Posts $posts, Request $request)
    {
        $this->posts = $posts;
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
	public function getPosts()
	{
		$posts = $this->posts->getPosts();
		if($posts) {
            return Response::json($posts);
        }

        return Response::internalError('Unable to get the posts');
	}

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
	public function getPost($id)
	{
		$post = $this->posts->getPost($id);
        if( !$post ) {
            return Response::notFound('Post not found');
        }

        return Response::json($post);
	}

    /**
     * @return \Illuminate\Http\JsonResponse
     */
	public function createPost()
	{
		$validator = Validator::make($this->request->all(), [
            'post_title'		=> 'required',
            'post_content'		=> 'required',
            'user_id'    		=> 'required',
        ]);

        if ($validator->errors()->count()) {
            return Response::badRequest($validator->errors());
        }

        $post = $this->posts->createPost($this->request);
        if ($post) {
            return Response::created($post);
        } 

        return Response::internalError('Unable to create the Post');
	}

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
	public function deletePost($id)
    {
        $post = $this->posts->find($id);
        if(!$post) {
            return Response::notFound('Post not found');
        }

        if( !$post->delete() ) {
            return Response::internalError('Unable to delete the post');
        }

        return Response::deleted();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePost($id)
    {
        $post = $this->posts->find($id);
        if(!$post) {
            return Response::notFound('Post not found');
        }
        
        $validator = Validator::make($this->request->all(), [
            'post_title'		=> 'required',
            'post_content'		=> 'required',
        ]);

        if ($validator->errors()->count()) {
            return Response::badRequest($validator->errors());
        }

        $post = $this->posts->updatePost($id, $this->request->all());
        if ($post) {
            return Response::json($post);
        }

        return Response::internalError('Unable to update the post');
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPosts($user_id)
    {
        return Response::json($this->posts->getUserPosts($user_id));
    }
}   