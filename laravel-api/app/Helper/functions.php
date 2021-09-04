<?php

if ( !function_exists('make_file_folder_name') ) {
    /**
     * 文件夹的名称命名
     *
     * @param  string  $string
     *
     * @return string
     */
    function make_file_folder_name(string $string) : string
    {
        return str_replace([
            '_',
            '*',
            '/',
            '//',
            '\\',
            '.',
        ], '', $string);
    }
}

if ( !function_exists('text_asterisk_except_first') ) {
    /**
     * 文本只展示首位，其余全部指定符号隐藏
     *
     * @param  string  $user_name
     *
     * @return string
     */
    function text_asterisk_except_first(string $user_name, string $symbol = '*') : string
    {
        //获取字符串长度
        $str_length = mb_strlen($user_name, 'utf-8');
        if ( $str_length <= 1 ) {
            return $str_length;
        }
        //mb_substr — 获取字符串的部分
        $first_str = mb_substr($user_name, 0, 1, 'utf-8');
        //str_repeat — 重复一个字符串
        $str = $first_str . str_repeat($symbol, $str_length - 1);
        return $str;
    }
}

if ( !function_exists('show_text_duration') ) {
    function add_0($str)
    {
        if ( $str < 10 ) $str = '0' . $str;
        return $str;
    }

    /**
     * 时长按照字符串展示（70s => 1:10 ）
     *
     * @param  int  $time
     *
     * @return string
     */
    function show_text_duration(int $time)
    {
        $hour = $min = $sec = 0;
        $str = '';
        if ( $time > 3600 ) {
            $hour = floor($time / 3600);
            $str = $hour . ':';
        }
        $min = floor(($time - $hour * 3600) / 60);
        $sec = floor($time - $hour * 3600 - $min * 60);
        return $str . add_0($min) . ':' . add_0($sec);
    }
}

if ( !function_exists('text_hidden') ) {
    /**
     * 文本隐藏：保留字符串前多少位与后多少位，隐藏中间用*代替（两个字符时只显示第一个）
     *
     * @param  string  $text
     * @param  int     $show_length  前后展示文本的长度
     *
     * @return string
     */
    function text_hidden(string $text, int $show_length = 2)
    {
        $strlen = mb_strlen($text, 'utf-8');
        $firstStr = mb_substr($text, 0, $show_length, 'utf-8');
        $lastStr = mb_substr($text, -2, $show_length, 'utf-8');
        return ($strlen == 2) ? $firstStr . str_repeat('*', mb_strlen($text, 'utf-8') - $show_length) : $firstStr . str_repeat("*", $strlen - $show_length * 2) . $lastStr;
    }
}

/**
 * 匹配内容，追加图片的前缀
 *
 * @param          $content
 * @param  string  $suffix
 *
 * @return string|string[]|null
 */
function merge_image_url($content, $suffix = '')
{
    $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
    // 如果不存在图片，则追加http；否则不变动
    $pregRule = "/<[img|IMG].*?src=[\'|\"]((?!(http|https)\:\/).*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
    $content = preg_replace($pregRule, '<img src="' . $suffix . '${1}" style="max-width:100%">', $content);
    return $content;
}

if ( !function_exists('number_show_text') ) {
    /**
     * 数字转换成文本展示
     *
     * @return string
     */
    function number_show_text(int $number) : string
    {
        $length = strlen($number);  //数字长度
        if ( $length > 8 ) { //亿单位
            $str = substr_replace(strstr($number, substr($number, -7), ' '), '.', -1, 0) . "亿";
        } elseif ( $length > 4 ) { //万单位
            //截取前俩为
            $str = substr_replace(strstr($number, substr($number, -3), ' '), '.', -1, 0) . "万";
        } else {
            return $number;
        }
        return $str;
    }
}

function get_query_str($url, $key)
{
    $query = explode($key . '=', urldecode($url));
    return current(explode('&', $query[1]));
}

/**
 * @计算中文字符串长度，只支持UTF8编码
 */
function utf8_strlen($string = null)
{
    preg_match_all("/./us", $string, $match);
    return count($match[0]);
}

if ( !function_exists('get_distance') ) {
    /**
     * 根据经纬度算距离，返回结果单位是公里，先纬度，后经度
     *
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     *
     * @return float|int
     */
    function get_distance($lat1, $lng1, $lat2, $lng2)
    {
        $EARTH_RADIUS = 6378.137;

        $radLat1 = rad($lat1);
        $radLat2 = rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = rad($lng1) - rad($lng2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * $EARTH_RADIUS;
        $s = round($s * 10000) / 10000;

        return $s;
    }

    function rad($d)
    {
        return $d * M_PI / 180.0;
    }
}

if ( !function_exists('get_encryption_idcard') ) {
    /**
     * 身份证号，文本内容部分加密
     *
     * @param  string  $identity_card
     *
     * @return string
     */
    function get_encryption_idcard(string $identity_card) : string
    {
        return substr_replace($identity_card, '****', -9, 5);
    }
}

if ( !function_exists('get_days_in_year') ) {
    /**
     * 获取指定年份有多少天
     *
     * @param  int  $year
     *
     * @return int
     */
    function get_days_in_year(int $year)
    {
        $days = 0;
        for ($month = 1; $month <= 12; $month++) {
            $days = $days + cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }
        return $days;
    }
}

if ( !function_exists('get_wxuser_by_code') ) {
    /**
     * 通过Code获取微信用户信息
     *
     * @param  string  $appid
     * @param  string  $appsecret
     * @param  string  $code
     *
     * @return array
     */
    function get_wxuser_by_code(string $appid, string $appsecret, string $code)
    {

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";
        $weixin = file_get_contents($url);//通过code换取网页授权access_token
        $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
        $array = get_object_vars($jsondecode);//转换成数组
        return $array;
    }
}

if ( !function_exists('get_wxuserinfo_by_openid') ) {
    function get_wxuserinfo_by_openid($access_token, $openid)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
        $weixin = file_get_contents($url);//通过code换取网页授权access_token
        $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
        return get_object_vars($jsondecode);
    }
}

if ( !function_exists('get_qquserinfo_by_openid') ) {
    function get_qquserinfo_by_openid($access_token, $openid, $oauth_consumer_key)
    {
        $url = 'https://graph.qq.com/user/get_user_info?access_token=' . $access_token . '&oauth_consumer_key=' . $oauth_consumer_key . '&openid=' . $openid;
        $info = file_get_contents($url);//通过code换取网页授权access_token
        $jsondecode = json_decode($info); //对JSON格式的字符串进行编码
        return get_object_vars($jsondecode);
    }
}

if ( !function_exists('get_cover_by_video') ) {
    /**
     * 通过 ffmpeg 获取视频的第一帧，存储为图片
     *
     * @param  string  $video_path       视频地址
     * @param  string  $cover_file_path  图片存储地址
     * @param          $output           检测失败与成功：0：成功；其它都为失败
     */
    /**
     * @param  string  $video_path       视频地址
     * @param  string  $cover_file_path  图片存储地址
     * @param  null    $return_val       0.表示成功
     * @param  null    $output
     * @param  string  $ffmpeg_path      ffmpeg的环境变量
     */
    function get_cover_by_video(string $video_path, string $cover_file_path, &$return_val = null, &$output = null, string $ffmpeg_path = '/usr/local/ffmpeg/bin/ffmpeg')
    {
        //  -s 286x160 是设置图片尺寸（放在  -f rawvideo 后面）
        //-L license
        //-h 帮助
        //-fromats 显示可用的格式，编解码的，协议的...
        //-f fmt 强迫采用格式fmt
        //-I filename 输入文件
        //-y 覆盖输出文件
        //-t duration 设置纪录时间 hh:mm:ss[.xxx]格式的记录时间也支持
        //-ss position 搜索到指定的时间 [-]hh:mm:ss[.xxx]的格式也支持
        //-title string 设置标题
        //-author string 设置作者
        //-copyright string 设置版权
        //-comment string 设置评论
        //-b bitrate 设置比特率，缺省200kb/s
        //-r fps 设置帧频 缺省25
        //-s size 设置帧大小 格式为WXH 缺省160X128.下面的简写也可以直接使用：
        //Sqcif 128X96 qcif 176X144 cif 252X288 4cif 704X576
        //-aspect aspect 设置横纵比 4:3 16:9 或 1.3333 1.7777
        //-croptop size 设置顶部切除带大小 像素单位
        //-cropbottom size –cropleft size –cropright size
        //-padtop size 设置顶部补齐的大小 像素单位
        //-padbottom size –padleft size –padright size –padcolor color 设置补齐条颜色(hex,6个16进制的数，红:绿:兰排列，比如 000000代表黑色)
        //-vn 不做视频记录
        //-bt tolerance 设置视频码率容忍度kbit/s
        //-maxrate bitrate设置最大视频码率容忍度
        //-minrate bitreate 设置最小视频码率容忍度
        //-bufsize size 设置码率控制缓冲区大小
        //-vcodec codec 强制使用codec编解码方式。如果用copy表示原始编解码数据必须被拷贝。
        //-sameq 使用同样视频质量作为源（VBR）
        //-pass n 选择处理遍数（1或者2）。两遍编码非常有用。第一遍生成统计信息，第二遍生成精确的请求的码率
        //-passlogfile file 选择两遍的纪录文件名为file
        $command = $ffmpeg_path . ' -v 0 -y -i "' . $video_path . '" -vframes 1 -ss 1 -vcodec mjpeg -f rawvideo -aspect 16:9 ' . $cover_file_path . ' ';

        exec($command, $output, $return_val);
    }
}

if ( !function_exists('get_last_month') ) {
    /**
     * 是否为windows系统
     *
     * @return bool
     */
    function is_windows() : bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? true : false;
    }
}

if ( !function_exists('get_last_month') ) {
    /**
     * 上个月份的年月
     *
     * @param  string  $date
     *
     * @return false|string
     */
    function get_last_month(string $date)
    {
        $timestamp = strtotime($date);
        return date('Y-m', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1)));
    }
}

if ( !function_exists('get_next_month') ) {
    /**
     * 下个月份的年月
     *
     * @param  string  $date
     *
     * @return false|string
     */
    function get_next_month(string $date)
    {
        $timestamp = strtotime($date);
        $arr = getdate($timestamp);
        if ( $arr['mon'] == 12 ) {
            $year = $arr['year'] + 1;
            $month = $arr['mon'] - 11;
            $firstday = $year . '-0' . $month;
        } else {
            $firstday = date('Y-m', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) + 1)));
        }
        return $firstday;
    }
}

if ( !function_exists('psQty_res') ) {
    /**
     * 获取指定的进程
     *
     * @param  string  $search
     *
     * @return mixed
     */
    function psQty_res(string $search)
    {
        exec("ps aux | grep '" . $search . "' | grep -v grep | awk '{ print  }' | head -50", $result);
        return $result;
    }
}

if ( !function_exists('psQty_limit') ) {
    /**
     * 获取指定进程数，是否超过限制
     *
     * @param  string  $search    进程名称
     * @param  int     $maxLimit  最大限制数
     *
     * @return boolean
     */
    function psQty_limit(string $search, int $maxLimit = 2)
    {
        $result = psQty_res($search);
        if ( count($result) - 1 >= $maxLimit ) {
            return false;
        } else {
            return true;
        }
    }
}

if ( !function_exists('psQty_num') ) {
    /**
     * 获取指定进程的数量
     *
     * @param  string  $search
     *
     * @return int
     */
    function psQty_num(string $search)
    {
        return count(psQty_res($search));
    }
}

if ( !function_exists('run_exec') ) {
    function run_exec(string $command_name)
    {
        $command = 'php ' . $command_name;

        $output = 0;

        $return_val = '';

        exec($command, $output, $return_val);

        return $output;
    }
}

if ( !function_exists('make_signature') ) {
    /**
     * 生成签名
     *
     * @param  array   $args
     * @param  string  $key
     *
     * @return string
     */
    function make_signature(array $args, string $key)
    {
        //排序
        ksort($args);
        //生成sign
        $str = urldecode(http_build_query($args)) . '&key=' . $key;
        $sign = strtoupper(md5($str));
        return $sign;
    }
}

if ( !function_exists('get_difference_hours') ) {
    /**
     * 计算两个时间戳之间相差的小时
     *
     * @param  int  $start_time  开始时间戳
     * @param  int  $end_time    结束时间戳
     *
     * @return int
     */
    function get_difference_hours(int $start_time, int $end_time) : float
    {
        if ( $start_time < $end_time ) {
            $starttime = $start_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $start_time;
        }
        //计算小时
        $timediff = $endtime - $starttime;
        return floatval($timediff / 3600);
    }
}

