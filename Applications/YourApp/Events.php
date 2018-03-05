<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        Gateway::sendToClient($client_id,json_encode(array(
            'type'=>'init',
            'client_id'=>$client_id
        )));
    }

    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage()
    {
        $client_id='{"to_client_id":"7f0000010b560000000e","content":{"server_command":1,"state":null,"balls":[{"index":0,"x":788.0,"y":353.5},{"index":12,"x":-644.0,"y":5.5},{"index":1,"x":-695.0,"y":-26.5},{"index":6,"x":-695.0,"y":31.5},{"index":15,"x":-745.0,"y":-55.5},{"index":8,"x":-745.0,"y":2.5},{"index":5,"x":-745.0,"y":60.5},{"index":4,"x":-795.0,"y":-84.5},{"index":14,"x":-795.0,"y":-26.5},{"index":7,"x":-795.0,"y":31.5},{"index":10,"x":-795.0,"y":89.5},{"index":9,"x":-845.0,"y":-113.5},{"index":2,"x":-845.0,"y":-55.5},{"index":11,"x":-845.0,"y":2.5},{"index":3,"x":-845.0,"y":60.5},{"index":13,"x":-845.0,"y":118.5}],"stroke_score":null,"error":null,"thread":null},"type":"say"}';
        // 客户端传递的是json数据
        $message_data = json_decode($client_id, true);
        if(!$message_data)
        {
            return;
        }
        // 根据类型执行不同的业务
        switch($message_data['type'])
        {
            // 客户端回应服务端的心跳
            case 'pong':
                return;
            case 'say':
                if(!Gateway::isOnline($client_id)){
                    $err=array(
                        'err_code'=>1,
                        'err_msg'=>'该client_id已经下线或不存在',
                        'type'=>'say'
                    );
                    Gateway::sendToClient($message_data['to_client_id'], json_encode($err));
                }else{
                    $new_message = array(
                        'err_code'=>0,
                        'err_msg'=>'',
                        'type'=>'say',
                        'from_client_id'=>$client_id,
                        'to_client_id'=>$message_data['to_client_id'],
                        'time'=>date('Y-m-d H:i:s'),
                        'content'=>nl2br(htmlspecialchars($message_data['content'])),
                    );
                    var_dump(json_encode($new_message));die;
                    Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
                }

        }
    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id)
    {
        Gateway::sendToAll(json_encode(array(
            'type'=>'logout',
            'client_id'=>$client_id
        )));
    }
}
