@extends('bbs::layouts.master')

@section('content')
    <h1>{{ config('app.name') }}</h1>

    <p>
        您正在通过邮箱修改密码，验证码：<b><a href="javascript:;">{{ $code }}</a></b>

        <small>验证码有效期：1小时。</small>
    </p>

    <p>
        如果这不是您本人的操作，请忽略此邮件。
    </p>

    © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endsection