if ( !function_exists('array_merge_multiple') ) {
    /**
     * 多维数组合并
     *
     * @param $array1
     * @param $array2
     *
     * @return array
     */
    function array_merge_multiple($array1, $array2)
    {
        $merge = $array1 + $array2;
        $data = [];
        foreach ($merge as $key => $val) {
            if ( isset($array1[$key]) && is_array($array1[$key]) && isset($array2[$key]) && is_array($array2[$key]) ) {
                $data[$key] = array_merge_multiple($array1[$key], $array2[$key]);
            } else {
                $data[$key] = isset($array2[$key]) ? $array2[$key] : $array1[$key];
            }
        }
        return $data;
    }
}

if ( !function_exists('export_excel') ) {
    /**
     * 数据导出到excel(csv文件)
     *
     * @param         $fileName
     * @param  array  $tileArray
     * @param  array  $dataArray
     */
    function export_excel($fileName, $tileArray = [], $dataArray = [])
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);
        ob_end_clean();
        ob_start();
        header("Content-Type: text/csv");
        header("Content-Disposition:filename=" . $fileName);
        $fp = fopen('php://output', 'w');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));// 转码 防止乱码(比如微信昵称)
        fputcsv($fp, $tileArray);
        $index = 0;
        foreach ($dataArray as $item) {
            if ( $index == 1000 ) {
                $index = 0;
                ob_flush();
                flush();
            }
            $index++;
            fputcsv($fp, $item);
        }
        ob_flush();
        flush();
        ob_end_clean();
    }
}

if ( !function_exists('is_json') ) {
    /**
     * 判断字符串是否为 Json 格式
     *
     * @param  string  $data   Json 字符串
     * @param  bool    $assoc  是否返回关联数组。默认返回对象
     *
     * @return array|bool|object 成功返回转换后的对象或数组，失败返回 false
     */
    function is_json($data = '', $assoc = false)
    {
        if ( PHP_VERSION > 5.3 ) {
            json_decode($data);
            return (json_last_error() == JSON_ERROR_NONE);
        } else {
            $data = json_decode($data, $assoc);
            if ( ($data && is_object($data)) || (is_array($data) && !empty($data)) ) {
                return $data;
            }
            return false;
        }
    }
}

if ( function_exists('get_request_method') ) {
    function get_request_method() : string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? '');
    }
}

