<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Swoole extends CI_Controller
{
	public function index()
	{
		$user = array();
        // 建立 websocket 物件，監聽 0.0.0.0:8080 連接埠
		$ws = new swoole_websocket_server("0.0.0.0", 8080); // 0.0.0.0 等於 localhost
		
		// 監聽 WebSocket 連接打開事件
		$ws->on('open', function ($ws, $request) {
			$user[$request->fd] = 'name'.$request->fd;
			$ws->push($request->fd, json_encode($user));
		});
		
		// 監聽 WebSocket 訊息事件
		$ws->on('message', function ($ws, $frame) {
			echo "Message: {$frame->data}\n";

			foreach ($user as $fd => $name)
			{
				$ws->push($fd, json_encode($user));
			}
		});
		
		// 今天 WebSocket 連接關閉事件
		$ws->on('close', function ($ws, $fd) {
			unisset($user[$fd]);
			foreach ($user as $key => $name)
			{
				$ws->push($key, json_encode($user));
			}
			echo "client-{$fd} is closed\n";
		});
		
		$ws->start();
	}
}