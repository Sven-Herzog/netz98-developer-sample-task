<?php 

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Posts;
use App\Helpers\Response;

/**
 * Class PostsCompleteController
 * @package App\Http\Controllers
 */
class PostsCompleteController extends Controller
{
    protected $posts;
    protected $request;

    /**
     * PostsCompleteController constructor.
     * @param Posts $posts
     * @param Request $request
     */
    public function __construct(Posts $posts, Request $request)
    {
        $this->posts = $posts;
        $this->request = $request;
    }


	public function getPosts()
	{
		$posts = $this->posts->getPostsComplete();
		if($posts) {
		    //print_r($posts);




		    return Response::json($posts);
        }

        return Response::internalError('Unable to get the posts');
	}

	public function getPost($id)
	{
		$post = $this->posts->getPost($id);
        if( !$post ) {
            return Response::notFound('Post not found');
        }

        return Response::json($post);
	}
}   