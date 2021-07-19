<?php

namespace App\Constants;

class UserCacheKeys
{
    // 默认的缓存时长：默认为1小时
    const KEY_DEFAULT_TIMEOUT = 3600;

    // 邮箱注册账户：发送验证码的key
    const REGISTER_EMAIL_CODE = 'register_email_code:';

    // 登录会员，重新绑定邮箱：发送验证码的key
    const BIND_EMAIL_CODE = 'bind_email_code:';

    // 更改登录密码时，通过邮箱：发送验证码的key
    const CHANGE_PASSWORD_EMAIL_CODE = 'change_password_email_code:';

    const USER_LOGIN_TOKEN = 'users_token:';
    // users_token：token:会员基本信息（List）
    const USER_LOGIN_TOKEN_LIST = 'users_token_list:';
}
