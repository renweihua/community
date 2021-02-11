<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Traits\Json;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BbsController extends Controller
{
    use Json;

    protected $service;
    protected $guard      = 'user';
    protected $user;
    protected $login_user = 0;

    public function __construct()
    {
        $this->user = request()->user($this->guard);
        $this->login_user = $this->user->user_id ?? 0;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('bbs::index');
    }
}