if ( !function_exists('my_json_encode') ) {
    /**
     * 统一的json_encode
     *
     * @param  array   $data
     * @param  string  $options
     *
     * @return false|string
     */
    function my_json_encode($data, string $options = '')
    {
        //$data = is_object($data) ? (array)$data : $data;
        return json_encode($data, empty($options) ? (JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $options);
    }
}

if ( !function_exists('my_json_decode') ) {
    /**
     * 统一的 json_decode
     *
     * @param  string  $string
     * @param  bool    $assoc
     *
     * @return mixed
     */
    function my_json_decode(string $string, bool $assoc = true)
    {
        return json_decode($string, $assoc);
    }
}

/**
 * 输出xml字符
 */
function ToXml($arr = [])
{
    if ( !is_array($arr) || count($arr) <= 0 ) {
        exception("数组数据异常！");
    }

    $xml = "<xml>";
    foreach ($arr as $key => $val) {
        if ( is_numeric($val) ) {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        } else {
            $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
    }
    $xml .= "</xml>";
    return $xml;
}

/**
 * 将xml转为array
 */
function xml_to_array($xml)
{
    if ( !$xml ) {
        \Exception("xml数据异常！");
    }

    // 解决部分json数据误入的问题
    $arr = json_decode($xml, true);
    if ( is_array($arr) && !empty($arr) ) {
        return $arr;
    }
    // 将XML转为array
    $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $arr;
}

// 生成签名
function make_sign($paraMap = [], $partner_key = '')
{
    $buff = "";
    ksort($paraMap);
    $paraMap['key'] = $partner_key;
    foreach ($paraMap as $k => $v) {
        if ( null != $v && "null" != $v && '' != $v && "sign" != $k ) {
            $buff .= $k . "=" . $v . "&";
        }
    }
    $reqPar = '';
    if ( strlen($buff) > 0 ) {
        $reqPar = substr($buff, 0, strlen($buff) - 1);
    }

    return strtoupper(md5($reqPar));
}

/**
 * 前端获取后端的数据结果集
 *
 * @param $data
 *
 * @return string
 */
function html_get_res_from_admin($data)
{
    return addslashes(my_json_encode($data));
}

if ( !function_exists('axios_request') ) {
    /**
     * 跨域问题设置
     */
    function axios_request()
    {
        $http_origin = !isset($_SERVER['HTTP_ORIGIN']) ? "*" : $_SERVER['HTTP_ORIGIN'];

        $http_origin = (empty($http_origin) || $http_origin == null || $http_origin == 'null') ? '*' : $http_origin;

        $_SERVER['HTTP_ORIGIN'] = $http_origin;

        //if(strtoupper($_SERVER['REQUEST_METHOD'] ?? "") == 'OPTIONS'){  //vue 的 axios 发送 OPTIONS 请求，进行验证
        //    exit;
        //}

        header('Access-Control-Allow-Origin: ' . $http_origin);// . $http_origin
        header('Access-Control-Allow-Credentials: true');//【如果请求方存在域名请求，那么为true;否则为false】
        header('Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Access-Control-Allow-Headers, x-xsrf-token, Accept, x-file-name, x-frame-options, X-Requested-With');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        //header('X-Frame-Options:SAMEORIGIN');
    }
}

if ( !function_exists('string_underscore_lowercase') ) {
    /**
     * 字符串如果存在大小，那么自动转换成 _小写
     *
     * @param          $string
     * @param  string  $format
     *
     * @return string|string[]|null
     */
    function string_underscore_lowercase($string, $format = '_')
    {
        return preg_replace('/((?<=[a-z])(?=[A-Z]))/', $format, $string);
    }
}

if ( !function_exists('convert_underline') ) {
    // 下划线的字符串转骆驼峰
    function convert_underline($str, $ucfirst = true)
    {
        $str = ucwords(str_replace('_', ' ', $str));
        $str = str_replace(' ', '', lcfirst($str));
        return $ucfirst ? ucfirst($str) : $str;
    }
}

if ( !function_exists('get_string_pluralize') ) {
    /**
     * 获得指定字符串的复数
     *
     * @param $string
     *
     * @return mixed|string|string[]|null
     */
    function get_string_pluralize(string $string)
    {
        $plural = [
            [
                '/(quiz)$/i',
                "$1zes",
            ],
            [
                '/^(ox)$/i',
                "$1en",
            ],
            [
                '/([m|l])ouse$/i',
                "$1ice",
            ],
            [
                '/(matr|vert|ind)ix|ex$/i',
                "$1ices",
            ],
            [
                '/(x|ch|ss|sh)$/i',
                "$1es",
            ],
            [
                '/([^aeiouy]|qu)y$/i',
                "$1ies",
            ],
            [
                '/([^aeiouy]|qu)ies$/i',
                "$1y",
            ],
            [
                '/(hive)$/i',
                "$1s",
            ],
            [
                '/(?:([^f])fe|([lr])f)$/i',
                "$1$2ves",
            ],
            [
                '/sis$/i',
                "ses",
            ],
            [
                '/([ti])um$/i',
                "$1a",
            ],
            [
                '/(buffal|tomat)o$/i',
                "$1oes",
            ],
            [
                '/(bu)s$/i',
                "$1ses",
            ],
            [
                '/(alias|status)$/i',
                "$1es",
            ],
            [
                '/(octop|vir)us$/i',
                "$1i",
            ],
            [
                '/(ax|test)is$/i',
                "$1es",
            ],
            [
                '/s$/i',
                "s",
            ],
            [
                '/$/',
                "s",
            ],
        ];

        $singular = [
            [
                "/s$/",
                "",
            ],
            [
                "/(n)ews$/",
                "$1ews",
            ],
            [
                "/([ti])a$/",
                "$1um",
            ],
            [
                "/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/",
                "$1$2sis",
            ],
            [
                "/(^analy)ses$/",
                "$1sis",
            ],
            [
                "/([^f])ves$/",
                "$1fe",
            ],
            [
                "/(hive)s$/",
                "$1",
            ],
            [
                "/(tive)s$/",
                "$1",
            ],
            [
                "/([lr])ves$/",
                "$1f",
            ],
            [
                "/([^aeiouy]|qu)ies$/",
                "$1y",
            ],
            [
                "/(s)eries$/",
                "$1eries",
            ],
            [
                "/(m)ovies$/",
                "$1ovie",
            ],
            [
                "/(x|ch|ss|sh)es$/",
                "$1",
            ],
            [
                "/([m|l])ice$/",
                "$1ouse",
            ],
            [
                "/(bus)es$/",
                "$1",
            ],
            [
                "/(o)es$/",
                "$1",
            ],
            [
                "/(shoe)s$/",
                "$1",
            ],
            [
                "/(cris|ax|test)es$/",
                "$1is",
            ],
            [
                "/([octop|vir])i$/",
                "$1us",
            ],
            [
                "/(alias|status)es$/",
                "$1",
            ],
            [
                "/^(ox)en/",
                "$1",
            ],
            [
                "/(vert|ind)ices$/",
                "$1ex",
            ],
            [
                "/(matr)ices$/",
                "$1ix",
            ],
            [
                "/(quiz)zes$/",
                "$1",
            ],
        ];

        $irregular = [
            [
                'move',
                'moves',
            ],
            [
                'sex',
                'sexes',
            ],
            [
                'child',
                'children',
            ],
            [
                'man',
                'men',
            ],
            [
                'person',
                'people',
            ],
        ];

        $uncountable = [
            'sheep',
            'fish',
            'series',
            'species',
            'money',
            'rice',
            'information',
            'equipment',
        ];

        if ( in_array(strtolower($string), $uncountable) ) return $string;

        foreach ($irregular as $noun) {
            if ( strtolower($string) == $noun[0] ) return $noun[1];
        }
        foreach ($plural as $pattern) {
            $str = preg_replace($pattern[0], $pattern[1], $string);
            if ( $str !== null && $str != $string ) return $str;
        }
    }
}

if ( !function_exists('get_month_range') ) {
    /**
     * 指定日期范围之内的所有月份
     *
     * @param  string  $start_date  开始日期
     * @param  string  $end_date    结束日期
     * @param  string  $format      返回格式
     *
     * @return array
     */
    function get_month_range(string $start_date, string $end_date, string $format = 'Y-m')
    {
        $end = date($format, strtotime($end_date)); // 转换为月
        $range = [];
        $i = 0;
        do {
            $month = date($format, strtotime($start_date . ' + ' . $i . ' month'));
            $range[] = $month;
            $i++;
        } while ( $month < $end );
        return $range;
    }
}

if ( !function_exists('get_year_range') ) {
    function get_year_range(string $start_date, string $end_date, string $format = 'Y')
    {
        $end = date($format, strtotime($end_date)); // 转换为月
        $range = [];
        $i = 0;
        do {
            $month = date($format, strtotime($start_date . ' + ' . $i . ' year'));
            $range[] = $month;
            $i++;
        } while ( $month < $end );
        return $range;
    }
}

if ( !function_exists('load_vendor') ) {
    /**
     * 加载composer的包 - vendor文件目录下
     */
    function load_vendor()
    {
        /*
        |--------------------------------------------------------------------------
        | Register The Auto Loader
        |--------------------------------------------------------------------------
        |
        | Composer provides a convenient, automatically generated class loader for
        | our application. We just need to utilize it! We'll simply require it
        | into the script here so that we don't have to worry about manual
        | loading any of our classes later on. It feels great to relax.
        |
        */
        require ROOT . '/vendor/autoload.php';
    }
}

if ( !function_exists('data_get') ) {
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed             $target
     * @param  string|array|int  $key
     * @param  mixed             $default
     *
     * @return mixed
     */
    function data_get($target, $key, $default = null)
    {
        if ( is_null($key) ) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while ( !is_null($segment = array_shift($key)) ) {
            if ( $segment === '*' ) {
                if ( $target instanceof Collection ) {
                    $target = $target->all();
                } elseif ( !is_array($target) ) {
                    return ($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = data_get($item, $key);
                }

                return in_array('*', $key) ? \system\exp\Cnpscy\Library\Arr::collapse($result) : $result;
            }

            if ( \system\exp\Cnpscy\Library\Arr::accessible($target) && \system\exp\Cnpscy\Library\Arr::exists($target, $segment) ) {
                $target = $target[$segment];
            } elseif ( is_object($target) && isset($target->{$segment}) ) {
                $target = $target->{$segment};
            } else {
                return ($default);
            }
        }

        return $target;
    }
}

if ( !function_exists('data_set') ) {
    /**
     * Set an item on an array or object using dot notation.
     *
     * @param  mixed         $target
     * @param  string|array  $key
     * @param  mixed         $value
     * @param  bool          $overwrite
     *
     * @return mixed
     */
    function data_set(&$target, $key, $value, $overwrite = true)
    {
        $segments = is_array($key) ? $key : explode('.', $key);

        if ( ($segment = array_shift($segments)) === '*' ) {
            if ( !Arr::accessible($target) ) {
                $target = [];
            }

            if ( $segments ) {
                foreach ($target as &$inner) {
                    data_set($inner, $segments, $value, $overwrite);
                }
            } elseif ( $overwrite ) {
                foreach ($target as &$inner) {
                    $inner = $value;
                }
            }
        } elseif ( Arr::accessible($target) ) {
            if ( $segments ) {
                if ( !Arr::exists($target, $segment) ) {
                    $target[$segment] = [];
                }

                data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ( $overwrite || !Arr::exists($target, $segment) ) {
                $target[$segment] = $value;
            }
        } elseif ( is_object($target) ) {
            if ( $segments ) {
                if ( !isset($target->{$segment}) ) {
                    $target->{$segment} = [];
                }

                data_set($target->{$segment}, $segments, $value, $overwrite);
            } elseif ( $overwrite || !isset($target->{$segment}) ) {
                $target->{$segment} = $value;
            }
        } else {
            $target = [];

            if ( $segments ) {
                data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ( $overwrite ) {
                $target[$segment] = $value;
            }
        }

        return $target;
    }
}

if ( !function_exists('config') ) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed              $default
     *
     * @return mixed|\Illuminate\Config\Repository
     */
    function config($key = null, $default = null)
    {
        if ( is_null($key) ) {
            return app('config');
        }

        if ( is_array($key) ) {
            return app('config')->set($key);
        }
        return app('config')->get($key, $default);
    }
}

if ( !function_exists('http_response_code') ) {
    function http_response_code($code = null)
    {
        if ( $code !== null ) {
            switch ($code) {
                case 100:
                    $text = 'Continue';
                    break;
                case 101:
                    $text = 'Switching Protocols';
                    break;
                case 200:
                    $text = 'OK';
                    break;
                case 201:
                    $text = 'Created';
                    break;
                case 202:
                    $text = 'Accepted';
                    break;
                case 203:
                    $text = 'Non-Authoritative Information';
                    break;
                case 204:
                    $text = 'No Content';
                    break;
                case 205:
                    $text = 'Reset Content';
                    break;
                case 206:
                    $text = 'Partial Content';
                    break;
                case 300:
                    $text = 'Multiple Choices';
                    break;
                case 301:
                    $text = 'Moved Permanently';
                    break;
                case 302:
                    $text = 'Moved Temporarily';
                    break;
                case 303:
                    $text = 'See Other';
                    break;
                case 304:
                    $text = 'Not Modified';
                    break;
                case 305:
                    $text = 'Use Proxy';
                    break;
                case 400:
                    $text = 'Bad Request';
                    break;
                case 401:
                    $text = 'Unauthorized';
                    break;
                case 402:
                    $text = 'Payment Required';
                    break;
                case 403:
                    $text = 'Forbidden';
                    break;
                case 404:
                    $text = 'Not Found';
                    break;
                case 405:
                    $text = 'Method Not Allowed';
                    break;
                case 406:
                    $text = 'Not Acceptable';
                    break;
                case 407:
                    $text = 'Proxy Authentication Required';
                    break;
                case 408:
                    $text = 'Request Time-out';
                    break;
                case 409:
                    $text = 'Conflict';
                    break;
                case 410:
                    $text = 'Gone';
                    break;
                case 411:
                    $text = 'Length Required';
                    break;
                case 412:
                    $text = 'Precondition Failed';
                    break;
                case 413:
                    $text = 'Request Entity Too Large';
                    break;
                case 414:
                    $text = 'Request-URI Too Large';
                    break;
                case 415:
                    $text = 'Unsupported Media Type';
                    break;
                case 500:
                    $text = 'Internal Server Error';
                    break;
                case 501:
                    $text = 'Not Implemented';
                    break;
                case 502:
                    $text = 'Bad Gateway';
                    break;
                case 503:
                    $text = 'Service Unavailable';
                    break;
                case 504:
                    $text = 'Gateway Time-out';
                    break;
                case 505:
                    $text = 'HTTP Version not supported';
                    break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
            }

            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

            header($protocol . ' ' . $code . ' ' . $text);

            $GLOBALS['http_response_code'] = $code;
        } else {

            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
        }
        return $code;
    }
}

if ( !function_exists('filtering_html_tags') ) {
    /**
     * 过滤HTML的标签，只保留文本
     *
     * @param $string
     *
     * @return string
     */
    function filtering_html_tags($string)
    {
        return strip_tags(htmlspecialchars_decode($string), '');
    }
}

if ( !function_exists('hash_make') ) {
    /**
     * hash加密
     *
     * @param  string  $password
     *
     * @return string
     */
    function hash_make(string $password = '123456') : string
    {
        return hash_encryption($password);
    }
}

if ( !function_exists('hash_encryption') ) {
    /**
     * [hash_encryption]
     *
     * @param  string  $pass  [description]
     *
     * @return string
     * @author             :cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation  :hash加密
     * @englishAnnotation  :
     * @version            :1.0
     */
    function hash_encryption($pass = '123456') : string
    {
        return password_hash($pass, PASSWORD_DEFAULT);
    }
}

if ( !function_exists('hash_verify') ) {
    /**
     * [hash_verify]
     *
     * @param  string  $pass       [description]
     * @param  string  $hash_pass  [description]
     *
     * @return bool
     * @author             :cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation  :hash解密
     * @englishAnnotation  :
     *
     * @version            :1.0
     */
    function hash_verify(string $pass, string $hash_pass) : bool
    {
        return password_verify($pass, $hash_pass);
    }
}

/**
 * 数组按照指定字段进行分组
 *
 * @param  array   $array
 * @param  string  $field
 *
 * @return array
 */
function array_field_group(array $array = [], string $field = '') : array
{
    $new_ary = [];
    foreach ($array as $k => $val) $new_ary[$val[$field]][] = $val;
    return $new_ary;
}

//计算中奖概率
function get_random_probability($proArr)
{
    $rs = ''; //z中奖结果
    $proSum = array_sum($proArr); //概率数组的总概率精度
    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(0, $proSum);
        if ( $randNum <= $proCur ) {
            $rs = $key;
            break;
        } else $proSum -= $proCur;
    }
    unset($proArr);
    return $rs;
}

/**
 * 对比数组的不同：
 *  第二个数组对于第一个数组的不同数据返回
 * 并不是array_diff数组的效果，找两个数组之间的差集
 *
 * @param $array1
 * @param $array2
 *
 * @return array
 */
function get_array_diff($array1, $array2)
{
    if ( empty($array2) ) return $array1;
    $diff = [];
    foreach ($array1 as $key => $val) {
        // if ( !isset($array2[$val]) ) $diff[$val] = $val;
        if ( !in_array($val, $array2) ) $diff[$val] = $val;
    }
    return $diff;
}

function list_to_tree($list, $primary_key = 'menu_id', $pid = 'parent_id', $child = '_child', $root = 0) : array
{
    $tree = [];
    if ( is_array($list) ) {
        $refer = [];
        foreach ($list as $key => $data) $refer[$data[$primary_key]] =& $list[$key];
        foreach ($list as $key => $data) {
            $parentId = $data[$pid];
            if ( $root == $parentId ) $tree[] =& $list[$key]; else {
                if ( isset($refer[$parentId]) ) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * [login_token]
 *
 * @param  integer  $length  [description]
 *
 * @return string
 * @author           :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:登录token值
 * @englishAnnotation:
 */
function login_token($length = 60) : string
{
    //, '#', '%', '&', '+', '^', '`' --- 会影响参数获取，参数获取不全
    $chars = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'u',
        'v',
        'w',
        'x',
        'y',
        'z',
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z',
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '!',
        '@',
        '$',
        '*',
        '(',
        ')',
        '-',
        '_',
        '[',
        ']',
        '{',
        '}',
        '<',
        '>',
        '~',
        '=',
        ',',
        '.',
        ';',
        ':',
        '/',
        '?',
        '|',
    ];
    $array = rand_array_fill($chars, $length);
    $rand = '';
    for ($i = 0; $i < $length; $i++) $rand .= $chars[$array[$i]];

    unset($length, $array, $chars);

    return $rand;
}

function rand_array_fill(&$ary, $length)
{
    $count = count($ary);
    if ( $count < $length ) {
        $new_ary = [];
        for ($i = 0; $i < ceil($length / $count); $i++) {
            for ($j = 0; $j < count($ary); $j++) {
                array_push($new_ary, $ary[$j]);
            }
        }
        $ary = $new_ary;
        return array_rand($new_ary, $length);
    } else return array_rand($ary, $length);
}

if ( !function_exists('get_client_info') ) {
    /**
     * 获取IP与浏览器信息、语言
     *
     * @return array
     */
    function get_client_info() : array
    {
        if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            $XFF = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $client_pos = strpos($XFF, ', ');
            $client_ip = false !== $client_pos ? substr($XFF, 0, $client_pos) : $XFF;
            unset($XFF, $client_pos);
        } else $client_ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? $_SERVER['LOCAL_ADDR'] ?? '0.0.0.0';
        $client_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5) : '';
        $client_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return [
            'ip'    => &$client_ip,
            'lang'  => &$client_lang,
            'agent' => &$client_agent,
        ];
    }
}

if ( !function_exists('get_ip') ) {
    function get_ip() : string
    {
        $data = get_client_info();
        return $data['ip'] ?? '';
    }
}

/**
 * [GetClientIP 客户端ip地址]
 *
 * @param    [boolean]        $long [是否将ip转成整数]
 *
 * @return   [string|int]           [ip地址|ip地址整数]
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-02-09T12:53:13+0800
 */
function GetClientIP($long = false)
{
    $onlineip = '';
    if ( getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown') ) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif ( getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown') ) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif ( getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown') ) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown') ) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    if ( $long ) {
        $onlineip = sprintf("%u", ip2long($onlineip));
    }
    return $onlineip;
}

if ( !function_exists('get_this_url') ) {
    function get_this_url()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
    }
}

if ( !function_exists('get_request_url') ) {
    function get_request_url()
    {
        return $_SERVER['REQUEST_URI'];
    }
}

/**
 * [request_post 模拟post进行url请求]
 *
 * @Author           :cnpscy——<[2278757482@qq.com]>
 * @DateTime         :2017-09-25
 * @chineseAnnotation:模拟post进行url请求
 * @englishAnnotation:Simulate post for URL requests
 *
 * @param  string   $url        [url地址]
 * @param  array    $post_data  [提交的数据]
 * @param  boolean  $ispost     [是否是post请求]
 * @param  string   $type       [返回格式]
 *
 * @return                               array              [description]
 */
function request_post(string $url = '', array $post_data = [], $ispost = true, $type = 'json')
{
    @header("Content-type: text/html; charset=utf-8");
    if ( empty($url) ) return false;
    $o = "";
    if ( !empty($post_data) ) {
        foreach ($post_data as $k => $v) $o .= "$k=" . urlencode($v) . "&";
        $post_data = substr($o, 0, -1);
        $key = md5(base64_encode($post_data));
    } else $key = 'key';
    if ( $ispost ) {
        $url = $url;
        $curlPost = $post_data;
    } else {
        $url = $url . '?' . implode(',', $post_data);
        $curlPost = 'key=' . $key;
    }
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    if ( $ispost ) {
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    }
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    $object = json_decode($data);
    $return = object_to_array($object);
    return $return;
}

/**
 * [object_to_array 对象转为数组]
 *
 * @Author           :cnpscy——<[2278757482@qq.com]>
 * @DateTime         :2017-09-26
 * @chineseAnnotation:对象转为数组
 * @englishAnnotation:The object is converted to an array
 *
 * @param  object  $array  [需要转换的对象]
 *
 * @return                               array         [description]
 */
function object_to_array($array)
{
    if ( is_object($array) ) $array = (array)$array;
    if ( is_array($array) ) {
        foreach ($array as $key => $value) $array[$key] = object_to_array($value);
    }
    return $array;
}

/**
 * [config_array_analysis]
 *
 * @param              [type] $data [需要解析的数组]
 *
 * @return             [type]       [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :配置多维数组的解析
 * @englishAnnotation  :
 */
function config_array_analysis($data)
{
    $value_extra = preg_split('/[,;\r\n]+/', trim($data, ",;\r\n"));
    if ( strpos($data, ':') ) {
        $array = [];
        foreach ($value_extra as $val) {
            [
                $k,
                $v,
            ] = explode(':', $val);
            $array[$k] = $v;
        }
    } else $array = $value_extra;
    return $array ?? [];
}

/**
 * [is_base64]
 *
 * @param              [type]  $str [description]
 *
 * @return             boolean      [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :检测是否为base64位编码
 * @englishAnnotation  :
 */
function is_base64($str)
{
    //这里多了个纯字母和纯数字的正则判断
    if ( @preg_match('/^[0-9]*$/', $str) || @preg_match('/^[a-zA-Z]*$/', $str) ) return false; elseif ( is_utf8(base64_decode($str)) && base64_decode($str) != '' ) return true;
    return false;
}

/**
 * [is_utf8]
 *
 * @param              [type]  $str [description]
 *
 * @return             boolean      [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :判断否为UTF-8编码
 * @englishAnnotation  :
 */
function is_utf8($str)
{
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        $c = ord($str[$i]);
        if ( $c > 128 ) {
            if ( ($c > 247) ) return false; elseif ( $c > 239 ) $bytes = 4;
            elseif ( $c > 223 ) $bytes = 3;
            elseif ( $c > 191 ) $bytes = 2;
            else return false;
            if ( ($i + $bytes) > $len ) return false;
            while ( $bytes > 1 ) {
                $i++;
                $b = ord($str[$i]);
                if ( $b < 128 || $b > 191 ) return false;
                $bytes--;
            }
        }
    }
    return true;
}

