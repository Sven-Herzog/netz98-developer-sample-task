<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * Routes for Creator
 */
$router->group(['prefix' => 'creator', 'namespace' => '\App\Http\Controllers'], function() use ($router) {
    $router->get('/', ['uses' => 'CreatorsController@getCreators']);
    $router->get('{id}/posts', ['uses' => 'CreatorsController@getCreatorPosts']);
    $router->get('{id}/posts/{postId}', ['uses' => 'CreatorsController@getCreatorPost']);
    $router->get('{id}/comments', ['uses' => 'CreatorsController@getCreatorComments']);
    $router->get('{id}/comments/{commentId}', ['uses' => 'CreatorsController@getCreatorComment']);
    $router->get('{id}', ['uses' => 'CreatorsController@getCreator']);
    $router->post('/', ['uses' => 'CreatorsController@createCreator']);
    $router->delete('{id}', ['uses' => 'CreatorsController@deleteCreator']);
    $router->put('{id}', ['uses' => 'CreatorsController@updateCreator']);
});

/**
 * Routes for Posts
 */
$router->group(['prefix' => 'posts', 'namespace' => '\App\Http\Controllers'], function() use ($router) {
    $router->get('/', ['uses' => 'PostsController@getPosts']);
    $router->get('{id}', ['uses' => 'PostsController@getPost']);
    $router->post('/', ['uses' => 'PostsController@createPost']);
    $router->delete('{id}', ['uses' => 'PostsController@deletePost']);
    $router->put('{id}', ['uses' => 'PostsController@updatePost']);
});

/**
 * Routes for Comments
 */
$router->group(['prefix' => 'comments', 'namespace' => '\App\Http\Controllers'], function() use ($router) {
    $router->get('/', ['uses' => 'CommentsController@getComments']);
    $router->get('{id}', ['uses' => 'CommentsController@getComment']);
    $router->post('/', ['uses' => 'CommentsController@createComment']);
    $router->delete('{id}', ['uses' => 'CommentsController@deleteComment']);
    $router->put('{id}', ['uses' => 'CommentsController@updateComment']);
});

/**
 * Routes for PostsComplete
 */
$router->group(['prefix' => 'v1'], function () use ($router) {

    $router->get('posts', ['uses' => 'PostsCompleteController@getPosts']);
    $router->get('posts/{id}', ['uses' => 'PostsCompleteController@getPost']);

});