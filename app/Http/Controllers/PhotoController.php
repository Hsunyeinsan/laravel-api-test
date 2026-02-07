<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhotoRequest $request)
    {
        $url=Storage::put('/',$request->file('image'));
        $photo=Photo::create(["url"=>$url]);
        return response()->json([
            "message"=>"Photo uploade succesfully",
            "data"=>new PhotoResource($photo)
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhotoRequest $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($url)
    {
        Storage::delete($url);
        Photo::where('url',$url)->delete();
        return response()->json([
            "message"=>"Photo delete successfully"
        ]);
    }
}
