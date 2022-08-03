<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Video;

class VideoController extends Controller
{   
    public function index()
    {
        $videos = Video::select('id', 'judul')->get();

        return view('index', compact('videos'));
    }

    public function store(Request $request)
    {
        $rules = [
            'judul' => 'required|unique:videos',
            'video' => 'required|mimes:mp4, webm',
        ];

        $text = [
            'judul.required' => 'Judul tidak boleh kosong!',
            'judul.unique' => 'Judul sudah terpakai!',
            'video.required' => 'Video tidak boleh kosong',
            'video.mimes' => 'Format video : mp4, webm',
        ];

        $validate = Validator::make($request->all(), $rules, $text);

        if ($validate->fails()){
            return response()->json(['success'=> 0, 'errors' => $validate->errors()], 422);
        }else{
            $title = $request->judul.'.'.$request->video->getClientOriginalExtension();
            $request->file('video')->storeAs('videos', $title, 'public');
            $data = new Video();
            $data->judul = $title;
            $data->save();
            return response()->json(['text' => 'Video Berhasil Di Upload'], 200);
        }
    }
}
