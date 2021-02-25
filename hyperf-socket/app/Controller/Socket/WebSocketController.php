<?php
declare(strict_types = 1);

namespace App\Controller\Socket;

use App\Constants\Socket\SocketConst;
use App\Exception\Exception;
use App\Library\Encrypt\Rsa;
use App\Service\Socket\ChatService;
use App\Service\Socket\UploadFileService;
use App\Service\Socket\UserService;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\WebSocketServer\Context;
use Hyperf\SocketIOServer\Socket;
use Hyperf\Utils\Codec\Json;
use Hyperf\SocketIOServer\BaseNamespace;
use Hyperf\SocketIOServer\Annotation\SocketIONamespace;
use Hyperf\SocketIOServer\Annotation\Event;
use Hyperf\Di\Annotation\Inject;

/**
 * @SocketIONamespace("/")
 *
 * 数据格式定义规范：
 * $socket->emit(事件, data, 成功失败的状态码，msg)
 */
class WebSocketController extends BaseNamespace
{
    /**
     * @Event("connect")
     */
    public function onConnect(Socket $socket, $data)
    {
//        var_dump('onConnect');
    }

    /**
     * @Event("disconnect")
     */
    public function onClose(Socket $socket, $data)
    {
//        var_dump('onClose');


        //        return;
        //        /**
        //         * 核心类的处理方式
        //         */
        //        $fdObj = FdCollector::get($fd);
        //        if (! $fdObj) {
        //            return;
        //        }
        //
        //        $this->logger->debug(sprintf('WebSocket: fd[%d] closed.', $fd));
        //
        //        Context::set(WsContext::FD, $fd);
        //        defer(function () use ($fd) {
        //            // Move those functions to defer, because onClose may throw exceptions
        //            FdCollector::del($fd);
        //            WsContext::release($fd);
        //        });
        //
        //        $instance = $this->container->get($fdObj->class);
        //        if ($instance instanceof OnCloseInterface) {
        //            $instance->onClose($server, $fd, $reactorId);
        //        }
    }

