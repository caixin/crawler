<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grab extends CI_Controller 
{
    public function __construct()
    {
		parent::__construct();
		
        set_time_limit(0);
		$this->load->library('Simple_html_dom');
        $this->load->model('Bc_ettm_record_model', 'bc_ettm_record_db');
	}

	public function index()
	{
		$hour = date('H');
		$minute = date('i');

		try
		{
			//湖南快10 開獎時間:09:10~23:00 10分鐘開一次
			if ($hour > 8 && $hour < 24) $this->happy10('hnkl10');
		}
		catch (Exception $e)
		{
			log_message('error',"hnkl10 Error!");
			log_message('error',$e->getMessage());
		}
		try
		{
			//天津快10 開獎時間:09:05~22:55 10分鐘開一次
			if ($hour > 8 && $hour < 24) $this->happy10('tjkl10');
		}
		catch (Exception $e)
		{
			log_message('error',"tjkl10 Error!");
			log_message('error',$e->getMessage());
		}

		try
		{
			//廣西快3 開獎時間:09:37~22:27 10分鐘開一次
			if ($hour > 8 && $hour < 23) $this->fast3('gxk3');
		}
		catch (Exception $e)
		{
			log_message('error',"gxk3 Error!");
			log_message('error',$e->getMessage());
		}
		try
		{
			//上海快3 開獎時間:08:58~21:08 10分鐘開一次
			if ($hour > 7 && $hour < 22) $this->fast3('shk3');
		}
		catch (Exception $e)
		{
			log_message('error',"shk3 Error!");
			log_message('error',$e->getMessage());
		}
		try
		{
			//江蘇快3 開獎時間:08:40~21:10 10分鐘開一次
			if ($hour > 7 && $hour < 22) $this->fast3('jsk3');
		}
		catch (Exception $e)
		{
			log_message('error',"jsk3 Error!");
			log_message('error',$e->getMessage());
		}

		try
		{
			//新疆時時彩 開獎時間:10:10~02:00 10分鐘開一次
			if (($hour > 9 && $hour < 24) || ($hour >= 0 && $hour < 3)) $this->tat('xjssc'); 
		}
		catch (Exception $e)
		{
			log_message('error',"xjssc Error!");
			log_message('error',$e->getMessage());
		}
		try
		{
			//重慶時時彩 開獎時間:白天10:00~22:00 10分鐘開一次 夜場22:00~01:55 5分鐘開一次
			if ($hour > 9 && $hour < 23) $this->tat('cqssc');
		}
		catch (Exception $e)
		{
			log_message('error',"cqssc Error!");
			log_message('error',$e->getMessage());
		}
		try
		{
			//天津時時彩 開獎時間:09:10~23:00 10分鐘開一次
			if ($hour > 8 && $hour < 24) $this->tat('tjssc');
		}
		catch (Exception $e)
		{
			log_message('error',"tjssc Error!");
			log_message('error',$e->getMessage());
		}

		try
		{
			//廣東11選5 開獎時間:09:10~23:00 10分鐘開一次
			if ($hour > 8 && $hour < 24) $this->select5('gd11x5');
		}
		catch (Exception $e)
		{
			log_message('error',"gd11x5 Error!");
			log_message('error',$e->getMessage());
		}
		try
		{
			//江西11選5 開獎時間:09:10~23:00 10分鐘開一次
			if ($hour > 8 && $hour < 24) $this->select5('jx11x5');
		}
		catch (Exception $e)
		{
			log_message('error',"jx11x5 Error!");
			log_message('error',$e->getMessage());
		}
		try
		{
			//山東11選5 開獎時間:09:05~21:55 10分鐘開一次
			if ($hour > 8 && $hour < 22) $this->select5('sd11x5');
		}
		catch (Exception $e)
		{
			log_message('error',"sd11x5 Error!");
			log_message('error',$e->getMessage());
		}
		
		try
		{
			//加拿大PC28 開獎時間: 210秒開一次
			$this->pc28('canadapc28');
		}
		catch (Exception $e)
		{
			log_message('error',"canadapc28 Error!");
			log_message('error',$e->getMessage());
		}
		try
		{
			//北京PC28 開獎時間:09:05~23:55 5分鐘開一次
			if ($hour > 8 && $hour < 24) $this->pc28('bjpc28');
		}
		catch (Exception $e)
		{
			log_message('error',"bjpc28 Error!");
			log_message('error',$e->getMessage());
		}
		
		try
		{
			//幸運快艇 開獎時間:13:04~04:04 5分鐘開一次
			if ($hour > 12 && $hour < 24 || ($hour >= 0 && $hour < 5)) $this->pk10('xyft');
		}
		catch (Exception $e)
		{
			log_message('error',"xyft Error!");
			log_message('error',$e->getMessage());
		}
		try
		{
			//北京PK10 開獎時間:09:07~23:57 10分鐘開一次
			if ($hour > 8 && $hour < 24) $this->pk10('bjpk10');
		}
		catch (Exception $e)
		{
			log_message('error',"bjpk10 Error!");
			log_message('error',$e->getMessage());
		}

		try
		{
			//排列3 開獎時間: 20:30
			if ($hour > 19 && $hour < 21) $this->lottery3('pl3');
		}
		catch (Exception $e)
		{
			log_message('error',"pl3 Error!");
			log_message('error',$e->getMessage());
		}
		try
		{
			//福彩3D 開獎時間: 21:15
			if ($hour > 20 && $hour < 22) $this->lottery3('fc3d');
		}
		catch (Exception $e)
		{
			log_message('error',"fc3d Error!");
			log_message('error',$e->getMessage());
		}
	}

	//快樂10分
	public function happy10($play)
	{
		$lottery = Bc_ettm_record_model::$lottoryList[$play];
		$this->bc_ettm_record_db->set_table($lottery['table']);

		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array(
				"verify_peer"=>false,
            	"verify_peer_name"=>false,
			)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("https://pub.icaile.com/$play",false,$context);

		$data = $numbers = array();
		$div = $dom->find("div.newPeriodBox",0)->find("div.right",0);
		$data['qishu'] = '20'.trim(str_replace('-','',$div->find('span',0)->plaintext));
		$numbers[] = trim($div->find('span',1)->plaintext);
		$numbers[] = trim($div->find('span',2)->plaintext);
		$numbers[] = trim($div->find('span',3)->plaintext);
		$numbers[] = trim($div->find('span',4)->plaintext);
		$numbers[] = trim($div->find('span',5)->plaintext);
		$numbers[] = trim($div->find('span',6)->plaintext);
		$numbers[] = trim($div->find('span',7)->plaintext);
		$numbers[] = trim($div->find('span',8)->plaintext);
		$data['numbers'] = implode(',',$numbers);
		$data['status'] = 1;
		
		$total = array_sum($numbers);
		//大小
		if ($total > 84) $data['value_one'] = '总大';
		if ($total < 84) $data['value_one'] = '总小';
		if ($total == 84) $data['value_one'] = '和';
		//單雙
		$data['value_two'] = ($total % 2 == 0) ? '双':'单';
		//尾數
		$mantissa = (int)substr($total,-1);
		$data['value_three'] = $mantissa >= 5 ? '尾大':'尾小';
		//龍虎
		$data['value_four'] = $numbers[0] > $numbers[7] ? '龙':'虎';

		$row = $this->bc_ettm_record_db->where(array('qishu'=>$data['qishu']))->row_where();
		if (!isset($row['id']))
		{
			//新增
			$this->bc_ettm_record_db->create($data);
			
			$this->_dispatch($lottery['crontabs'],$data['qishu']);
		}
		else
		{
			if ($row['numbers'] == '')
			{
				//沒資料就更新
				$data['id'] = $row['id'];
				$this->bc_ettm_record_db->update($data);
				
				$this->_dispatch($lottery['crontabs'],$data['qishu']);
			}
		}
	}

	//快3
	public function fast3($play)
	{
		$lottery = Bc_ettm_record_model::$lottoryList[$play];
		$this->bc_ettm_record_db->set_table($lottery['table']);

		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array(
				"verify_peer"=>false,
            	"verify_peer_name"=>false,
			)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("https://pub.icaile.com/$play",false,$context);
		
		$data = $numbers = array();
		$div = $dom->find("div.newPeriodBox",0)->find("div.right",0);
		$data['qishu'] = '20'.trim(str_replace('-','',$div->find('span',0)->plaintext));
		$numbers[] = trim($div->find('span',1)->plaintext);
		$numbers[] = trim($div->find('span',2)->plaintext);
		$numbers[] = trim($div->find('span',3)->plaintext);
		$data['number_one'] = $numbers[0];
		$data['number_two'] = $numbers[1];
		$data['number_three'] = $numbers[2];
		$data['status'] = 1;
		
		$total = array_sum($numbers);
		$data['value_three'] = $total;
		//大小
		$data['value_one'] = ($total > 10) ? 1:0;
		//單雙
		$data['value_two'] = ($total % 2 == 0) ? 0:1;
		//三號不同
		$data['value_four'] = count(array_unique($numbers)) == 3 ? 1:0;
		//三號順子
		$data['value_five'] = $numbers[2] - $numbers[1] == 1 && $numbers[1] - $numbers[0] == 1 ? 1:0;
		//豹子
		$data['value_six'] = $numbers[2] == $numbers[1] && $numbers[1] == $numbers[0] ? 1:0;
		//兩號複選
		$data['value_seven'] = $numbers[2] == $numbers[1] || $numbers[1] == $numbers[0] || $numbers[2] == $numbers[0] ? 1:0;
		
		$row = $this->bc_ettm_record_db->where(array('qishu'=>$data['qishu']))->row_where();
		if (!isset($row['id']))
		{
			//新增
			$this->bc_ettm_record_db->create($data);
			
			$this->_dispatch($lottery['crontabs'],$data['qishu']);
		}
		else
		{
			if ($row['number_one'] == '')
			{
				//沒資料就更新
				$data['id'] = $row['id'];
				$this->bc_ettm_record_db->update($data);

				$this->_dispatch($lottery['crontabs'],$data['qishu']);
			}
		}
	}

	//時時彩
	public function tat($play)
	{
		$lottery = Bc_ettm_record_model::$lottoryList[$play];
		$this->bc_ettm_record_db->set_table($lottery['table']);

		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array(
				"verify_peer"=>false,
            	"verify_peer_name"=>false,
			)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("https://pub.icaile.com/$play",false,$context);
		
		$data = $numbers = array();
		$div = $dom->find("div.newPeriodBox",0)->find("div.right",0);
		if ($play == 'xjssc')
		{
			$qishu = explode('-',trim($div->find('span',0)->plaintext));
			$data['qishu'] = '20'.$qishu[0].substr($qishu[1],1);
		}
		else
		{
			$data['qishu'] = '20'.trim(str_replace('-','',$div->find('span',0)->plaintext));
		}
		$numbers[] = trim($div->find('span',1)->plaintext);
		$numbers[] = trim($div->find('span',2)->plaintext);
		$numbers[] = trim($div->find('span',3)->plaintext);
		$numbers[] = trim($div->find('span',4)->plaintext);
		$numbers[] = trim($div->find('span',5)->plaintext);
		$data['numbers'] = implode(',',$numbers);
		$data['status'] = 1;
				
		$top3 = array_slice($numbers,0,3);
		$medium3 = array_slice($numbers,1,3);
		$after3 = array_slice($numbers,2,3);
		sort($top3);
		sort($medium3);
		sort($after3);

		$data['value_one'] = $data['value_two'] = $data['value_three'] = $data['value_four'] = $data['value_five'] = 0;
		if (count(array_flip($top3)) == 1) $data['value_one'] = 1;
		elseif (($top3[2] - $top3[1] == 1 && $top3[1] - $top3[0] == 1) || $top3 == [0,1,9] || $top3 == [0,8,9]) $data['value_two'] = 1;
		elseif (count(array_flip($top3)) == 2) $data['value_three'] = 1;
		elseif ($top3[1] - $top3[0] == 1 || $top3[2] - $top3[1] == 1 || $top3[2] - $top3[0] == 9) $data['value_four'] = 1;
		else $data['value_five'] = 1;
		
		$data['value_six'] = $data['value_seven'] = $data['value_eight'] = $data['value_nine'] = $data['value_ten'] = 0;
		if (count(array_flip($medium3)) == 1) $data['value_six'] = 1;
		elseif (($medium3[2] - $medium3[1] == 1 && $medium3[1] - $medium3[0] == 1) || $medium3 == [0,1,9] || $medium3 == [0,8,9]) $data['value_seven'] = 1;
		elseif (count(array_flip($medium3)) == 2) $data['value_eight'] = 1;
		elseif ($medium3[1] - $medium3[0] == 1 || $medium3[2] - $medium3[1] == 1 || $medium3[2] - $medium3[0] == 9) $data['value_nine'] = 1;
		else $data['value_ten'] = 1;
		
		$data['value_eleven'] = $data['value_twelve'] = $data['value_thirteen'] = $data['value_fourteen'] = $data['value_fifteen'] = 0;
		if (count(array_flip($after3)) == 1) $data['value_eleven'] = 1;
		elseif (($after3[2] - $after3[1] == 1 && $after3[1] - $after3[0] == 1) || $after3 == [0,1,9] || $after3 == [0,8,9]) $data['value_twelve'] = 1;
		elseif (count(array_flip($after3)) == 2) $data['value_thirteen'] = 1;
		elseif ($after3[1] - $after3[0] == 1 || $after3[2] - $after3[1] == 1 || $after3[2] - $after3[0] == 9) $data['value_fourteen'] = 1;
		else $data['value_fifteen'] = 1;
		
		$row = $this->bc_ettm_record_db->where(array('qishu'=>$data['qishu']))->row_where();
		if (!isset($row['id']))
		{
			//新增
			$this->bc_ettm_record_db->create($data);
			
			$this->_dispatch($lottery['crontabs'],$data['qishu']);
		}
		else
		{
			if ($row['numbers'] == '')
			{
				//沒資料就更新
				$data['id'] = $row['id'];
				$this->bc_ettm_record_db->update($data);

				$this->_dispatch($lottery['crontabs'],$data['qishu']);
			}
		}
	}

	//11選5
	public function select5($play)
	{
		$lottery = Bc_ettm_record_model::$lottoryList[$play];
		$this->bc_ettm_record_db->set_table($lottery['table']);

		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array(
				"verify_peer"=>false,
            	"verify_peer_name"=>false,
			)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("https://pub.icaile.com/$play",false,$context);

		$data = $numbers = array();
		$div = $dom->find("div.newPeriodBox",0)->find("div.right",0);
		$qishu = explode('-',trim($div->find('span',0)->plaintext));
		$data['qishu'] = '20'.$qishu[0].str_pad((int)$qishu[1],3,'0',STR_PAD_LEFT);
		$numbers[] = trim($div->find('span',1)->plaintext);
		$numbers[] = trim($div->find('span',2)->plaintext);
		$numbers[] = trim($div->find('span',3)->plaintext);
		$numbers[] = trim($div->find('span',4)->plaintext);
		$numbers[] = trim($div->find('span',5)->plaintext);
		$data['numbers'] = implode(',',$numbers);
		$data['status'] = 1;

		$row = $this->bc_ettm_record_db->where(array('qishu'=>$data['qishu']))->row_where();
		if (!isset($row['id']))
		{
			//新增
			$this->bc_ettm_record_db->create($data);
			
			$this->_dispatch($lottery['crontabs'],$data['qishu']);
		}
		else
		{
			if ($row['numbers'] == '')
			{
				//沒資料就更新
				$data['id'] = $row['id'];
				$this->bc_ettm_record_db->update($data);

				$this->_dispatch($lottery['crontabs'],$data['qishu']);
			}
		}
	}

	//PC28
	public function pc28($play)
	{
		$lottery = Bc_ettm_record_model::$lottoryList[$play];
		$this->bc_ettm_record_db->set_table($lottery['table']);

		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array(
				"verify_peer"=>false,
            	"verify_peer_name"=>false,
			)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("http://www.cx997.com/index.php?m=Home&c=WebPc&a=$lottery[url]",false,$context);

		$data = $numbers = array();
		$data['qishu'] = trim($dom->find('span#pc_tt',0)->plaintext);
		$numbers[] = trim($dom->find('div.ball',0)->plaintext);
		$numbers[] = trim($dom->find('div.ball',1)->plaintext);
		$numbers[] = trim($dom->find('div.ball',2)->plaintext);
		$data['number_one'] = $numbers[0];
		$data['number_two'] = $numbers[1];
		$data['number_three'] = $numbers[2];
		$data['status'] = 1;
		
		$total = array_sum($numbers);
		$data['value_three'] = $total;
		//大小
		$data['value_one'] = $total > 13 ? 1:0;
		//單雙
		$data['value_two'] = $total % 2 == 0 ? 0:1;
		//大小-單
		$data['value_four'] = $total % 2 == 0 ? 0:($total > 13 ? 1:2);
		//大小-雙
		$data['value_five'] = $total % 2 == 1 ? 0:($total > 13 ? 1:2);
		//極大小
		$data['value_six'] = $total >= 22 ? 1:($total <= 5 ? 2:0);
		//兩號複選
		$data['value_seven'] = count(array_flip($numbers)) == 2 ? 1:0;
		//豹子
		$data['value_eight'] = count(array_flip($numbers)) == 1 ? 1:0;
		//龍虎
		$data['value_nine'] = $numbers[0] > $numbers[2] ? 1:($numbers[0] < $numbers[2] ? 0:2);
		//三號順子
		sort($numbers);
		$data['value_ten'] = $numbers[2] - $numbers[1] == 1 && $numbers[1] - $numbers[0] == 1 ? 1:0;
		
		$row = $this->bc_ettm_record_db->where(array('qishu'=>$data['qishu']))->row_where();
		if (!isset($row['id']))
		{
			//新增
			$this->bc_ettm_record_db->create($data);
			
			$this->_dispatch($lottery['crontabs'],$data['qishu']);
		}
		else
		{
			if ($row['number_one'] == '')
			{
				//沒資料就更新
				$data['id'] = $row['id'];
				$this->bc_ettm_record_db->update($data);

				$this->_dispatch($lottery['crontabs'],$data['qishu']);
			}
		}
	}

	//PK10 幸運飛艇
	public function pk10($play)
	{
		$lottery = Bc_ettm_record_model::$lottoryList[$play];
		$this->bc_ettm_record_db->set_table($lottery['table']);

		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array(
				"verify_peer"=>false,
            	"verify_peer_name"=>false,
			)
		);
		$context = stream_context_create($opts);
		$url = $play == 'bjpk10' ? 'pk10':$play;
		$dom = file_get_html("http://www.150557e.com/{$url}_historyr.php",false,$context);
		
		$tr = $dom->find("table#table-pk10 tr",1);
		$numbers = $data = array();
		$data['qishu'] = trim($tr->find('td',0)->find('span',0)->plaintext);
		$numbers[] = trim($tr->find('td',1)->find('span',0)->plaintext);
		$numbers[] = trim($tr->find('td',1)->find('span',1)->plaintext);
		$numbers[] = trim($tr->find('td',1)->find('span',2)->plaintext);
		$numbers[] = trim($tr->find('td',1)->find('span',3)->plaintext);
		$numbers[] = trim($tr->find('td',1)->find('span',4)->plaintext);
		$numbers[] = trim($tr->find('td',1)->find('span',5)->plaintext);
		$numbers[] = trim($tr->find('td',1)->find('span',6)->plaintext);
		$numbers[] = trim($tr->find('td',1)->find('span',7)->plaintext);
		$numbers[] = trim($tr->find('td',1)->find('span',8)->plaintext);
		$numbers[] = trim($tr->find('td',1)->find('span',9)->plaintext);
		$data['numbers'] = implode(',',$numbers);
		$data['status'] = 1;
		
		//大小
		$data['value_one'] = ($numbers[0] + $numbers[1]) > 11 ? '大':'小';
		//單雙
		$data['value_two'] = ($numbers[0] + $numbers[1]) % 2 == 1 ? '单':'双';
		//冠軍號
		$data['value_three'] = $numbers[0];
		//0-9龍虎
		$data['value_four'] = $numbers[0] > $numbers[9] ? '龙':'虎';
		//1-8龍虎
		$data['value_five'] = $numbers[1] > $numbers[8] ? '龙':'虎';
		//2-7龍虎
		$data['value_six'] = $numbers[2] > $numbers[7] ? '龙':'虎';
		//3-6龍虎
		$data['value_seven'] = $numbers[3] > $numbers[6] ? '龙':'虎';
		//4-5龍虎
		$data['value_eight'] = $numbers[4] > $numbers[5] ? '龙':'虎';
		
		$row = $this->bc_ettm_record_db->where(array('qishu'=>$data['qishu']))->row_where();
		if (!isset($row['id']))
		{
			//新增
			$this->bc_ettm_record_db->create($data);
			
			$this->_dispatch($lottery['crontabs'],$data['qishu']);
		}
		else
		{
			if ($row['numbers'] == '')
			{
				//沒資料就更新
				$data['id'] = $row['id'];
				$this->bc_ettm_record_db->update($data);

				$this->_dispatch($lottery['crontabs'],$data['qishu']);
			}
		}
	}

	//排列3 福彩3D
	public function lottery3($play)
	{
		$lottery = Bc_ettm_record_model::$lottoryList[$play];
		$this->bc_ettm_record_db->set_table($lottery['table']);

		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array(
				"verify_peer"=>false,
            	"verify_peer_name"=>false,
			)
		);
		$context = stream_context_create($opts);
		$url = $play == 'fc3d' ? '3d':$play;
		$dom = file_get_html("http://caipiao.163.com/award/$url",false,$context);
		
		$div = $dom->find("div.search_zj_left",0);
		$data['qishu'] = ($play == 'pl3'?'20':'').trim($div->find('span',0)->plaintext);
		$numbers[] = trim($div->find('p#zj_area',0)->find('span',0)->plaintext);
		$numbers[] = trim($div->find('p#zj_area',0)->find('span',1)->plaintext);
		$numbers[] = trim($div->find('p#zj_area',0)->find('span',2)->plaintext);
		$data['numbers'] = implode(',',$numbers);
		$data['status'] = 1;

		//個位數大小
		$data['value_one'] = $numbers[2] > 4 ? 1:0;
		//個位數單雙
		$data['value_two'] = $numbers[2] % 2 == 1 ? 1:0;
		//十位數大小
		$data['value_three'] = $numbers[1] > 4 ? 1:0;
		//十位數單雙
		$data['value_four'] = $numbers[1] % 2 == 1 ? 1:0;
		//百位數大小
		$data['value_five'] = $numbers[0] > 4 ? 1:0;
		//百位數單雙
		$data['value_six'] = $numbers[0] % 2 == 1 ? 1:0;
		
		$row = $this->bc_ettm_record_db->where(array('qishu'=>$data['qishu']))->row_where();
		if (!isset($row['id']))
		{
			//新增
			$this->bc_ettm_record_db->create($data);
			
			$this->_dispatch($lottery['crontabs'],$data['qishu']);
		}
		else
		{
			if ($row['numbers'] == '')
			{
				//沒資料就更新
				$data['id'] = $row['id'];
				$this->bc_ettm_record_db->update($data);

				$this->_dispatch($lottery['crontabs'],$data['qishu']);
			}
		}
	}

	private function _dispatch($keyword,$qishu)
	{
		//派彩
		//$url = $this->config->item('lottery_domain')."index.php/rabbitMQ_c/RabbitMQ_open_numbers?key_word=$keyword&qishu=$qishu";
		//file_get_contents($url);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $this->config->item('lottery_domain')."index.php/rabbitMQ_c/RabbitMQ_open_numbers?key_word=$keyword&qishu=$qishu");
		$result = curl_exec($ch);
		curl_close($ch);
	}

	//湖南快十
	/*
	public function hnkl10()
	{
        $this->load->model('Bc_ettm_hn_v_happy_record_model', 'bc_ettm_hn_v_happy_record_db');

		$opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));
		$context = stream_context_create($opts);
		$dom = file_get_html('http://hnkl10.icaile.com',false,$context);
		
		$tr = $dom->find('table#fixedtable tr');
		foreach ($tr as $k => $v)
		{
			if ($k < 2) continue;
			if ($v->find('td',2) == false) break;
			$numbers = $data = array();

			$data['qishu'] = '20'.$v->find('td',0)->plaintext;
			$numbers[] = $v->find('td',1)->plaintext;
			$numbers[] = $v->find('td',2)->plaintext;
			$numbers[] = $v->find('td',3)->plaintext;
			$numbers[] = $v->find('td',4)->plaintext;
			$numbers[] = $v->find('td',5)->plaintext;
			$numbers[] = $v->find('td',6)->plaintext;
			$numbers[] = $v->find('td',7)->plaintext;
			$numbers[] = $v->find('td',8)->plaintext;
			$data['numbers'] = implode(',',$numbers);
			$data['status'] = 1;
			
			$total = array_sum($numbers);
			//大小
			if ($total > 84) $data['value_one'] = '总大';
			if ($total < 84) $data['value_one'] = '总小';
			if ($total == 84) $data['value_one'] = '和';
			//單雙
			$data['value_two'] = ($total % 2 == 0) ? '双':'单';
			//尾數
			$mantissa = (int)substr($total,-1);
			$data['value_three'] = $mantissa >= 5 ? '尾大':'尾小';
			//龍虎
			$data['value_four'] = $numbers[0] > $numbers[7] ? '龙':'虎';

			$row = $this->bc_ettm_hn_v_happy_record_db->where(array('qishu'=>$data['qishu']))->row_where();
			if (!isset($row['id']))
			{
				//新增
				$this->bc_ettm_hn_v_happy_record_db->create($data);
				
				//派彩
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('qishu'=>$data['qishu'])));
				curl_setopt($ch, CURLOPT_URL, $this->config->item('lottery_domain')."index.php/crontabs/crontabsSetHnVHappyOpen?qishu=$data[qishu]");
				curl_exec($ch);
				curl_close($ch);
			}
		}
	}*/
}