function getMillisecond()
{
    [
        $t1,
        $t2,
    ] = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
}

/**
 * [check_url]
 *
 * @param  string  $_url  [description]
 *
 * @return             [type]        [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :检测URL地址格式
 * @englishAnnotation  :
 * @version            :1.0
 */
if ( !function_exists('check_url') ) {
    function check_url(string $url) : bool
    {
        $str = "/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/";
        if ( !preg_match($str, $url) ) return false; else return true;
    }
}

if ( !function_exists('filter_url') ) {
    function filter_url(string $url) : bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false ? false : true;
    }
}

/**
 * [array_ksort_to_string]
 *
 * @param              [type] $data [description]
 *
 * @return string
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :数组升序转成字符串
 * @englishAnnotation  :
 * @version            :1.0
 */
function array_ksort_to_string($data) : string
{
    if ( is_string($data) ) return $data;
    ksort($data);
    $tmps = [];
    foreach ($data as $k => $v) $tmps[] = $k . $v;
    return implode('', $tmps);
}

/**
 * [mobile_web]
 *
 * @return             boolean [description]
 * @version          :1.0
 * @author           :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:是否为手机端访问
 * @englishAnnotation:
 */
function mobile_web() : bool
{
    $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';

    $mobile_os_list = [
        'Google Wireless Transcoder',
        'Windows CE',
        'WindowsCE',
        'Symbian',
        'Android',
        'armv6l',
        'armv5',
        'Mobile',
        'CentOS',
        'mowser',
        'AvantGo',
        'Opera Mobi',
        'J2ME/MIDP',
        'Smartphone',
        'Go.Web',
        'Palm',
        'iPAQ',
    ];
    $mobile_token_list = [
        'Profile/MIDP',
        'Configuration/CLDC-',
        '160×160',
        '176×220',
        '240×240',
        '240×320',
        '320×240',
        'UP.Browser',
        'UP.Link',
        'SymbianOS',
        'PalmOS',
        'PocketPC',
        'SonyEricsson',
        'Nokia',
        'BlackBerry',
        'Vodafone',
        'BenQ',
        'Novarra-Vision',
        'Iris',
        'NetFront',
        'HTC_',
        'Xda_',
        'SAMSUNG-SGH',
        'Wapaka',
        'DoCoMo',
        'iPhone',
        'iPod',
    ];

    $found_mobile = CheckSubstrs($mobile_os_list, $useragent_commentsblock) || CheckSubstrs($mobile_token_list, $useragent);

    if ( $found_mobile ) return true; else return false;
}

function CheckSubstrs($substrs, $text)
{
    foreach ($substrs as $substr) {
        if ( false !== strpos($text, $substr) ) return true;
    }
    return false;
}

/**
 * [is_app]
 *
 * @return             boolean [description]
 * @version          :1.0
 * @author           :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:检测是否为App
 * @englishAnnotation:
 */
function is_app()
{
    if ( isset ($_SERVER['HTTP_X_WAP_PROFILE']) ) return true;// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if ( isset ($_SERVER['HTTP_VIA']) ) return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;// 找不到为flase,否则为true
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if ( isset ($_SERVER['HTTP_USER_AGENT']) ) {
        $clientkeywords = [
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile',
        ];
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if ( preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])) ) return true;
    }
    // 协议法，因为有可能不准确，放到最后判断
    if ( isset ($_SERVER['HTTP_ACCEPT']) ) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ( (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))) ) return true;
    }
    return false;
}

/**
 * 检测身份证号码
 *
 * @param  string  $id
 *
 * @return bool
 */
function check_idcard(string $id) : bool
{
    $id = strtoupper($id);
    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
    $arr_split = [];
    if ( !preg_match($regx, $id) ) return false;
    if ( 15 == strlen($id) ) { //检查15位
        $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
        @preg_match($regx, $id, $arr_split);
        //检查生日日期是否正确
        $dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if ( !strtotime($dtm_birth) ) return false; else return true;
    } else {      //检查18位
        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
        @preg_match($regx, $id, $arr_split);
        $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if ( !strtotime($dtm_birth) ) return false;//检查生日日期是否正确
        else {
            //检验18位身份证的校验码是否正确。
            //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
            $arr_int = [
                7,
                9,
                10,
                5,
                8,
                4,
                2,
                1,
                6,
                3,
                7,
                9,
                10,
                5,
                8,
                4,
                2,
            ];
            $arr_ch = [
                '1',
                '0',
                'X',
                '9',
                '8',
                '7',
                '6',
                '5',
                '4',
                '3',
                '2',
            ];
            $sign = 0;
            for ($i = 0; $i < 17; $i++) {
                $b = (int)$id[$i];
                $w = $arr_int[$i];
                $sign += $b * $w;
            }
            $n = $sign % 11;
            $val_num = $arr_ch[$n];
            if ( $val_num != substr($id, 17, 1) ) return false; else return true;//phpfensi.com
        }
    }
}

/**
 * [round_down_decimal]
 *
 * @param  float|integer  $money_num  [description]
 * @param  int|integer    $length     [description]
 *
 * @return float
 * @version          :1.0
 * @author           :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:保留几位小数，向下取，就是直接截断 3 为小数即可。
 * @englishAnnotation:
 */
function round_down_decimal(float $money_num = 0, int $length = 2) : float
{
    return substr($money_num, 0, strlen($money_num) - _getFloatLength($money_num) + $length);
}

/**
 * [_getFloatLength]
 *
 * @param              [type] $num [description]
 *
 * @return int
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :计算小数部分的长度
 * @englishAnnotation  :
 * @version            :1.0
 */
function _getFloatLength($num) : int
{
    $count = 0;
    $temp = explode('.', $num);
    if ( sizeof($temp) > 1 ) {
        $decimal = end($temp);
        $count = strlen($decimal);
    }
    return $count;
}

/**
 * [set_money_conversion]
 *
 * @param  float|integer  $money   [description]
 * @param  int|integer    $length  [description]
 *
 * @return float
 *
 * @author           :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:金额插入数据库的时候，单位转换为“分”【具体根据$length长度而定】
 * @englishAnnotation:
 * @version          :1.0
 */
function set_money_conversion(float $money = 0, int $length = 2) : float
{
    return floatval($money) * pow(10, $length);
}

/**
 * [get_money_conversion]
 *
 * @param  float|integer  $money   [description]
 * @param  int|integer    $length  [description]
 *
 * @return             [type]                [description]
 * @version            :1.0
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :金额取出的时候，由“分”转化为“元”【具体根据$length长度而定】
 * @englishAnnotation  :
 */
function get_money_conversion(float $money = 0, int $length = 2) : float
{
    return floatval($money) / pow(10, $length);
}

/**
 * [is_date]
 *
 * @param  string  $date  [description]
 *
 * @return             boolean       [description]
 * @author           :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:是否为日期格式
 * @englishAnnotation:
 * @version          :1.0
 */
function is_date(string $date) : bool
{
    $ret = strtotime($date);
    return $ret !== false && $ret != -1;

    // if (date('Y-m-d H:i:s', strtotime($date)) === $date) return true;
    // else return false;
}

/**
 * [month_list]
 *
 * @param  int  $start  [description]
 * @param  int  $end    [description]
 *
 * @return             [type]        [description]
 * @version            :1.0
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :
 * @englishAnnotation  :
 */
function month_list(int $start, int $end) : array
{
    if ( !is_numeric($start) || !is_numeric($end) || ($end <= $start) ) return '';
    $start = date('Y-m', $start);
    $end = date('Y-m', $end);
    //转为时间戳
    $start = strtotime($start . '-01');
    $end = strtotime($end . '-01');
    $i = 0;
    $d = [];
    while ( $start <= $end ) {
        //这里累加每个月的的总秒数 计算公式：上一月1号的时间戳秒数减去当前月的时间戳秒数
        $d[$i] = trim(date('Y-m', $start), ' ');
        $start += strtotime('+1 month', $start) - $start;
        $i++;
    }
    return $d;
}

function get_errors_list(array $data = []) : string
{
    $html = '';
    if ( !empty($data) ) {
        foreach ($data as $k => $v) $html .= $k + 1 . '.' . $v . PHP_EOL;
    }
    return $html;
}

//内存占用空间
function memory_usage()
{
    $memory = ( !function_exists('memory_get_usage')) ? '0' : round(memory_get_usage() / 1024 / 1024, 2) . 'MB';
    return $memory;
}

/**
 *  参数说明
 *  $string  欲截取的字符串
 *  $sublen  截取的长度
 *  $start   从第几个字节截取，默认为0
 *  $code    字符编码，默认UTF-8
 */
function cut_str(string $string, int $sublen = 100, int $start = 0, $code = 'UTF-8')
{
    if ( $code == 'UTF-8' ) {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if ( count($t_string[0]) - $start > $sublen ) return join('', array_slice($t_string[0], $start, $sublen)) . ".....";
        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start = $start * 2;
        $sublen = $sublen * 2;
        $strlen = strlen($string);
        $tmpstr = '';
        for ($i = 0; $i < $strlen; $i++) {
            if ( $i >= $start && $i < ($start + $sublen) ) {
                if ( ord(substr($string, $i, 1)) > 129 ) {
                    $tmpstr .= substr($string, $i, 2);
                } else {
                    $tmpstr .= substr($string, $i, 1);
                }
            }
            if ( ord(substr($string, $i, 1)) > 129 ) $i++;
        }
        if ( strlen($tmpstr) < $strlen ) $tmpstr .= "";
        return $tmpstr;
    }
}

/**
 * [strToHex]
 *
 * @param              [type] $string [description]
 *
 * @return             [type]         [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :字符串转16进制
 * @englishAnnotation  :
 * @version            :1.0
 */
function strToHex($string)
{
    $this_i = $hex = "";
    for ($i = 0; $i < strlen($string); $i++) {
        $this_i = dechex(ord($string[$i]));
        if ( strlen($this_i) == 0 ) $this_i = '00'; elseif ( strlen($this_i) == 1 ) $this_i = '0' . $this_i;
        $hex .= $this_i;
    }
    $hex = strtoupper($hex);
    return $hex;
}

/**
 * [hexToStr]
 *
 * @param              [type] $hex [description]
 *
 * @return             [type]      [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :16进制转字符串
 * @englishAnnotation  :
 * @version            :1.0
 */
function hexToStr($hex)
{
    $sendStrArray = str_split(str_replace(' ', '', $hex), 2);  // 将16进制数据转换成两个一组的数组
    $send_info = '';
    for ($j = 0; $j < count($sendStrArray); $j++) {
        $send_info .= chr(hexdec($sendStrArray[$j]));
    }
    return $send_info;
}

function crc16($string, $start_reverse = 0)
{
    $string = pack('H*', $string);
    $crc = 0xFFFF;
    for ($x = 0; $x < strlen($string); $x++) {
        $crc = $crc ^ ord($string[$x]);
        for ($y = 0; $y < 8; $y++) {
            if ( ($crc & 0x0001) == 0x0001 ) {
                $crc = (($crc >> 1) ^ 0xA001);
            } else {
                $crc = $crc >> 1;
            }
        }
    }
    $more_data = strlen(dechex(floor($crc % 256))) < 2 ? '0' . dechex($crc % 256) : dechex($crc % 256);
    $less_data = strlen(dechex(floor($crc / 256))) < 2 ? '0' . dechex($crc / 256) : dechex($crc / 256);
    return strtoupper($start_reverse == 0 ? ($more_data . $less_data) : ($less_data . $more_data));
}

