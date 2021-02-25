<?php

namespace App\Constants;

class UserCacheKeys
{
    // 默认的缓存时长：默认为1小时
    const KEY_DEFAULT_TIMEOUT = 36000;

    // 更改登录密码时，通过邮箱：发送验证码的key
    const CHANGE_PASSWORD_EMAIL_CODE = 'change_password_email_code:';

    // users_token：token:会员基本信息（List）
    const USER_LOGIN_TOKEN = 'users_token:';
}
