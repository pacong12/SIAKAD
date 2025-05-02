<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function tambahnilai(Request $request, $id)
    {
        // return $request->all();
        $siswa = \App\Siswa::findOrFail($id);
        $siswa->mapel()->updateExistingPivot($request->pk, ['uts' => $request->value, 'uas' => $request->value, 'status' => $request->value]);
    }
}
