<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::all();

        if($siswa) {
            return response()->json(
                [
                    "message" => 'Success',
                    "data" => $siswa
                ]
            );
        } else {
            return response()->json(['message' => 'Data not found']);
        }
    }  

    public function show($id)
    {
        $siswa = Siswa::find($id);

        if($siswa) {
            return response()->json(
                [
                    "message" => 'Success',
                    "data" => $siswa
                ]
            );
        } else {
            return response()->json(["message" => 'Data not found']);
        }
    }
}