function gencrc16($string)
{
    $crc = 0xFFFF;
    for ($x = 0; $x < strlen($string); $x++) {
        $crc = $crc ^ ord($string[$x]);
        for ($y = 0; $y < 8; $y++) {
            if ( ($crc & 0x0001) == 0x0001 ) {
                $crc = (($crc >> 1) ^ 0xA001);
            } else {
                $crc = $crc >> 1;
            }
        }
    }
    return strtoupper($crc);
}

/**
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 *
 * @return int
 *
 * 经纬度计算两点之间的距离
 */
function getDistance($lat1, $lng1, $lat2, $lng2)
{

    // 将角度转为狐度
    $radLat1 = deg2rad($lat1);// deg2rad()函数将角度转换为弧度
    $radLat2 = deg2rad($lat2);
    $radLng1 = deg2rad($lng1);
    $radLng2 = deg2rad($lng2);

    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;

    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;

    return $s;
}

/**
 * @param         $lat1
 * @param         $lon1
 * @param         $lat2
 * @param         $lon2
 * @param  float  $radius  星球半径 KM
 *
 * @return float
 *
 * 经纬度计算两点之间的距离
 */
function distance($lat1, $lon1, $lat2, $lon2, $radius = 6378.137)
{
    $rad = floatval(M_PI / 180.0);

    $lat1 = floatval($lat1) * $rad;
    $lon1 = floatval($lon1) * $rad;
    $lat2 = floatval($lat2) * $rad;
    $lon2 = floatval($lon2) * $rad;

    $theta = $lon2 - $lon1;

    $dist = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta));

    if ( $dist < 0 ) {
        $dist += M_PI;
    }
    return $dist = $dist * $radius;
}

function request_function(string $url = '', array $post_data = [], $ispost = true, $json_conversion = 1)
{
    if ( empty($url) ) return false;
    $o = "";
    if ( !empty($post_data) ) {
        foreach ($post_data as $k => $v) $o .= "$k=" . urlencode($v) . "&";
        $post_data = substr($o, 0, -1);
        $key = md5(base64_encode($post_data));
    } else $key = 'key';
    if ( $ispost ) {
        $url = $url;
        $curlPost = $post_data;
    } else {
        $url = $url . '?' . $post_data;
        $curlPost = 'key=' . $key;
    }
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //禁止 cURL 验证对等证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //是否检测服务器的域名与证书上的是否一致
    if ( $ispost ) {
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    }
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    return $json_conversion ? json_decode($data, true) : $data;
}

/**
 * [set_field_filtering]
 *
 * @param  string  $field        [数据]
 * @param  string  $field_type   [数据的类型]
 * @param  string  $default_val  [默认值]
 *
 * @version          :1.0
 * @author           :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:数据类型过滤
 * @englishAnnotation:
 */
function set_field_filtering($field = '', $field_type = 'string', $default_val = '')
{
    if ( isset($field) || $field == null ) return $field;
    $field_type = strtolower(trim($field_type));
    if ( in_array($field_type, [
        'str',
        'string',
        'varchar',
    ]) ) return trim($field ?? $default_val); elseif ( in_array($field_type, [
        'int',
        'intval',
        'number',
    ]) ) return intval($field ?? $default_val);
    elseif ( in_array($field_type, [
        'double',
        'float',
        'floatval',
    ]) ) return floatval($field ?? $default_val);
}

function get_server_url() : string
{
    $pact = isset($_SERVER['HTTPS']) && 'off' !== $_SERVER['HTTPS'] ? 'https://' : 'http://';
    return $pact . ($_SERVER['SERVER_NAME'] ?? '');
}

function set_server_url($str) : string
{
    return get_server_url() . $str;
    // return get_server_url() . $str;
}

function remove_server_url(string $img) : string
{
    return str_replace(get_server_url(), "", $img);
}

//生成随机数
function get_rand($sum = 6)
{
    $rand = '';
    for ($i = 1; $i <= $sum; $i++) $rand .= rand(0, 9);
    return $rand;
}

/**
 * 获取随机字符串
 *
 * @param  int  $randLength     长度
 * @param  int  $create_time    是否加入当前时间戳
 * @param  int  $includenumber  是否包含数字
 *
 * @return string
 */
if ( !function_exists('rand_str') ) {
    function rand_str($randLength = 6, $create_time = 0, $includenumber = 1)
    {
        if ( $includenumber ) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
        } else {
            $chars = 'abcdefghijklmnopqrstuvwxyz';
        }
        $len = strlen($chars);
        $randStr = '';
        for ($i = 0; $i < $randLength; $i++) {
            $randStr .= $chars[mt_rand(0, $len - 1)];
        }
        $tokenvalue = $randStr;
        if ( $create_time ) {
            $tokenvalue = $randStr . '-' . time();
        }
        return $tokenvalue;
    }
}

/**
 * 倒计时 转化成 天-时分秒 展示
 *
 * @param $time
 *
 * @return string
 */
function countdown_conversion_time($time)
{
    if ( empty($time) ) return '';
    $day = intval($time / (60 * 60 * 24));
    $hour = intval($time / (60 * 60) - $day * 24);
    $minute = intval($time / 60 - $day * 24 * 60 - $hour * 60);
    $second = intval($time - $day * 24 * 60 * 60 - $hour * 60 * 60 - $minute * 60);

    $day = (intval($day) <= 0) ? '' : $day . '天 ';
    if ( intval($hour) <= 9 ) $hour = '0' . $hour;
    if ( intval($minute) <= 9 ) $minute = '0' . $minute;
    if ( intval($second) <= 9 ) $second = '0' . $second;
    return $day . $hour . '时' . $minute . '分' . $second . '秒';
}

function amount_conversion($money, $length = 2)
{
    return round(floatval($money), $length);
}

/**
 * 金额格式化
 *
 * @param   [float]         $value     [金额]
 * @param   [int]           $decimals  [保留的位数]
 * @param   [string]        $dec_point [保留小数分隔符]
 */
function PriceNumberFormat($value, $decimals = 2, $dec_point = '.')
{
    return number_format($value, $decimals, $dec_point, '');
}

function getWxPayMoeny($moeny)
{
    return $moeny * 100;//以分为单位
}

/*
 * 检测银行卡
  16-19 位卡号校验位采用 Luhm 校验方法计算：
    1，将未带校验位的 15 位卡号从右依次编号 1 到 15，位于奇数位号上的数字乘以 2
    2，将奇位乘积的个十位全部相加，再加上所有偶数位上的数字
    3，将加法和加上校验位能被 10 整除。
*/
function check_bank_card($bankCardNo)
{
    $strlen = strlen($bankCardNo);
    if ( $strlen < 15 || $strlen > 19 ) {
        return false;
    }
    if ( !preg_match("/^\d{15}$/i", $bankCardNo) && !preg_match("/^\d{16}$/i", $bankCardNo) && !preg_match("/^\d{17}$/i", $bankCardNo) && !preg_match("/^\d{18}$/i", $bankCardNo) && !preg_match("/^\d{19}$/i", $bankCardNo) ) {
        return false;
    }
    $arr_no = str_split($bankCardNo);
    $last_n = $arr_no[count($arr_no) - 1];
    krsort($arr_no);
    $i = 1;
    $total = 0;
    foreach ($arr_no as $n) {
        if ( $i % 2 == 0 ) {
            $ix = $n * 2;
            if ( $ix >= 10 ) {
                $nx = 1 + ($ix % 10);
                $total += $nx;
            } else {
                $total += $ix;
            }
        } else {
            $total += $n;
        }
        $i++;
    }
    $total -= $last_n;
    $x = 10 - ($total % 10);
    if ( $x != $last_n ) {
        return false;
    }
    return true;
}

/**
 * 生成混合code
 *
 * @param  integer  $length  [description]
 *
 * @return             [type]          [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :登录token值
 * @englishAnnotation  :
 */
function make_blend_code($length = 20) : string
{
    $chars = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'u',
        'v',
        'w',
        'x',
        'y',
        'z',
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z',
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
    ];
    $array = array_rand($chars, $length);
    $rand = '';
    for ($i = 0; $i < $length; $i++) $rand .= $chars[$array[$i]];
    return $rand;
}

/**
 * 生成UUID
 *
 * @param  string  $string
 *
 * @return string
 */
if ( !function_exists('make_uuid') ) {
    /**
     * 生成UUID
     *
     * @param  string  $string
     *
     * @return string
     */
    function make_uuid(string $string = '') : string
    {
        $string = '' === $string ? uniqid(mt_rand(), true) : (0 === (int)preg_match('/[A-Z]/', $string) ? $string : mb_strtolower($string, 'UTF-8'));
        $code = hash('sha1', $string . ':UUID');
        $uuid = substr($code, 0, 10);
        $uuid .= substr($code, 10, 4);
        $uuid .= substr($code, 16, 4);
        $uuid .= substr($code, 22, 4);
        $uuid .= substr($code, 28, 12);
        $uuid = strtoupper($uuid);
        unset($string, $code);
        return $uuid;
    }
}

/**
 * Generate UUID (string hash based)
 *
 * @param  string  $string
 *
 * @return string
 */
function get_uuid(string $string = '') : string
{
    if ( '' === $string ) {
        //Create random string
        $string = uniqid(microtime() . getmypid() . mt_rand(), true);
    }

    $start = 0;
    $codes = [];
    $length = [
        8,
        4,
        4,
        4,
        12,
    ];
    $string = hash('md5', $string);

    foreach ($length as $len) {
        $codes[] = substr($string, $start, $len);
        $start += $len;
    }

    $uuid = implode('-', $codes);

    unset($string, $start, $codes, $length, $len);
    return $uuid;
}

function getFirstChar($s)
{
    $s0 = mb_substr($s, 0, 3); //获取名字的姓
    $s = iconv('UTF-8', 'gb2312', $s0); //将UTF-8转换成GB2312编码
    if ( ord($s0) > 128 ) { //汉字开头，汉字没有以U、V开头的
        $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
        if ( $asc >= -20319 and $asc <= -20284 ) return "A";
        if ( $asc >= -20283 and $asc <= -19776 ) return "B";
        if ( $asc >= -19775 and $asc <= -19219 ) return "C";
        if ( $asc >= -19218 and $asc <= -18711 ) return "D";
        if ( $asc >= -18710 and $asc <= -18527 ) return "E";
        if ( $asc >= -18526 and $asc <= -18240 ) return "F";
        if ( $asc >= -18239 and $asc <= -17760 ) return "G";
        if ( $asc >= -17759 and $asc <= -17248 ) return "H";
        if ( $asc >= -17247 and $asc <= -17418 ) return "I";
        if ( $asc >= -17417 and $asc <= -16475 ) return "J";
        if ( $asc >= -16474 and $asc <= -16213 ) return "K";
        if ( $asc >= -16212 and $asc <= -15641 ) return "L";
        if ( $asc >= -15640 and $asc <= -15166 ) return "M";
        if ( $asc >= -15165 and $asc <= -14923 ) return "N";
        if ( $asc >= -14922 and $asc <= -14915 ) return "O";
        if ( $asc >= -14914 and $asc <= -14631 ) return "P";
        if ( $asc >= -14630 and $asc <= -14150 ) return "Q";
        if ( $asc >= -14149 and $asc <= -14091 ) return "R";
        if ( $asc >= -14090 and $asc <= -13319 ) return "S";
        if ( $asc >= -13318 and $asc <= -12839 ) return "T";
        if ( $asc >= -12838 and $asc <= -12557 ) return "W";
        if ( $asc >= -12556 and $asc <= -11848 ) return "X";
        if ( $asc >= -11847 and $asc <= -11056 ) return "Y";
        if ( $asc >= -11055 and $asc <= -10247 ) return "Z";
    } elseif ( ord($s) >= 48 and ord($s) <= 57 ) { //数字开头
        switch (iconv_substr($s, 0, 1, 'utf-8')) {
            case 1:
                return "Y";
            case 2:
                return "E";
            case 3:
                return "S";
            case 4:
                return "S";
            case 5:
                return "W";
            case 6:
                return "L";
            case 7:
                return "Q";
            case 8:
                return "B";
            case 9:
                return "J";
            case 0:
                return "L";
        }
    } elseif ( ord($s) >= 65 and ord($s) <= 90 ) { //大写英文开头
        return substr($s, 0, 1);
    } elseif ( ord($s) >= 97 and ord($s) <= 122 ) { //小写英文开头
        return strtoupper(substr($s, 0, 1));
    } else {
        return iconv_substr($s0, 0, 1, 'utf-8');
        //中英混合的词语，不适合上面的各种情况，因此直接提取首个字符即可
    }
}

function strip_html_tags($tags, $str)
{
    $html = [];
    foreach ($tags as $tag) $html[] = "/(<(?:\/" . $tag . "|" . $tag . ")[^>]*>)/i";
    $data = preg_replace($html, '', $str);
    return $data;
}