    /**
     * ping
     *
     * @Event("start-ping")
     *
     * @param  \Hyperf\SocketIOServer\Socket  $socket
     * @param                                 $data
     */
    public function onStartPing(Socket $socket, $data)
    {
        $socket->emit('start-ping', 'ping……' . date('Y-m-d H:i:s'), SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::SUCCESS));
    }

    /**
     * 连接socket
     *
     * @Event("user-login")
     *
     * @param  string  $data
     */
    public function onUserLogin(Socket $socket, $data)
    {
        var_dump('onUserLogin - getSid' . $socket->getSid());
        var_dump($data);

        // 登录Token验证
        $this->verifyUserLogin($socket, $data);
    }

    /**
     * 开启socket时，验证Token，并进行记录会员信息
     *
     * @param  \Hyperf\SocketIOServer\Socket  $socket
     * @param                                 $data
     */
    private function verifyUserLogin(Socket $socket, $data)
    {
        if (!isset($data['token']) || empty($data['token'])) {
            $socket->emit('user-login', '', SocketConst::STATUS_ERROR, SocketConst::getMessage(SocketConst::UN_LOGIN));
            // 断开此连接
            $socket->disconnect();
        }
        try {
            $token_user = Rsa::privDecrypt($data['token']);
            if (!$token_user){
                throw new Exception('Token已失效');
            }
            // Token 是否过期
            if (!isset($token_user->expires_time) || $token_user->expires_time <= time()){
                throw new Exception('Token过期，请重新登录！');
            }
            $redis = redis('token');
            $value = $redis->get('laravel_database_users_token:' . $data['token']);
            if (empty($value)){
                throw new Exception('Token过期，请重新登录！');
            }
            $user = my_json_decode($value);
            $user = (object)[
                'user_id' => rand(0, 999),
                'nick_name' => '随机名称',
                'nick_avatar' => ''
            ];
            // 欢迎加入房间 - SocketConst::getMessage(SocketConst::JOIN_ROOM)
            $socket->emit('user-login', (array)$user, SocketConst::STATUS_SUCCESS, '欢迎{' . $user->user_id . '：' . $user->nick_name . '}进入socket');
            // 记录加入房间的用户标识：用户Id与socket_id进行绑定
            UserService::setUser($socket, $user);
        } catch (Exception $e) {
            var_dump($e->getLine());
            var_dump($e->getMessage());

            $socket->emit('user-login', '', SocketConst::STATUS_ERROR, SocketConst::getMessage(SocketConst::TOKEN_INVALID));
            // 断开此连接
            $socket->disconnect();
        }
    }

    /**
     * 消息通知
     *
     * @Event("message")
     *
     * @param  string  $data
     */
    public function onMessage(Socket $socket, $data)
    {
        var_dump('onMessage - getSid' . $socket->getSid());
        $data = $this->getArrayByData($data);
        var_dump($data);
        $sid = $socket->getSid();
//        var_dump('onSay - sid：' . $sid . '：' . $data);
        $socket->to($data['room_id'])
               ->emit('message', $sid . " say: {$data['content']}", SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::SUCCESS));
    }

    /**
     * @Event("join-room")
     * @param  string  $data
     */
    public function onJoinRoom(Socket $socket, $data)
    {
        var_dump('onJoinRoom - getSid' . $socket->getSid());
        var_dump($this->getArrayByData($data));
        $data = $this->getStringByData($data);
        var_dump($data);
        // 将当前用户加入房间
        $socket->join($data);
        // 向房间内其他用户推送（不含当前用户）
        $socket->to($data)
               ->emit('message', $socket->getSid() . "has joined {$data}", SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::SUCCESS));
        // 向房间内所有人广播（含当前用户）
        $this->emit('message', 'There are ' . count($socket->getAdapter()
                                                           ->clients($data)) . " players in {'room':" . (Json::decode($data)['room_id'] ?? 0) . "}", SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::SUCCESS));
    }

    /**
     * base64文件上传
     *
     * @Event("upload-file")
     *
     * @param  string  $data
     */
    public function onUploadFile(Socket $socket, $data)
    {
        if (isset($data['file'])) {
            $base64img = $data['file'];
            try {
                $res = UploadFileService::base64($base64img);
                $socket->emit('upload-file', $res, SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::UPLOAD_SUCCESS));
            } catch (Exception $e) {
                $socket->emit('upload-file', $e->getMessage(), SocketConst::STATUS_ERROR, SocketConst::getMessage(SocketConst::UPLOAD_ERROR));
            }
        } else {
            $socket->emit('upload-file', '', SocketConst::STATUS_ERROR, SocketConst::getMessage(SocketConst::UPLOAD_UNFILE));
        }
    }

    /**
     * 获取与指定好友的聊天记录
     *
     * @Event("getPrivateChatRecors")
     */
    public function onGetPrivateChatRecors(Socket $socket, $data)
    {
        $sid = $socket->getSid();
        $data = $this->getArrayByData($data);
        $list = ChatService::getPrivateChatRecords($sid, $data);
        // 发送给当前连接
        $socket->emit('getPrivateChatRecors', $list, SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::STATUS_SUCCESS));
    }

    /**
     * 私聊 --- OK
     *
     * @Event("private-chat")
     */
    public function onPrivateChat(Socket $socket, $data)
    {
        /**
         * friend_id
         * chat_type
         * content
         */
        $sid = $socket->getSid();
        $data = $this->getArrayByData($data);
        // 聊天记录录入
        if ($result = ChatService::setPrivateChat($sid, $data)) {
            // 会员Id：通过好友Id找到该会员的sid标识
            $friend_sid = UserService::getSidByUserId($data['friend_id']);
            // 消息发送给好友对应的sid
            $socket->to($friend_sid)
                   ->emit('private-chat', $result, SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::SUCCESS));
            // 回复发送者：消息发送成功，并且推送数据
            $user_id = $result->friend_id;
            $friend_id = $result->user_id;
            $result->user_id = $friend_id;
            $result->friend_id = $user_id;
            $socket->emit('private-chat', $result, SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::SUCCESS));
        } else {
            // 消息录入失败，回复发送者
            $socket->emit('private-chat', '', SocketConst::STATUS_ERROR, SocketConst::getMessage(SocketConst::MESSAGE_SEND_ERROR));
        }
    }

    /**
     * 房间内广播
     *
     * @Event("room-broadcast")
     */
    public function onRoomBroadcast(Socket $socket, $data)
    {
        $sid = $socket->getSid();
//        var_dump('onSay - sid：' . $sid . '：' . $data);
        // 聊天记录录入
        $result = ChatService::setGroupChat($sid, $data);
        // 会员Id：通过好友Id找到该会员的sid标识
        $friend_sid = UserService::getSidByUserId($data['friend_id']);
        //
        $socket->to($friend_sid)
               ->emit('message', $data['content'], SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::SUCCESS));
//        var_dump('onJoinRoom - getSid' . $socket->getSid());
        // 将当前用户加入房间
        $socket->join($data['group_id']);
        // 向房间内其他用户推送（不含当前用户）
        $socket->to($data['group_id'])
               ->emit('message', $socket->getSid() . "has joined {$data}", SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::SUCCESS));
        // 向房间内所有人广播（含当前用户）
        $this->emit('message', 'There are ' . count($socket->getAdapter()
                                                           ->clients($data)) . " players in {'room':" . (Json::decode($data)['room_id'] ?? 0) . "}", SocketConst::STATUS_SUCCESS, SocketConst::getMessage(SocketConst::SUCCESS));
    }

    /**
     * 所有人广播
     *
     * @Event("broadcasts")
     */
    public function onBroadcasts(Socket $socket, $data)
    {
        // 向所有连接推送 broadcast 事件，但是不包括当前连接。
        $socket->broadcast->emit('broadcast', 'hello friends!');
        // 向所有连接推送
        $socket->emit('an event sent to all connected clients');
    }

    /**
     * 好友列表
     *
     * @Event("friends")
     */


    protected function getArrayByData($data)
    {
        if (is_array($data)) return $data;
        return my_json_decode($data);
    }

    protected function getStringByData($data)
    {
        if (is_string($data)) return $data;
        return my_json_encode($data);
    }
}