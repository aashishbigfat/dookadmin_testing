<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/blog_destinations',['uses'=>'API\DestinationBlogController@destinationListForBlog']);
Route::get('/career_listing',['uses'=>'API\DestinationBlogController@careerListing']);
Route::post('/career_details',['uses'=>'API\DestinationBlogController@careerDetails']);
Route::post('/resume_store',['uses'=>'API\DestinationBlogController@userResumeStore']);


