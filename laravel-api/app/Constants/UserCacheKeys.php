<?php

namespace App\Constants;

class UserCacheKeys extends CacheKeys
{
    // 更改登录密码时，通过邮箱：发送验证码的key
    const CHANGE_PASSWORD_EMAIL_CODE = 'change_password_email_code:';
}
