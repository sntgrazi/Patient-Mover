<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locais;


class LocaisController extends Controller
{
    public function index()
    {
        return Locais::all();
    }
}
