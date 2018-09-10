<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grab extends CI_Controller 
{
    public function __construct()
    {
		parent::__construct();
		
        set_time_limit(0);
		$this->load->library('Simple_html_dom');
		$this->load->helper('mqtt');
        $this->load->model('Bc_ettm_record_model', 'bc_ettm_record_db');
        $this->load->model('Recordinfo_model', 'recordinfo_db');
	}

	public function index()
	{
		$minute = (date('H') * 60) + date('i');
		
		try
		{
			//湖南快10 開獎時間:09:10~23:00 10分鐘開一次
			if ($minute > 550 && $minute < 1390)
			{
				$run = false;
				$updatetime = $this->recordinfo_db->get_updatetime('hnkl10');
				if (date('Y-m-d H:i:s',time()-8*60) >= $updatetime)
				{
					$run = $this->happy10('hnkl10');
					if (!$run) $this->happy10_2('hnkl10');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "hnkl10 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		try
		{
			//天津快10 開獎時間:09:05~22:55 10分鐘開一次
			if ($minute > 545 && $minute < 1385)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('tjkl10');
				if (date('Y-m-d H:i:s',time()-8*60) >= $updatetime)
				{
					$run = $this->happy10('tjkl10');
					if (!$run) $this->happy10_2('tjkl10');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "tjkl10 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}

		try
		{
			//廣西快3 開獎時間:09:37~22:27 10分鐘開一次
			if ($minute > 577 && $minute < 1357)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('gxk3');
				if (date('Y-m-d H:i:s',time()-8*60) >= $updatetime)
				{
					$run = $this->fast3('gxk3');
					if (!$run) $this->fast3_2('gxk3');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "gxk3 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		try
		{
			//上海快3 開獎時間:08:58~22:28 10分鐘開一次
			if ($minute > 538 && $minute < 1358)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('shk3');
				if (date('Y-m-d H:i:s',time()-8*60) >= $updatetime)
				{
					$run = $this->fast3('shk3');
					if (!$run) $this->fast3_2('shk3');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "shk3 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		try
		{
			//江蘇快3 開獎時間:08:40~22:10 10分鐘開一次
			if ($minute > 520 && $minute < 1340)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('jsk3');
				if (date('Y-m-d H:i:s',time()-8*60) >= $updatetime)
				{
					$run = $this->fast3('jsk3');
					if (!$run) $this->fast3_2('jsk3');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "jsk3 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		
		try
		{
			//新疆時時彩 開獎時間:10:10~02:00 10分鐘開一次
			if ($minute > 610 || $minute < 130)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('xjssc');
				if (date('Y-m-d H:i:s',time()-8*60) >= $updatetime) $this->xjssc();
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "xjssc Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		try
		{
			//重慶時時彩 開獎時間:白天10:00~22:00 10分鐘開一次 夜場22:00~01:55 5分鐘開一次
			if ($minute > 600 || $minute < 125)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('cqssc');
				if (date('Y-m-d H:i:s',time()-3*60) >= $updatetime) $this->tat('cqssc');
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "cqssc Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		try
		{
			//天津時時彩 開獎時間:09:10~23:00 10分鐘開一次
			if ($minute > 550 && $minute < 1390)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('tjssc');
				if (date('Y-m-d H:i:s',time()-8*60) >= $updatetime) $this->tat2('tjssc');
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "tjssc Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}

		try
		{
			//廣東11選5 開獎時間:09:10~23:00 10分鐘開一次
			if ($minute > 550 && $minute < 1390)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('gd11x5');
				if (date('Y-m-d H:i:s',time()-8*60) >= $updatetime)
				{
					$run = $this->select5('gd11x5');
					if (!$run) $this->select5_2('gd11x5');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "gd11x5 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		try
		{
			//江西11選5 開獎時間:09:10~23:00 10分鐘開一次
			if ($minute > 550 && $minute < 1390)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('jx11x5');
				if (date('Y-m-d H:i:s',time()-8*60) >= $updatetime)
				{
					$run = $this->select5('jx11x5');
					if (!$run) $this->select5_2('jx11x5');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "jx11x5 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		try
		{
			//山東11選5 開獎時間:08:35~22:55 10分鐘開一次
			if ($minute > 515 && $minute < 1385)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('sd11x5');
				if (date('Y-m-d H:i:s',time()-8*60) >= $updatetime)
				{
					$run = $this->select5('sd11x5');
					if (!$run) $this->select5_2('sd11x5');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "sd11x5 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		
		try
		{
			//加拿大PC28 開獎時間: 210秒開一次
			$updatetime = $this->recordinfo_db->get_updatetime('canadapc28');
			if (date('Y-m-d H:i:s',time()-2*60) >= $updatetime)
			{
				$run = $this->pc28('canadapc28');
				if (!$run) $this->pc28_2('canadapc28');
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "canadapc28 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		try
		{
			//北京PC28 開獎時間:09:05~23:55 5分鐘開一次
			if ($minute > 545 || $minute < 5)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('bjpc28');
				if (date('Y-m-d H:i:s',time()-3*60) >= $updatetime)
				{
					$run = $this->pc28('bjpc28');
					if (!$run) $this->pc28_2('bjpc28');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "bjpc28 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		
		try
		{
			//幸運快艇 開獎時間:13:04~04:04 5分鐘開一次
			if ($minute > 784 || $minute < 254)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('xyft');
				if (date('Y-m-d H:i:s',time()-3*60) >= $updatetime)
				{
					$run = $this->pk10('xyft');
					if (!$run) $this->pk10_2('xyft');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "xyft Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		try
		{
			//北京PK10 開獎時間:09:07~23:57 5分鐘開一次
			if ($minute > 547 || $minute < 7)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('bjpk10');
				if (date('Y-m-d H:i:s',time()-2*60) >= $updatetime)
				{
					$run = $this->pk10('bjpk10');
					if (!$run) $this->pk10_2('bjpk10');
				}
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "bjpk10 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}

		try
		{
			//排列3 開獎時間: 20:30
			if ($minute > 1230 && $minute < 1250)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('pl3');
				if (date('Y-m-d H:i:s',time()-20*60) >= $updatetime) $this->lottery3('pl3');
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "pl3 Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
		try
		{
			//福彩3D 開獎時間: 21:15
			if ($minute > 1275 && $minute < 1295)
			{
				$updatetime = $this->recordinfo_db->get_updatetime('fc3d');
				if (date('Y-m-d H:i:s',time()-20*60) >= $updatetime) $this->lottery3('fc3d');
			}
		}
		catch (Exception $e)
		{
			mqtt_publish("home/web/crawler", "fc3d Error! Message:".$e->getMessage());
			log_message('error',$e->getMessage());
		}
	}

	//快樂10分
	public function happy10($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("https://pub.icaile.com/$play",false,$context);

		$data = $numbers = array();
		foreach (array('table1','table2','table3') as $table)
		{
			$tr = $dom->find("table.$table tr");
			foreach ($tr as $k => $v)
			{
				if ($k == 0) continue;
				if (count($v->find('td',1)->children()) < 8) break;

				$numbers = array();
				$qishu = explode('-',trim($v->find('td',0)->plaintext));
				$data['qishu'] = '20'.$qishu[0].str_pad(intval($qishu[1]),2,'0',STR_PAD_LEFT);
				$numbers[] = (int)trim($v->find('td',1)->find('em',0)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',1)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',2)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',3)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',4)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',5)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',6)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',7)->plaintext);
			}
		}

		foreach ($numbers as $val)
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}

		$data = $this->bc_ettm_record_db->happy10($numbers,$data);
		return $this->_dispatch($play,$data);
	}
	
	//快樂10分-2
	public function happy10_2($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$url = '';
		if ($play == 'hnkl10') $url = '135';
		if ($play == 'tjkl10') $url = '132';
		if ($url == '') return;
		$dom = file_get_html("https://www.ydniu.com/open/$url.html",false,$context);

		$data = $numbers = array();
		$tr = $dom->find("table.gg_ls",0)->find("tr",1);
		$data['qishu'] = str_replace('期','',trim($tr->find('td',0)->plaintext));
		$numbers[] = (int)trim($tr->find('td',1)->find('li',0)->plaintext);
		$numbers[] = (int)trim($tr->find('td',1)->find('li',1)->plaintext);
		$numbers[] = (int)trim($tr->find('td',1)->find('li',2)->plaintext);
		$numbers[] = (int)trim($tr->find('td',1)->find('li',3)->plaintext);
		$numbers[] = (int)trim($tr->find('td',1)->find('li',4)->plaintext);
		$numbers[] = (int)trim($tr->find('td',1)->find('li',5)->plaintext);
		$numbers[] = (int)trim($tr->find('td',1)->find('li',6)->plaintext);
		$numbers[] = (int)trim($tr->find('td',1)->find('li',7)->plaintext);
		foreach ($numbers as $val)
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}

		$data = $this->bc_ettm_record_db->happy10($numbers,$data);
		return $this->_dispatch($play,$data);
	}

	//快3
	public function fast3($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("https://pub.icaile.com/$play",false,$context);
		
		$data = $numbers = array();
		foreach (array('table1','table2','table3') as $table)
		{
			$tr = $dom->find("table.$table tr");
			foreach ($tr as $k => $v)
			{
				if ($k == 0) continue;
				if (count($v->find('td',1)->children()) < 3) break;

				$numbers = array();
				$qishu = explode('-',trim($v->find('td',0)->plaintext));
				$data['qishu'] = '20'.$qishu[0].str_pad(intval($qishu[1]),3,'0',STR_PAD_LEFT);
				$numbers[] = (int)trim($v->find('td',1)->find('em',0)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',1)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',2)->plaintext);
			}
		}

		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}
		
		$data = $this->bc_ettm_record_db->fast3($numbers,$data);
		return $this->_dispatch($play,$data);
	}

	//快3
	public function fast3_2($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$url = '';
		if ($play == 'gxk3') $url = '89';
		if ($play == 'shk3') $url = '119';
		if ($play == 'jsk3') $url = '86';
		if ($url == '') return;
		$dom = file_get_html("https://www.ydniu.com/open/$url.html",false,$context);

		$data = $numbers = array();
		$tr = $dom->find("table.gg_ls",0)->find("tr",1);
		$qishu = str_replace('期','',trim($tr->find('td',0)->plaintext));
		$data['qishu'] = substr($qishu,0,8).str_pad(substr($qishu,8),3,'0',STR_PAD_LEFT);
		$numbers[] = (int)trim($tr->find('td',2)->find('li',0)->plaintext);
		$numbers[] = (int)trim($tr->find('td',2)->find('li',1)->plaintext);
		$numbers[] = (int)trim($tr->find('td',2)->find('li',2)->plaintext);
		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}
		
		$data = $this->bc_ettm_record_db->fast3($numbers,$data);
		return $this->_dispatch($play,$data);
	}

	//新疆時時彩
	public function xjssc()
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("http://www.xjflcp.com/game/sscIndex",false,$context);
		
		$data = $numbers = array();
		$div = $dom->find("div.con_left",1);

		$data['qishu'] = trim($div->find('span',0)->plaintext);
		$numbers[] = trim($div->find('i',0)->plaintext);
		$numbers[] = trim($div->find('i',1)->plaintext);
		$numbers[] = trim($div->find('i',2)->plaintext);
		$numbers[] = trim($div->find('i',3)->plaintext);
		$numbers[] = trim($div->find('i',4)->plaintext);
		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}
		$data = $this->bc_ettm_record_db->tat($numbers,$data);
		$this->_dispatch('xjssc',$data);
	}

	//時時彩-重慶+新疆
	public function tat($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("http://www.ssztzzy.com/?c=record&a=game&lottery_type=$play",false,$context);
		
		$data = $numbers = array();
		$div = $dom->find("div.ionic-scroll",0)->find("div.row",0);

		$data['qishu'] = trim($div->find('div.cont',0)->find('div.ng-binding',0)->plaintext);
		$numbers[] = trim($div->find('div.cont',1)->find('span',0)->plaintext);
		$numbers[] = trim($div->find('div.cont',1)->find('span',1)->plaintext);
		$numbers[] = trim($div->find('div.cont',1)->find('span',2)->plaintext);
		$numbers[] = trim($div->find('div.cont',1)->find('span',3)->plaintext);
		$numbers[] = trim($div->find('div.cont',1)->find('span',4)->plaintext);
		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}

		$data = $this->bc_ettm_record_db->tat($numbers,$data);
		$this->_dispatch($play,$data);
	}

	//時時彩-天津
	public function tat2($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("https://pub.icaile.com/$play",false,$context);
		
		$data = $numbers = array();
		foreach (array('table1','table2','table3') as $table)
		{
			$tr = $dom->find("table.$table tr");
			foreach ($tr as $k => $v)
			{
				if ($k == 0) continue;
				if (count($v->find('td',1)->children()) < 5) break;

				$numbers = array();
				$qishu = explode('-',trim($v->find('td',0)->plaintext));
				$data['qishu'] = '20'.$qishu[0].str_pad(intval($qishu[1]),($play == 'xjssc'?2:3),'0',STR_PAD_LEFT);
				$numbers[] = (int)trim($v->find('td',1)->find('em',0)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',1)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',2)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',3)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',4)->plaintext);
			}
		}
		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}

		$data = $this->bc_ettm_record_db->tat($numbers,$data);
		$this->_dispatch($play,$data);
	}

	//11選5
	public function select5($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$dom = file_get_html("https://pub.icaile.com/$play",false,$context);

		$data = $numbers = array();
		foreach (array('table1','table2','table3') as $table)
		{
			$tr = $dom->find("table.$table tr");
			foreach ($tr as $k => $v)
			{
				if ($k == 0) continue;
				if (count($v->find('td',1)->children()) < 5) break;

				$numbers = array();
				$qishu = explode('-',trim($v->find('td',0)->plaintext));
				$data['qishu'] = '20'.$qishu[0].str_pad(intval($qishu[1]),3,'0',STR_PAD_LEFT);
				$numbers[] = (int)trim($v->find('td',1)->find('em',0)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',1)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',2)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',3)->plaintext);
				$numbers[] = (int)trim($v->find('td',1)->find('em',4)->plaintext);
			}
		}
		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}
		$data['numbers'] = implode(',',$numbers);
		$data['status'] = 1;

		return $this->_dispatch($play,$data);
	}

	//11選5
	public function select5_2($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$url = '';
		if ($play == 'gd11x5') $url = '78';
		if ($play == 'jx11x5') $url = '70';
		if ($play == 'sd11x5') $url = '62';
		if ($url == '') return;
		$dom = file_get_html("https://www.ydniu.com/open/$url.html",false,$context);

		$data = $numbers = array();
		$tr = $dom->find("table.gg_ls",0)->find("tr",1);
		$qishu = str_replace('期','',trim($tr->find('td',0)->plaintext));
		$data['qishu'] = substr($qishu,0,8).str_pad(substr($qishu,8),3,'0',STR_PAD_LEFT);
		$numbers[] = (int)trim($tr->find('td',2)->find('li',0)->plaintext);
		$numbers[] = (int)trim($tr->find('td',2)->find('li',1)->plaintext);
		$numbers[] = (int)trim($tr->find('td',2)->find('li',2)->plaintext);
		$numbers[] = (int)trim($tr->find('td',2)->find('li',3)->plaintext);
		$numbers[] = (int)trim($tr->find('td',2)->find('li',4)->plaintext);
		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}
		$data['numbers'] = implode(',',$numbers);
		$data['status'] = 1;
		
		return $this->_dispatch($play,$data);
	}

	//PC28
	public function pc28($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$url = '';
		if ($play == 'canadapc28') $url = 'dt_result_jnd';
		if ($play == 'bjpc28') $url = 'dt_result';
		if ($url == '') return false;
		$dom = file_get_html("http://www.cx997.com/index.php?m=Home&c=WebPc&a=$url",false,$context);

		$data = $numbers = array();
		$data['qishu'] = trim($dom->find('span#pc_tt',0)->plaintext);
		$numbers[] = trim($dom->find('div.ball',0)->plaintext);
		$numbers[] = trim($dom->find('div.ball',1)->plaintext);
		$numbers[] = trim($dom->find('div.ball',2)->plaintext);
		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}
		$data = $this->bc_ettm_record_db->pc28($numbers,$data);
		$this->_dispatch($play,$data);
	}

	//PC28
	public function pc28_2($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$url = '';
		if ($play == 'canadapc28') $url = 'jnd';
		if ($play == 'bjpc28') $url = 'dan';
		if ($url == '') return false;
		$dom = file_get_html("https://www.99yuce.com/yuce/$url.html",false,$context);

		$data = $numbers = array();
		$tr = $dom->find('table#tbe tr',2);
		$data['qishu'] = trim($tr->find('td',1)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.rball',0)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.rball',1)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.rball',2)->plaintext);
		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}
		$data = $this->bc_ettm_record_db->pc28($numbers,$data);
		$this->_dispatch($play,$data);
	}
	
	//PK10 幸運飛艇
	public function pk10($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
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
		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}

		$data = $this->bc_ettm_record_db->pk10($numbers,$data);
		$this->_dispatch($play,$data);
	}

	//PK10 幸運飛艇
	public function pk10_2($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$url = $play == 'bjpk10' ? '':$play;
		$dom = file_get_html("https://www-k123456.com/list-kaijianglishi{$url}.html",false,$context);
		
		$tr = $dom->find("table.sub_table tr",1);
		$numbers = $data = array();
		$data['qishu'] = trim($tr->find('td',1)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.ball_pks_',0)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.ball_pks_',1)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.ball_pks_',2)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.ball_pks_',3)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.ball_pks_',4)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.ball_pks_',5)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.ball_pks_',6)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.ball_pks_',7)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.ball_pks_',8)->plaintext);
		$numbers[] = trim($tr->find('td',2)->find('span.ball_pks_',9)->plaintext);
		foreach ($numbers as $val) 
		{
			if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
		}

		$data = $this->bc_ettm_record_db->pk10($numbers,$data);
		$this->_dispatch($play,$data);
	}

	//排列3 福彩3D
	public function lottery3($play)
	{
		$opts = array(
			'http' => array('header' => "User-Agent:MyAgent/1.0\r\n"),
			"ssl" => array("verify_peer"=>false,"verify_peer_name"=>false)
		);
		$context = stream_context_create($opts);
		$url = $play;
		if ($play == 'fc3d') $url = '3d';
		if ($play == 'pl3') $url = 'p3p5';
		$dom = file_get_html("http://www.china-lottery.net/$url",false,$context);
		
		$data['qishu'] = trim($dom->find('select#result_issue',0)->find('option',0)->plaintext);
		$numbers[] = trim($dom->find('span#data_lottery_0',0)->plaintext);
		$numbers[] = trim($dom->find('span#data_lottery_1',0)->plaintext);
		$numbers[] = trim($dom->find('span#data_lottery_2',0)->plaintext);
		$error = false;
		foreach ($numbers as $val)
		{
			if (!is_numeric($val)) $error = true;
		}
		//如果號碼有問題 抓另一個網址
		if ($error)
		{
			$numbers = array();
			$url = $play;
			if ($play == 'fc3d') $url = '3d';
			$dom = file_get_html("http://caipiao.163.com/award/$url",false,$context);
			
			$div = $dom->find("div.search_zj_left",0);
			$data['qishu'] = ($play == 'pl3'?'20':'').trim($div->find('span',0)->plaintext);
			$numbers[] = trim($div->find('p#zj_area',0)->find('span',0)->plaintext);
			$numbers[] = trim($div->find('p#zj_area',0)->find('span',1)->plaintext);
			$numbers[] = trim($div->find('p#zj_area',0)->find('span',2)->plaintext);
			foreach ($numbers as $val)
			{
				if (!is_numeric($val)) { mqtt_publish("home/web/crawler", "{$play}抓的開獎數字出錯!"); return false; }
			}
		}
		
		$data = $this->bc_ettm_record_db->lottery3($numbers,$data);
		$this->_dispatch($play,$data);
	}

	public function _dispatch($play,$data)
	{
		$lottery = Bc_ettm_record_model::$lottoryList[$play];
		$this->bc_ettm_record_db->set_table($lottery['table']);
		
		$row = $this->bc_ettm_record_db->where(array('qishu'=>$data['qishu']))->row_where();
		if (isset($row['id']) && intval($row['status']) == 0)
		{
			$data['id'] = $row['id'];
			$this->bc_ettm_record_db->update($data);
			
			//寫入最後開彩時間
			$this->recordinfo_db->update_qishu($play,$data['qishu']);
			
			//派彩
			$url = $this->config->item('lottery_domain')."index.php/rabbitMQ_c/RabbitMQ_open_numbers?key_word={$lottery['crontabs']}&qishu={$data['qishu']}";
			//file_get_contents($url);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $url);
			$result = curl_exec($ch);
			curl_close($ch);

			return true;
		}
		return false;
	}
}