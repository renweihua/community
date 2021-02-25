

<script src="https://cdn.bootcss.com/socket.io/2.3.0/socket.io.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.js"></script>

上传图片：
<a name="uploadFile" id="uploadFile" href="javascript:;">[上传文件]</a>
<input id="myFile" name="myFile" value="上传图片" type="file" style="display: none"/>

<script>
    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }

    token = getQueryString('token') || '';
    var socket = io('ws://127.0.0.1:9502', {
        query: {
            token : token
        },transports: ["websocket"]});
    socket.on('connect', data => {
        var num = Math.random() * 1000;
        num = parseInt(num, 10);
        room_id = num;

        // console.log(room_id);
        // token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJkZWZhdWx0XzVmNDhiMmNmYWZjZDU3LjMwNzAwNTEyIiwiaWF0IjoxNTk4NTk5ODg3LCJuYmYiOjE1OTg1OTk4ODcsImV4cCI6MTU5ODYwNzA4NywidXNlcl9pZCI6MiwiZ3VhcmQiOiJ1c2VyIiwiand0X3NjZW5lIjoiZGVmYXVsdCJ9.YImehUpzq9sdtPO61YYD7A8oVrMy26X-_AWCrv7Gbjw';

        // socket.emit('event', 'hello, hyperf', console.log);
        socket.emit('join-room', {"room_id":' + room_id + ', "type":"login", "token":"' + token + '"}, console.log);

        setInterval(function () {
            socket.emit('message', {"room_id":' + room_id + ', "type":"ping", "content":"ping......"});
        }, 10000);
    });

    /**
     *
     * 对应后端的返回事件
     *
     **/
    socket.on('message', (type, data, status, msg) => {
        // switch ($.trim(type)) {
        //     case '':
        //         break;
        //     default:
        //         break;
        // }
        console.log(type, data, status, msg);
    });

    // 登录事件
    socket.on('user-login', (data, status) => {
        console.log(data);
        console.log(status);
    });

    // 图片上传事件
    socket.on('upload-file', (data, status, msg) => {
        console.log(data);
        console.log(status);
        console.log(msg);

        console.log('图片路径：' + data.file_path);
    });

    /**
     * 点击上传文件，触发input type="file"
     */
    $("#uploadFile").click(function () {
        const myFile = $("#myFile");
        // 触发
        myFile.click()
        // 监听change事件
        myFile[0].addEventListener('change', function (e) {
            var filesList = document.querySelector('#myFile').files;
            if (filesList.length == 0) { //如果取消上传，则上传文件的长度为0
                console.log("没有上传任何文件");
                return;
            } else {
                //如果有文件上传，这在这里面进行
                for (i = 0; i < filesList.length; i++) {
                    //获取并记录图片的base64编码
                    var reader = new FileReader();
                    reader.readAsDataURL(filesList[i]); // 读出 base64
                    reader.onloadend = function () {
                        // 图片的 base64 格式, 可以直接当成 img 的 src 属性值
                        var dataURL = reader.result;//base64

                        // 开始图片上传
                        socket.emit('upload-file', {"room_id":' + room_id + ', "type":"uploadFile", "file":"' + dataURL + '"}, console.log);
                    };
                }
            }
        });
    });
</script>
