<?php
/**
 *
 *
 * 针对于laravel的方法函数
 *
 *
 */
function get_request_post()
{
    return request()->isMethod('post');
}

function cnpscy_config(string $config_name = '', string $default = '')
{
    return config('cnpscy.' . $config_name, $default);
}

//快速修改.env文件
function modifyEnv(array $data)
{
    $envPath      = base_path() . DIRECTORY_SEPARATOR . '.env';
    $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
    $contentArray->transform(function ($item) use ($data)
    {
        foreach ($data as $key => $value) {
            if (str_contains($item, $key)) {
                return $key . '=' . $value;
            }
        }
        return $item;
    });
    $content = implode($contentArray->toArray(), "\n");
    File::put($envPath, $content);
}


function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return Str::limit($excerpt, $length);
}

function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

function model_link($title, $model, $prefix = '')
{
    // 获取数据模型的复数蛇形命名
    $model_name = model_plural_name($model);

    // 初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';

    // 使用站点 URL 拼接全量 URL
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    // 拼接 HTML A 标签，并返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

function model_plural_name($model)
{
    // 从实体中获取完整类名，例如：App\Models\User
    $full_class_name = get_class($model);

    // 获取基础类名，例如：传参 `App\Models\User` 会得到 `User`
    $class_name = class_basename($full_class_name);

    // 蛇形命名，例如：传参 `User`  会得到 `user`, `FooBar` 会得到 `foo_bar`
    $snake_case_name = Str::snake($class_name);

    // 获取子串的复数形式，例如：传参 `user` 会得到 `users`
    return Str::plural($snake_case_name);
}


function getControllerAndFunction()
{
    $action = \Route::current()->getActionName();
    [$class, $method] = explode('@', $action);
    $class = substr(strrchr($class, '\\'), 1);
    return ['controller' => $class, 'method' => $method];
}

function getControllerRoutePrefix()
{
    $controller = getControllerAndFunction()['controller'] ?? '';
    return strtolower(str_replace('Controller', '', $controller));
}

/**
 * 获取资源文件下的所有文件名称
 *
 * @param  string  $path
 *
 * @return array|bool
 */
function getViewsByResource(string $path)
{
    $files = get_dir_files($path, ['layouts', '.gitkeep']);
    foreach ($files as &$file){
        $file = str_replace('.blade.php', '', $file);
    }
    return $files;
}
