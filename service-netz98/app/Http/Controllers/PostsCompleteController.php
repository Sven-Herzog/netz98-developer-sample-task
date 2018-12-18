<?php 

namespace App\Http\Controllers;

use App\Models\Creators;
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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
	public function getPosts()
	{
        $return = [];

		$posts = $this->posts->getPostsComplete();
		if($posts) {
            foreach ($posts as $key => $post) {
                $return[$key] = [
                    'post' => [],
                    'creator' => [],
                    'comments' => []
                ];

                $return[$key]['post']['title'] = $post->post_title;
                $return[$key]['post']['description'] = $post->post_description;
                $return[$key]['post']['content'] = $post->post_content;
                $return[$key]['post']['link'] = $post->post_link;

                if ($post->creators_id > 0) {
                    $return[$key]['creator']['name'] = Creators::find($post->creators_id)->name;
                }

                if ($post->post_comments_count > 0) {
                    $comments = $post->comments;
                    foreach ($comments as $keyComments => $comment) {
                        $return[$key]['comments'][$keyComments]['title'] = $comment['comment_title'];
                        $return[$key]['comments'][$keyComments]['description'] = $comment['comment_description'];
                        $return[$key]['comments'][$keyComments]['content'] = $comment['comment_content'];
                        $return[$key]['comments'][$keyComments]['link'] = $comment['comment_link'];

                        if ($comment['creators_id'] > 0) {
                            $return[$key]['comments'][$keyComments]['creator']['name'] = Creators::find($comment['creators_id'])->name;
                        }
                    }
                }
            }

		    return Response::json($return);
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
        if($post) {
            $return = [
                'post' => [],
                'creator' => [],
                'comments' => []
            ];

            $return['post']['title'] = $post['post_title'];
            $return['post']['description'] = $post['post_description'];
            $return['post']['content'] = $post['post_content'];
            $return['post']['link'] = $post['post_link'];

            if ($post['creators_id'] > 0) {
                $return['creator']['name'] = Creators::find($post['creators_id'])->name;
            }

            if ($post['post_comments_count'] > 0) {
                $comments = $post->comments;
                foreach ($comments as $keyComments => $comment) {
                    $return['comments'][$keyComments]['title'] = $comment['comment_title'];
                    $return['comments'][$keyComments]['description'] = $comment['comment_description'];
                    $return['comments'][$keyComments]['content'] = $comment['comment_content'];
                    $return['comments'][$keyComments]['link'] = $comment['comment_link'];

                    if ($comment['creators_id'] > 0) {
                        $return['comments'][$keyComments]['creator']['name'] = Creators::find($comment['creators_id'])->name;
                    }
                }
            }

            return Response::json($return);
        }

        return Response::internalError('Unable to get the posts');
	}
}   