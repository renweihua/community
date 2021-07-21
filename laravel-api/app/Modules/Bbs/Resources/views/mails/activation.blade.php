@component('mail::message')

<h1>Hello,</h1>

请点击此按钮以激活您的账户：

@component('mail::button', ['url' => $user->getActivationLink($token)])
    立即激活
@endcomponent

<small>本邮件有效期为 7 天，请在过期前激活。</small>

Thanks,<br>
{{ cnpscy_config('site_web_title') }}
@endcomponent
