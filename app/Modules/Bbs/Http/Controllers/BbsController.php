<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Traits\Json;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;

class BbsController extends Controller
{
    use Json;

    protected $service;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('bbs::index');
    }
}
