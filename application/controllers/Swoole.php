<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Swoole extends CI_Controller
{
	public function index()
	{
        // 建立 websocket 物件，監聽 0.0.0.0:8080 連接埠
		$ws = new swoole_websocket_server("0.0.0.0", 8080); // 0.0.0.0 等於 localhost
		
		// 監聽 WebSocket 連接打開事件
		$ws->on('open', function ($ws, $request) {
			print_r($request);
			$ws->push($request->fd, "hello, welcome\n");
		});
		
		// 監聽 WebSocket 訊息事件
		$ws->on('message', function ($ws, $frame) {
			echo "Message: {$frame->data}\n";
			$ws->push($frame->fd, $frame->data);
		});
		
		// 今天 WebSocket 連接關閉事件
		$ws->on('close', function ($ws, $fd) {
			echo "client-{$fd} is closed\n";
		});
		
		$ws->start();
	}
}