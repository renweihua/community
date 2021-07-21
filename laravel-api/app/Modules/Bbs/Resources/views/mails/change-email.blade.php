@component('mail::message')

<h1>Hello,</h1>

请点击此按钮以确定修改账户邮箱：

@component('mail::button', ['url' => $user->getChangeEmailLink($token)])
    确定修改
@endcomponent

<small>本邮件有效期为 7 天，请在过期前激活。</small>

Thanks,<br>
{{ cnpscy_config('site_web_title') }}
@endcomponent
