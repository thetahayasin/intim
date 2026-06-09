<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Associate;

class AssociateProfileController extends Controller
{
    public function index()
    {
        //get associate id
        $associateId = Auth::guard('associate')->user()->id;

        $data = Associate::where('id', $associateId)->first();
        //dd($data);

        return view('associate.profile', compact('data'));
    }
}
