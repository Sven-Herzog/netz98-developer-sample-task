<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Creators;
use App\Helpers\Response;

/**
 * Class CreatorsController
 * @package App\Http\Controllers
 */
class CreatorsController extends Controller
{
    /**
     * @var Creators
     */
    protected $creators;

    /**
     * @var Request
     */
    protected $request;

    /**
     * CreatorsController constructor.
     * @param Creators $creators
     * @param Request $request
     */
    public function __construct(Creators $creators, Request $request)
    {
        $this->creators = $creators;
        $this->request = $request; 
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCreators()
    {
        $creators = $this->creators->getCreators();

        if($creators) {
            return Response::json($creators);
        }

        return Response::internalError('Unable to get the creators');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser($id)
    {
        $creator = $this->creators->getCreator($id);

        if(!$creator) {
            return Response::notFound('Creator not found');
        }

        return Response::json($creator);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCreatorPosts($id)
    {
        $creator = $this->creators->getCreator($id);

        if(!$creator) {
            return Response::notFound('Creator not found');
        }

        return $creator->posts;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCreatorComments($id)
    {
        $creator = $this->creators->getCreator($id);

        if(!$creator) {
            return Response::notFound('Creator not found');
        }

        return $creator->comments;
    }

    /**
     * @param $id
     * @param $commentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCreatorComment($id, $commentId)
    {
        $creator = $this->creators->getCreator($id);

        if(!$creator) {
            return Response::notFound('Creator not found');
        }

        return $creator->comments()->find($commentId);
    }

    /**
     * @param $id
     * @param $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCreatorPost($id, $postId)
    {
        $creator = $this->creators->getCreator($id);

        if(!$creator) {
            return Response::notFound('Creator not found');
        }

        return $creator->posts()->find($postId);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCreator()
    {
        $this->request->validate([
            'title' => 'required|unique:posts|max:255',
            'author.name' => 'required',
            'author.description' => 'required',
        ]);

        $validator = Validator::make($this->request->all(), [
            'name'      => 'required|unique:creators|max:255',
        ]);

        if ($validator->errors()->count()) {
            return Response::badRequest($validator->errors());
        }

        $creator = $this->creators->createCreator($this->request);

        if ($creator) {
            return Response::created($creator);
        } 

        return Response::internalError('Unable to create the user');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCreator($id)
    {
        $creator = $this->creators->find($id);

        if(!$creator) {
            return Response::notFound('Creator not found');
        }

        if(!$creator->delete()) {
            return Response::internalError('Unable to delete the creator');
        }

        return Response::deleted();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCreator($id)
    {
        $creator = $this->creators->find($id);

        if(!$creator) {
            return Response::notFound('Creator not found');
        }

        $creator = $this->creators->updateCreator($id, $this->request->all());

        if ($creator) {
            return Response::json($creator);
        }

        return Response::internalError('Unable to update the creator');
    }
}