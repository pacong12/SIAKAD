<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil semua info dengan pagination
        $items = Info::orderBy('tanggal', 'desc')->paginate(5);

        return view('pages.admin.guru.info', compact('items'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $item = Info::where('slug', $slug)->firstOrFail();

        return view('pages.admin.guru.infodetail', compact('item'));
    }
} 