//获取本地存储的文件
function GetLocalFileByPath($path)
{
    return asset('uploads') . '/' . $path;
}

//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function curl_request($url, $post = '', $json = false, $header = [])
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 0);
    //curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    if ( $json ) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge([
            'Content-Type: application/json; charset=utf-8',
            //伪造IP
            //'CLIENT-IP:85.25.105.77','X-FORWARDED-FOR:85.25.105.77',//此处可以改为任意假IP
            'Content-Length: ' . (empty($post) ? 0 : strlen(http_build_query($post))),
        ], $header));
    }
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    //不输出头信息
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if ( curl_errno($curl) ) {
        return false;
        //throw new Exception("Error:".curl_error($curl));
    }
    curl_close($curl);
    return $data;
}

function curlRequest($url, $params = [], $method = 'POST', $header = [], $type = 'json', $options = [])
{
    if ( empty($options) ) {
        $options = [
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => ['X-REQUESTED-WITH: XMLHttpRequest'],
        ];
    }

    $method = strtoupper($method);
    $protocol = substr($url, 0, 5);

    if ( $type == 'json' && is_array($params) ) {
        $query_string = json_encode($params, JSON_UNESCAPED_UNICODE);
    } else {
        $query_string = is_array($params) ? http_build_query($params) : $params;
    }

    $ch = curl_init();
    $defaults = [];
    if ( 'GET' == $method ) {
        $geturl = $query_string ? $url . (stripos($url, '?') !== false ? '&' : '?') . $query_string : $url;
        $defaults[CURLOPT_URL] = $geturl;
    } else {
        $defaults[CURLOPT_URL] = $url;
        if ( $method == 'POST' ) {
            $defaults[CURLOPT_POST] = 1;
        } else {
            $defaults[CURLOPT_CUSTOMREQUEST] = $method;
        }
        $defaults[CURLOPT_POSTFIELDS] = $query_string;
    }

    $defaults[CURLOPT_HEADER] = false;
    $defaults[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.98 Safari/537.36';
    $defaults[CURLOPT_FOLLOWLOCATION] = true;
    $defaults[CURLOPT_RETURNTRANSFER] = true;
    $defaults[CURLOPT_CONNECTTIMEOUT] = 3;
    $defaults[CURLOPT_TIMEOUT] = 3;

    // disable 100-continue
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Expect:']);

    if ( 'https' == $protocol ) {
        $defaults[CURLOPT_SSL_VERIFYPEER] = false;
        $defaults[CURLOPT_SSL_VERIFYHOST] = false;
    }

    curl_setopt_array($ch, (array)$options + $defaults);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
        'Content-Type: application/json; charset=utf-8',
        //伪造IP
        'CLIENT-IP:85.25.105.77',
        'X-FORWARDED-FOR:85.25.105.77',
        //此处可以改为任意假IP
        'Content-Length: ' . strlen($query_string),
    ], $header));

    $ret = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    return json_decode($ret, true);
}

//过滤值为空的数组
function filterEmptyArr($array)
{

    if ( !is_array($array) ) return false;

    $return_arr = [];
    foreach ($array as $k => $v) {
        if ( is_array($v) ) {

            foreach ($v as $k1 => $v1) {
                if ( $v1 ) {
                    $return_arr[$k] = $v;
                }
            }
        } elseif ( $v ) {
            $return_arr[$k] = $v;
        }
    }

    return $return_arr;
}

//验证数组是否为空,除了指定的key
function CheckArrIsEmpty($arr, $except = [])
{
    if ( !is_array($arr) ) return false;
    foreach ($arr as $k => $value) {
        if ( is_array($value) ) {
            CheckArrIsEmpty($value);
        } elseif ( !$value && $value != 0 ) {
            if ( !in_array($k, $except) ) {
                throw  new  Exception($k . " is empty.", 40000);
            }
        }
    }
}

/**
 * 获取客户端浏览器信息
 *
 * @param  null
 *
 * @return  string
 * @author  huang
 */
function get_broswer()
{
    $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
    if ( stripos($sys, "Firefox/") > 0 ) {
        preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
        $exp[0] = "Firefox";
        $exp[1] = $b[1];    //获取火狐浏览器的版本号
    } elseif ( stripos($sys, "Maxthon") > 0 ) {
        preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
        $exp[0] = "傲游";
        $exp[1] = $aoyou[1];
    } elseif ( stripos($sys, "MSIE") > 0 ) {
        preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
        $exp[0] = "IE";
        $exp[1] = $ie[1];  //获取IE的版本号
    } elseif ( stripos($sys, "OPR") > 0 ) {
        preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
        $exp[0] = "Opera";
        $exp[1] = $opera[1];
    } elseif ( stripos($sys, "Edge") > 0 ) {
        //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
        preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);
        $exp[0] = "Edge";
        $exp[1] = $Edge[1];
    } elseif ( stripos($sys, "Chrome") > 0 ) {
        preg_match("/Chrome\/([\d\.]+)/", $sys, $google);
        $exp[0] = "Chrome";
        $exp[1] = $google[1];  //获取google chrome的版本号
    } elseif ( stripos($sys, 'rv:') > 0 && stripos($sys, 'Gecko') > 0 ) {
        preg_match("/rv:([\d\.]+)/", $sys, $IE);
        $exp[0] = "IE";
        $exp[1] = $IE[1];
    } else {
        $exp[0] = "未知浏览器";
        $exp[1] = "";
    }
    return $exp[0] . '(' . $exp[1] . ')';
}

/**
 * 获取客户端操作系统信息,包括win10
 *
 * @param  null
 *
 * @return  string
 * @author  huang
 */
function get_os()
{

    $agent = $_SERVER['HTTP_USER_AGENT'];
    $os = false;

    if ( preg_match('/win/i', $agent) && strpos($agent, '95') ) {
        $os = 'Windows 95';
    } elseif ( preg_match('/win 9x/i', $agent) && strpos($agent, '4.90') ) {
        $os = 'Windows ME';
    } elseif ( preg_match('/win/i', $agent) && preg_match('/98/i', $agent) ) {
        $os = 'Windows 98';
    } elseif ( preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent) ) {
        $os = 'Windows Vista';
    } elseif ( preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent) ) {
        $os = 'Windows 7';
    } elseif ( preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent) ) {
        $os = 'Windows 8';
    } elseif ( preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent) ) {
        $os = 'Windows 10';#添加win10判断
    } elseif ( preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent) ) {
        $os = 'Windows XP';
    } elseif ( preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent) ) {
        $os = 'Windows 2000';
    } elseif ( preg_match('/win/i', $agent) && preg_match('/nt/i', $agent) ) {
        $os = 'Windows NT';
    } elseif ( preg_match('/win/i', $agent) && preg_match('/32/i', $agent) ) {
        $os = 'Windows 32';
    } elseif ( preg_match('/linux/i', $agent) ) {
        $os = 'Linux';
    } elseif ( preg_match('/unix/i', $agent) ) {
        $os = 'Unix';
    } elseif ( preg_match('/sun/i', $agent) && preg_match('/os/i', $agent) ) {
        $os = 'SunOS';
    } elseif ( preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent) ) {
        $os = 'IBM OS/2';
    } elseif ( preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent) ) {
        $os = 'Macintosh';
    } elseif ( preg_match('/PowerPC/i', $agent) ) {
        $os = 'PowerPC';
    } elseif ( preg_match('/AIX/i', $agent) ) {
        $os = 'AIX';
    } elseif ( preg_match('/HPUX/i', $agent) ) {
        $os = 'HPUX';
    } elseif ( preg_match('/NetBSD/i', $agent) ) {
        $os = 'NetBSD';
    } elseif ( preg_match('/BSD/i', $agent) ) {
        $os = 'BSD';
    } elseif ( preg_match('/OSF1/i', $agent) ) {
        $os = 'OSF1';
    } elseif ( preg_match('/IRIX/i', $agent) ) {
        $os = 'IRIX';
    } elseif ( preg_match('/FreeBSD/i', $agent) ) {
        $os = 'FreeBSD';
    } elseif ( preg_match('/teleport/i', $agent) ) {
        $os = 'teleport';
    } elseif ( preg_match('/flashget/i', $agent) ) {
        $os = 'flashget';
    } elseif ( preg_match('/webzip/i', $agent) ) {
        $os = 'webzip';
    } elseif ( preg_match('/offline/i', $agent) ) {
        $os = 'offline';
    } else {
        $os = '未知操作系统';
    }
    return $os;
}

//生成树形结构数据

function listToTree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
    $tree = [];
    if ( is_array($list) ) {
        $refer = [];
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }

        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];

            if ( $root == $parentId ) {
                $tree[$data[$pk]] = &$list[$key];
            } else {
                if ( isset($refer[$parentId]) ) {
                    $parent = &$refer[$parentId];
                    $parent[$child][$data[$pk]] = &$list[$key];
                }
            }
        }
    }

    return $tree;
}

//获取本地翻译语言
function getTranslateByKey($key)
{
    //echo (__("admin.zh.index_allow_curl"));
    echo __(session("admin_current_language")["shortcode"] . "." . $key);
}

function getHomeByKey($key)
{
    echo __(session("home_current_language")["shortcode"] . "." . $key);
}

//获取上一页的URL
function getPreUrl()
{
    return url()->previous();
}

function excelTime($date, $time = false)
{
    if ( function_exists('GregorianToJD') ) {
        if ( is_numeric($date) ) {
            $jd = GregorianToJD(1, 1, 1970);
            $gregorian = JDToGregorian($jd + intval($date) - 25569);
            $date = explode('/', $gregorian);
            $date_str = str_pad($date [2], 4, '0', STR_PAD_LEFT) . "-" . str_pad($date [0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date [1], 2, '0', STR_PAD_LEFT) . ($time ? " 00:00:00" : '');
            return $date_str;
        }
    } else {
        $date = $date > 25568 ? $date + 1 : 25569;
        /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
        $ofs = (70 * 365 + 17 + 2) * 86400;
        $date = date("Y-m-d", ($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
    }
    return $date;
}

//判断是否是微信浏览器
function is_weixin()
{

    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return true;
    }
    return false;
}

//是否为手机号码
if ( !function_exists('is_mobile') ) {
    function is_mobile(string $text)
    {
        $search = '/^0?1[3|4|5|6|7|8|9][0-9]\d{8}$/';
        if ( preg_match($search, $text) ) return true; else return false;
    }
}

//手机号码 中间4位加密
if ( !function_exists('get_encryption_mobile') ) {
    function get_encryption_mobile($tel)
    {
        $new_tel = preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $tel);
        return $new_tel;
    }
}

//API统一的数据返回格式
if ( !function_exists('return_api_format') ) {
    function return_api_format($return = [])
    {
        $return['data'] = !isset($return['data']) ? [] : $return['data'];
        $return['msg'] = !isset($return['msg']) ? '获取成功' : $return['msg'];
        $return['status'] = !isset($return['status']) ? (empty($return['data']) ? 40000 : 200) : $return['status'];
        return response()->json($return);
    }
}

if ( !function_exists('del_dir_files') ) {
    /**
     * 删除文件夹与下方的所有文件
     *
     * @param       $dirName     文件夹名称
     * @param  int  $delete_dir  是否删除文件夹【1.删除；0.不删除】
     */
    function del_dir_files($dirName, $delete_dir = 1)
    {
        if ( $handle = @opendir($dirName) ) {
            while ( false !== ($item = @readdir($handle)) ) {
                if ( $item != '.' && $item != '..' ) {
                    if ( is_dir($dirName . '/' . $item) ) del_dir_files($dirName . '/' . $item); else @unlink($dirName . '/' . $item);
                }
            }
            @closedir($handle);
        }
        if ( $delete_dir == 1 ) @rmdir($dirName);
    }
}

if ( !function_exists('get_file_filtering') ) {
    /**
     * 获取指定格式的文件
     *
     * @param  array  $array
     * @param  array  $format
     *
     * @return array
     */
    function get_file_filtering($array = [], $format = [])
    {
        $return = [];
        if ( empty($array) || empty($format) ) return $return;
        foreach ($array as $key => $value) {
            $arr = pathinfo($value);
            if ( !empty($arr['extension']) && in_array($arr['extension'], $format) ) $return[] = $value;
        }
        return $return;
    }
}

if ( !function_exists('write_lock_file') ) {
    /**
     * 写入锁文件
     *
     * @param $path
     */
    function write_lock_file($path, $content = '')
    {
        $lock_file = fopen($path . '/lock', 'w+');//创建 锁文件
        fwrite($lock_file, empty($content) ? date('Y-m-d H:i:s') : $content);//写入
    }
}

if ( !function_exists('del_dir_files') ) {
    /**
     * 删除文件夹与下方的所有文件
     *
     * @param       $dirName     文件夹名称
     * @param  int  $delete_dir  是否删除文件夹【1.删除；0.不删除】
     */
    function del_dir_files($dirName, $delete_dir = 1)
    {
        if ( $handle = @opendir($dirName) ) {
            while ( false !== ($item = @readdir($handle)) ) {
                if ( $item != '.' && $item != '..' ) {
                    if ( is_dir($dirName . '/' . $item) ) del_dir_files($dirName . '/' . $item); else @unlink($dirName . '/' . $item);
                }
            }
            @closedir($handle);
        }
        if ( $delete_dir == 1 ) @rmdir($dirName);
    }
}

if ( !function_exists('get_client_info') ) {
    /**
     * 获取IP与浏览器信息、语言
     */
    function get_client_info() : array
    {
        if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            $XFF = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $client_pos = strpos($XFF, ', ');
            $client_ip = false !== $client_pos ? substr($XFF, 0, $client_pos) : $XFF;
            unset($XFF, $client_pos);
        } else $client_ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? $_SERVER['LOCAL_ADDR'] ?? '0.0.0.0';
        $client_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5) : '';
        $client_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return [
            'ip'    => &$client_ip,
            'lang'  => &$client_lang,
            'agent' => &$client_agent,
        ];
    }
}

