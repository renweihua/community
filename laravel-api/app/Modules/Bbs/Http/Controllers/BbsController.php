<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Traits\Json;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;

class BbsController extends Controller
{
    use Json;

    protected $service;

    public function getLoginUser()
    {
        return request()->attributes->get('login_user');
    }

    public function getLoginUserId(): int
    {
        return $this->getLoginUser()->user_id ?? 0;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        // $rsa = new \App\Library\Encrypt\Rsa;
        // var_dump($rsa->generate(1024));


        return view('bbs::index');
    }
}
