<?php
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
            $new_message = array(
                'err_code'=>0,
                'err_msg'=>'',
                'type'=>'say',
                'to_client_id'=>$message_data['to_client_id'],
                'time'=>date('Y-m-d H:i:s'),
                'content'=>$message_data['content']
            );
            var_dump(json_encode($new_message));die;
            Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
        }

?>