if ( !function_exists('get_ip') ) {
    function get_ip() : string
    {
        $data = get_client_info();
        return empty($data['ip']) ? '' : $data['ip'];
    }
}

if ( !function_exists('get_month_days') ) {
    /**
     * 获取某月份的所有日期列表
     *
     * @param  string  $time
     * @param  string  $format
     *
     * @return array
     */
    function get_month_days($time = '', $format = 'Y-m-d')
    {
        $time = $time != '' ? $time : time();
        //获取当前周几
        $week = date('d', $time);
        $date = [];
        for ($i = 1; $i <= date('t', $time); $i++) {
            $date[$i] = date($format, strtotime('+' . ($i - $week) . ' days', $time));
        }
        return $date;
    }
}

if ( !function_exists('get_month_range') ) {
    /**
     * 指定日期范围之内的所有月份
     *
     * @param  string  $start_date  开始日期
     * @param  string  $end_date    结束日期
     * @param  string  $format      返回格式
     *
     * @return array
     */
    function get_month_range(string $start_date, string $end_date, string $format = 'Y-m')
    {
        $end = date($format, strtotime($end_date)); // 转换为月
        $range = [];
        $i = 0;
        do {
            $month = date($format, strtotime($start_date . ' + ' . $i . ' month'));
            $range[] = $month;
            $i++;
        } while ( $month < $end );
        return $range;
    }
}

if ( !function_exists('get_days_range') ) {
    /**
     * 指定日期范围之内的所有天
     *
     * @param  string  $start_date  开始日期
     * @param  string  $end_date    结束日期
     * @param  string  $format      返回格式
     *
     * @return array
     */
    function get_days_range(string $start_date, string $end_date, string $format = 'Y-m-d')
    {
        $end = date($format, strtotime($end_date)); // 转换为月
        $range = [];
        $i = 0;
        do {
            $day = date($format, strtotime($start_date . ' + ' . $i . ' day'));
            $range[] = $day;
            $i++;
        } while ( $day < $end );
        return $range;
    }
}

if ( !function_exists('get_years_range') ) {
    /**
     * 指定日期范围之内的所有年份
     *
     * @param  string  $start_date  开始日期
     * @param  string  $end_date    结束日期
     * @param  string  $format      返回格式
     *
     * @return array
     */
    function get_years_range(string $start_date, string $end_date, string $format = 'Y')
    {
        $start_date = date('Y-m-d', strtotime($start_date . '-01-01'));
        $end = date($format, strtotime($end_date)); // 转换为年
        $range = [];
        $i = 0;
        do {
            $year = date($format, strtotime($start_date . ' + ' . $i . ' year'));
            $range[] = $year;
            $i++;
        } while ( $year < $end );
        return $range;
    }
}

if ( !function_exists('set_month_format') ) {
    /**
     * 设置 月份 的格式统一
     *
     * @param $month
     *
     * @return string
     */
    function set_month_format($month)
    {
        return (string)(strlen($month) == 1 ? '0' . $month : $month);
    }
}

if ( !function_exists('get_mail_template_message') ) {
    /**
     * 获取模板消息内容
     *
     * @param          $content
     * @param  string  $change  也可以是数组
     * @param  string  $code    对应数组
     *
     * @return mixed
     */
    function get_mail_template_message($content, $change = '', $code = '{$code}')
    {
        return str_replace($code, $change, $content);
    }
}

/**
 * 获取当前host域名
 *
 * @return string
 */
function get_host()
{
    if ( isset($_SERVER['HTTP_HOST']) ) {
        $scheme = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
        $SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
        $arr = explode('/', $SCRIPT_NAME);
        $url = $scheme . $_SERVER['HTTP_HOST'] . substr($SCRIPT_NAME, 0, -strlen($arr[count($arr) - 2])) . '/';
        return $url;
        //$scheme = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
        //$SCRIPT_NAME = rtrim($_SERVER['REQUEST_URI'], '/');
        //$arr = explode('/', $SCRIPT_NAME);
        //$url = $scheme . $_SERVER['HTTP_HOST'] . substr($SCRIPT_NAME, 0, -strlen($arr[count($arr) - 2]));
        //return $url;
    } else {
        if ( empty($baseUrl) ) {
            $request = \think\facade\Request::instance();
            $subDir = str_replace('\\', '/', dirname($request->server('PHP_SELF')));
            $baseUrl = $request->scheme() . '://' . $request->host() . $subDir . ($subDir === '/' ? '' : '/');
        }

        return trim($baseUrl, '/');
    }
}

/**
 * [is_email]
 *
 * @param  string  $email  [description]
 *
 * @return             boolean        [description]
 * @author           :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:检测邮箱格式
 * @englishAnnotation:
 * @version          :1.0
 */
function is_email(string $email) : bool
{
    // '/^[a-z0-9]+([._-][a-z0-9]+)*@([0-9a-z]+\.[a-z]{2,14}(\.[a-z]{2})?)$/i';
    $checkmail = "/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";//定义正则表达式
    if ( preg_match($checkmail, $email) ) return true; else return false;
}

/**
 * [is_mobile]
 *
 * @param              [type]  $text [description]
 *
 * @return             bool|boolean  [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :检测手机号格式是否正确
 * @englishAnnotation  :
 * @version            :1.0
 */
function is_mobile(string $text) : bool
{
    $search = '/^0?1[3|4|5|6|7|8][0-9]\d{8}$/';
    if ( preg_match($search, $text) ) return true; else return false;
}

/**
 * [roundDownDecimal]
 *
 * @param  float|integer  $money_num  [description]
 * @param  int|integer    $length     [description]
 *
 * @return float
 * @version          :1.0
 * @author           :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:保留几位小数，向下取，就是直接截断 3 为小数即可。
 * @englishAnnotation:
 */
function roundDownDecimal(float $money_num = 0, int $length = 2) : float
{
    return substr($money_num, 0, strlen($money_num) - _getFloatLength($money_num) + $length);
}

if ( !function_exists('writeLog') ) {
    function writeLog($msg, $file_name, $path = __DIR__)
    {
        /**
         * 第一部分路径
         */
        $dirPath = $path . '/logs';
        if ( !is_dir($dirPath) ) @mkdir($dirPath);
        $dirPath .= '/' . date('Y');
        if ( !is_dir($dirPath) ) @mkdir($dirPath);
        $dirPath .= '/' . date('n');
        if ( !is_dir($dirPath) ) @mkdir($dirPath);
        /**
         * 第二部分
         */
        file_put_contents($dirPath . '/' . (empty($file_name) ? date('j') : $file_name) . '.txt', "\n\n" . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        file_put_contents($dirPath . '/' . (empty($file_name) ? date('j') : $file_name) . '.txt', print_r($msg, true), FILE_APPEND);
    }
}

/**
 * 倒计时 转化成 天-时分秒 展示
 *
 * @param $time
 *
 * @return string
 */
function countdownConversionTime($time)
{
    if ( empty($time) ) return '';
    $day = intval($time / (60 * 60 * 24));
    $hour = intval($time / (60 * 60) - $day * 24);
    $minute = intval($time / 60 - $day * 24 * 60 - $hour * 60);
    $second = intval($time - $day * 24 * 60 * 60 - $hour * 60 * 60 - $minute * 60);

    $day = (intval($day) <= 0) ? '' : $day . '天 ';
    if ( intval($hour) <= 9 ) $hour = '0' . $hour;
    if ( intval($minute) <= 9 ) $minute = '0' . $minute;
    if ( intval($second) <= 9 ) $second = '0' . $second;
    return $day . $hour . ':' . $minute . ':' . $second . '';
}

if ( !function_exists('array_key_first') ) {
    /**
     * Gets the first key of an array
     *
     * @param  array  $array
     *
     * @return mixed
     */
    function array_key_first(array $array)
    {
        if ( count($array) ) {
            reset($array);
            return key($array);
        }
        return null;
    }
}

function hidden_mobile(string $text) : string
{
    $start = substr($text, 0, 3);
    $end = substr($text, -4, 4);
    return $start . ' **** ' . $end;
}

function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    if ( function_exists("mb_substr") ) {
        if ( $suffix ) return mb_substr($str, $start, $length, $charset) . "..."; else
            return mb_substr($str, $start, $length, $charset);
    } elseif ( function_exists('iconv_substr') ) {
        if ( $suffix ) return iconv_substr($str, $start, $length, $charset) . "..."; else
            return iconv_substr($str, $start, $length, $charset);
    }
    $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]
[x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
    $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
    $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("", array_slice($match[0], $start, $length));
    if ( $suffix ) return $slice . "…";
    return $slice;
}

// function hidden_bank(string $text, $start = 1): string
// {
//     $start = substr($text, 0, 6);
//     $end = substr($text, -4, 4);
//     return $start . ($start == 1 ? ' ' : '') . '········' . ($start == 1 ? ' ' : '') . $end;
//     return $start . ($start == 1 ? ' ' : '') . '**** ****' . ($start == 1 ? ' ' : '') . $end;
// }

function hidden_bank(string $text) : string
{
    $start = substr($text, 0, 4);
    $end = substr($text, -4, 4);
    return $start . '&nbsp;****&nbsp;****&nbsp' . $end;
}

function get_month_first_day()
{
    return date('Y-m-01', strtotime(date("Y-m-d", time())));
}

function get_month_last_day()
{
    $date = date('Y-m-01', strtotime(date("Y-m-d", time())));
    return date('Y-m-d', strtotime("$date +1 month -1 day"));
}

/**
 * [Xml_Array xml转数组]
 *
 * @param    [xml] $xmlstring [xml数据]
 *
 * @return   [array]          [array数组]
 */
function Xml_Array($xmlstring)
{
    return json_decode(json_encode((array)simplexml_load_string($xmlstring)), true);
}

/**
 * [IsMobile 是否是手机访问]
 *
 * @return  [boolean] [手机访问true, 则false]
 */
function IsMobile()
{
    /* 如果有HTTP_X_WAP_PROFILE则一定是移动设备 */
    if ( isset($_SERVER['HTTP_X_WAP_PROFILE']) ) return true;

    /* 此条摘自TPM智能切换模板引擎，适合TPM开发 */
    if ( isset($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'] ) return true;

    /* 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息 */
    if ( isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap') !== false ) return true;

    /* 判断手机发送的客户端标志,兼容性有待提高 */
    if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
        $clientkeywords = [
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipad',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile',
        ];
        /* 从HTTP_USER_AGENT中查找手机浏览器的关键字 */
        if ( preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])) ) {
            return true;
        }
    }

    /* 协议法，因为有可能不准确，放到最后判断 */
    if ( isset($_SERVER['HTTP_ACCEPT']) ) {
        /* 如果只支持wml并且不支持html那一定是移动设备 */
        /* 如果支持wml和html但是wml在html之前则是移动设备 */
        if ( (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))) ) return true;
    }
    return false;
}

/**
 * [EmptyDir 清空目录下所有文件]
 *
 * @param    [string]    $dir_path [目录地址]
 *
 * @return   [boolean]             [成功true, 失败false]
 */
