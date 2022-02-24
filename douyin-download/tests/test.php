<?php
    $class = new \Cnpscy\DouyinDownload\Video;

    $url = $_GET['url'] ?? 'https://v.douyin.com/dNdR5W7/';
    $uid = $class->getSecUidByUrl($url);

    // 是否开启下载
    $open_download = true;
    // 下载的文件路径
    $path_file_folder = __DIR__ . '/download/';

    $response = $class->getVideosBySecUid($uid, $max_cursor);
    var_dump($class->getResult());
    exit;
    $max_cursor = $download_nums = 0;
    do {
        $response = $class->getVideosBySecUid($uid, $max_cursor);
        $class->getResult();
        var_dump($response);
        // 开启下载，创建文件夹
        if ($open_download){
            if ($max_cursor == 0){
                $path_file_folder = trim($path_file_folder, '/');
                $path_file_folder .= '/' . $class->getNickName() . '-' .  $this->getFileFolder($class->getUniqueId());
                if (!is_dir($path_file_folder)) {
                    var_dump($path_file_folder);
                    mkdir($path_file_folder, 0777, true);
                }
            }

            foreach ($class->getResult() as &$item){
                // 开启下载，下载文件
                $file_name = empty($item['desc']) ? $item['aweme_id'] : $item['desc'];
                $file_name = str_replace(['/','//','\\'], '', $file_name);
                $path_file_name = $path_file_folder . '/' . $file_name .'.mp4';
                // 下载文件
                if ($item['video_path'] && !is_file($path_file_name)){
                    file_put_contents($path_file_name, fopen($item['video_path'], 'r'));
                    ++$download_nums;
                }
                $item['real_video_path'] = $path_file_name;
            }
        }
        // $response['has_more'] && $max_cursor
    }while(false);