function EmptyDir($dir_path)
{
    if ( is_dir($dir_path) ) {
        $dn = @opendir($dir_path);
        if ( $dn !== false ) {
            while ( false !== ($file = readdir($dn)) ) {
                if ( $file != '.' && $file != '..' ) {
                    if ( !unlink($dir_path . $file) ) {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }
    return true;
}

/**
 * [FileSizeByteToUnit 文件大小转常用单位]
 *
 * @param    [int]                   $bit [字节数]
 *
 * @return string
 */
function FileSizeByteToUnit($bit) : string
{
    //单位每增大1024，则单位数组向后移动一位表示相应的单位
    $type = [
        'Bytes',
        'KB',
        'MB',
        'GB',
        'TB',
    ];
    for ($i = 0; $bit >= 1024; $i++) {
        $bit /= 1024;
    }

    //floor是取整函数，为了防止出现一串的小数，这里取了两位小数
    return (floor($bit * 100) / 100) . $type[$i];
}

/**
 * json带格式输出
 *
 * @param    [array]          $data   [数据]
 * @param    [string]         $indent [缩进字符，默认4个空格 ]
 *
 * @return string
 */
function JsonFormat($data, $indent = null) : string
{
    // json encode
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    // 缩进处理
    $ret = '';
    $pos = 0;
    $length = strlen($data);
    $indent = isset($indent) ? $indent : '    ';
    $newline = "\n";
    $prevchar = '';
    $outofquotes = true;

    for ($i = 0; $i <= $length; $i++) {
        $char = substr($data, $i, 1);

        if ( $char == '"' && $prevchar != '\\' ) {
            $outofquotes = !$outofquotes;
        } elseif ( ($char == '}' || $char == ']') && $outofquotes ) {
            $ret .= $newline;
            $pos--;
            for ($j = 0; $j < $pos; $j++) {
                $ret .= $indent;
            }
        }

        $ret .= $char;

        if ( ($char == ',' || $char == '{' || $char == '[') && $outofquotes ) {
            $ret .= $newline;
            if ( $char == '{' || $char == '[' ) {
                $pos++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $ret .= $indent;
            }
        }

        $prevchar = $char;
    }

    return $ret;
}

/**
 * 根据身份证号码得到年龄
 *
 * @param $id
 *
 * @return float|int|string
 */
function getAgeByID(string $id)
{ //过了这年的生日才算多了1周岁

    if ( empty($id) ) return '';

    $date = strtotime(substr($id, 6, 8)); //获得出生年月日的时间戳

    $today = strtotime('today'); //获得今日的时间戳

    $diff = floor(($today - $date) / 86400 / 365); //得到两个日期相差的大体年数

    //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比

    $age = strtotime(substr($id, 6, 8) . '+' . $diff . 'years') > $today ? ($diff + 1) : $diff;

    return $age;
}

/**
 * [api_url]
 *
 * @return              [string] [URL]
 * @author              :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation   :API请求地址
 * @englishAnnotation   :API request URL address
 */
function web_url()
{
    return http_type() . $_SERVER['HTTP_HOST'];
}

/**
 * [http_type]
 *
 * @return             [string] [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :获取http类型：http\https
 * @englishAnnotation  :Get the HTTP type: http\https
 */
function http_type() : string
{
    return $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
}

/**
 * 格式化字节大小
 *
 * @param  number  $size       字节数
 * @param  string  $delimiter  数字和单位分隔符
 *
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = [
        'B',
        'KB',
        'MB',
        'GB',
        'TB',
        'PB',
    ];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }
    return $size . $delimiter . $units[$i];
}

/**
 * CURL发送Request请求,含POST和REQUEST
 *
 * @param  string  $url      请求的链接
 * @param  mixed   $params   传递的参数
 * @param  string  $method   请求的方法
 * @param  mixed   $options  CURL的参数
 *
 * @return array
 */
function send_request($url, $params = [], $method = 'POST', $type = 'json', $options = [])
{
    if ( empty($options) ) {
        $options = [
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => ['X-REQUESTED-WITH: XMLHttpRequest'],
        ];
    }

    $params = array_merge([
        'origin_host' => get_host(),
        'origin_url'  => $_SERVER['REQUEST_URI'],
        'origin_ip'   => get_ip(),
    ], $params);
    $method = strtoupper($method);
    $protocol = substr($url, 0, 5);
    if ( $type == 'json' && is_array($params) ) {
        $params = json_encode($params, JSON_UNESCAPED_UNICODE);
    }
    $query_string = is_array($params) ? http_build_query($params) : $params;

    $ch = curl_init();
    $defaults = [];
    if ( 'GET' == $method ) {
        $geturl = $query_string ? $url . (stripos($url, '?') !== false ? '&' : '?') . $query_string : $url;
        $defaults[CURLOPT_URL] = $geturl;
    } else {
        $defaults[CURLOPT_URL] = $url;
        if ( $method == 'POST' ) {
            $defaults[CURLOPT_POST] = 1;
        } else {
            $defaults[CURLOPT_CUSTOMREQUEST] = $method;
        }
        $defaults[CURLOPT_POSTFIELDS] = $query_string;
    }

    $defaults[CURLOPT_HEADER] = false;
    $defaults[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.98 Safari/537.36';
    $defaults[CURLOPT_FOLLOWLOCATION] = true;
    $defaults[CURLOPT_RETURNTRANSFER] = true;
    $defaults[CURLOPT_CONNECTTIMEOUT] = 3;
    $defaults[CURLOPT_TIMEOUT] = 3;

    // disable 100-continue
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Expect:']);

    if ( 'https' == $protocol ) {
        $defaults[CURLOPT_SSL_VERIFYPEER] = false;
        $defaults[CURLOPT_SSL_VERIFYHOST] = false;
    }

    curl_setopt_array($ch, (array)$options + $defaults);

    $ret = curl_exec($ch);
    $err = curl_error($ch);

    if ( false === $ret || !empty($err) ) {
        $errno = curl_errno($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return [
            'ret'   => false,
            'errno' => $errno,
            'msg'   => $err,
            'info'  => $info,
        ];
    }
    curl_close($ch);

    return json_decode($ret, true);
}

//随机验证码
if ( !function_exists('random_verification_code') ) {
    function random_verification_code($length = 6)
    {
        $code = '';
        for ($i = 0; $i < $length; $i++) $code .= mt_rand(0, 9);
        return $code;
    }
}

if ( !function_exists('get_dir_files') ) {

    //获取某目录下所有子文件和子目录,可以过滤
    function get_dir_files($path, $filter = [], $onlydir = false)
    {
        if ( !is_dir($path) ) {
            return false;
        }
        //scandir方法
        $arr = [];
        $data = scandir($path);
        foreach ($data as $value) {
            if ( $value != '.' && $value != '..' && $value != ".DS_Store" && !in_array($value, $filter) ) {
                if ( $onlydir ) {
                    if ( is_dir($path . "/" . $value) ) {
                        $arr[] = $value;
                    } else {
                        continue;
                    }
                } else {
                    $arr[] = $value;
                }
            }
        }
        return $arr;
    }

    // 列出指定目录下所有目录和文件
    function get_dir_files1($dir)
    {
        $arr = [];
        if ( is_dir($dir) ) {//如果是目录，则进行下一步操作
            $d = opendir($dir);//打开目录
            if ( $d ) {//目录打开正常
                while ( ($file = readdir($d)) !== false ) {//循环读出目录下的文件，直到读不到为止
                    if ( $file != '.' && $file != '..' ) {//排除一个点和两个点
                        if ( is_dir($file) ) {//如果当前是目录
                            $arr[$file] = get_dir_files($file);//进一步获取该目录里的文件
                        } else {
                            $arr[] = $file;//记录文件名
                        }
                    }
                }
            }
            closedir($d);//关闭句柄
        }
        return $arr;
    }
}

if ( !function_exists('write_lock_file') ) {
    /**
     * 写入锁文件
     *
     * @param          $path
     * @param  string  $content
     */
    function write_lock_file($path, $content = '')
    {
        $lock_file = fopen($path . '/lock', 'w+');//创建 锁文件
        fwrite($lock_file, empty($content) ? date('Y-m-d H:i:s') : $content);//写入
    }
}

if ( !function_exists('put_file_to_zip') ) {
    /**
     * 把指定文件目录下的所有文件，打包压缩至压缩包内
     *
     * @param  string  $path
     * @param          $zip
     * @param  string  $old_filename
     * @param  array   $limit_dir  限制压缩的文件目录
     */
    function put_file_to_zip(string $path, $zip, string $old_filename, $limit_dir = [])
    {
        header('content-type:text/html;charset=utf-8');
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while ( ($filename = readdir($handler)) !== false ) {
            if ( $filename != '.' && $filename != '..' ) {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if ( is_dir($path . '/' . $filename) ) {// 如果读取的某个对象是文件夹，则递归
                    if ( !empty($limit_dir) && !in_array($filename, $limit_dir) ) continue;
                    $old_filename = (empty($old_filename) ? '' : ($old_filename . '/'));
                    $zip->addEmptyDir($old_filename . $filename);
                    put_file_to_zip($path . '/' . $filename, $zip, $old_filename . $filename);
                } else { //将文件加入zip对象
                    $zip->addFile($path . '/' . $filename, (empty($old_filename) ? '' : ($old_filename . '/')) . $filename);
                }
            }
        }
        @closedir($path);
    }
}

if ( !function_exists('check_http_file_exists') ) {
    //判断远程文件是否存在
    function check_http_file_exists($url)
    {
        $curl = curl_init($url);
        // 不取回数据
        curl_setopt($curl, CURLOPT_NOBODY, true);
        // 发送请求
        $result = curl_exec($curl);
        $found = false;
        // 如果请求没有发送失败
        if ( $result !== false ) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ( $statusCode == 200 ) $found = true;
        }
        curl_close($curl);
        return $found;
    }
}

if ( !function_exists('check_dir_exits') ) {
    /**
     * 检测目录是否存在，不存在则创建目录
     *
     * @param  string  $dir_path
     */
    function check_dir_exits(string $dir_path) : void
    {
        if ( !is_dir($dir_path) ) @mkdir($dir_path, 0755, true);
    }
}

function get_url() : string
{
    $scheme = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
    $url = $scheme . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '/' . $_SERVER['REQUEST_URI'];
    return $url;
}

//把时间戳转换为几分钟或几小时前或几天前
function formatting_timestamp($time) : string
{
    $time = (int)substr($time, 0, 10);
    $int = time() - $time;
    $str = '';
    if ( $int <= 30 ) {
        $str = sprintf('刚刚', $int);
    } elseif ( $int < 60 ) {
        $str = sprintf('%d秒前', $int);
    } elseif ( $int < 3600 ) {
        $str = sprintf('%d分钟前', floor($int / 60));
    } elseif ( $int < 86400 ) {
        $str = sprintf('%d小时前', floor($int / 3600));
    } elseif ( $int < 2592000 ) {
        $str = sprintf('%d天前', floor($int / 86400));
    } elseif ( date('Y', $time) == date('Y') ) {
        $str = date('m-d H:i:s', $time);
    } else {
        $str = date('Y-m-d H:i:s', $time);
    }
    return $str;
}

// 统计字数
function comment_count_word($str)
{
    //$str =characet($str);
    //判断是否存在替换字符
    $is_tihuan_count = substr_count($str, "龘");
    try {
        //先将回车换行符做特殊处理
        $str = preg_replace('/(\r\n+|\s+|　+)/', "龘", $str);
        //处理英文字符数字，连续字母、数字、英文符号视为一个单词
        $str = preg_replace('/[a-z_A-Z0-9-\.!@#\$%\\\^&\*\)\(\+=\{\}\[\]\/",\'<>~`\?:;|]/', "m", $str);
        //合并字符m，连续字母、数字、英文符号视为一个单词
        $str = preg_replace('/m+/', "*", $str);
        //去掉回车换行符
        $str = preg_replace('/龘+/', "", $str);
        //返回字数
        return mb_strlen($str) + $is_tihuan_count;
    } catch (Exception $e) {
        return 0;
    }
}

// 二分查找法
function Binary_Search()
{
    //function binary_search($nums, $num)
    //{
    //    return binary_search_internal($nums, $num, 0, count($nums) - 1);
    //}
    //
    //function binary_search_internal($nums, $num, $low, $high)
    //{
    //    if ($low > $high) {
    //        return -1;
    //    }
    //
    //    $mid = floor(($low + $high) / 2);
    //    if ($num > $nums[$mid]) {
    //        return binary_search_internal($nums, $num, $mid + 1, $high);
    //    } elseif ($num < $nums[$mid]) {
    //        return binary_search_internal($nums, $num, $low, $mid - 1);
    //    } else {
    //        return $mid;
    //    }
    //}
    //
    //$nums = [1, 2, 3, 4, 5, 6];
    //$index = binary_search($nums, 5);
    //print $index;
}

function exist_http($str) : bool
{
    return preg_match('/(http:\/\/)|(https:\/\/)/i', $str);
}

/**
 * 生成订单号
 *
 * @return string
 */
function order_no()
{
    return date('Ymd') . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
