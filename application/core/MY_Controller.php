<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $var = array('link_dbname'=>'');
    public $initated = false;
    public $allow = 0;
	public $langstr = 'tw';
    public $langid = 0;
    public $controllers = '';
    public $function = '';
	public $menuid = 0;
	public $menudata = array();
	public $domain = 'Mobile';
    
    public function __construct()
    {
        parent::__construct();
		$this->load->library('Session');
        $this->load->library('layout');
		$this->load->model('common');
		$this->load->helper('language');
		
        $this->common->is_install();
        //�s����Ʈw
        $logview = logview_file();
        $fp = fopen($logview, 'r');
        $content = fread($fp,filesize($logview));
        fclose($fp);
        $this->var['connect'] = $content;
        $content = decrypt($content,'runewaker');
        $node = explode(",", $content);
		load_db($node[0],$node[1],$node[2],$node[3]);
        
		$this->domain = str_replace(array('.gamewaker.com','.runewaker.com'),'',$_SERVER['SERVER_NAME']);
		$this->load->model('auth');
        $this->load->language('common');
        $this->load->language('menu');
        $this->load->language('column');
        $this->load->model('menu_model','menu_db');
        $this->load->model('language_model', 'language_db');
        $this->load->model('favoritegroup_model', 'favoritegroup_db');
        $this->load->model('favorite_model', 'favorite_db');
        $this->load->model('servermapping_model','servermapping_db');
        $this->load->model('setting_model','setting_db');
        header("Content-type: text/html; charset=utf-8");
        $this->langstr = $this->config->item('lang');
		foreach ($this->language_db->get_list() as $key => $val)
		{
			if ($val == $this->langstr) $this->langid = $key;
		}
		
        $this->controllers = substr($this->router->directory,1).$this->router->class;
        $this->function = $this->router->method;
		
		if (!in_array($this->controllers,array('schedule','api','admin'))) $this->auth->is_login();
        if (!in_array($this->controllers,array('home','favorite','ajax','schedule','api','setting','admin')) &&
			!($this->controllers == 'roledata' && $this->function == 'copyrole') &&
			!in_array($this->function,array('index'))) $this->auth->is_allow($this->controllers,$this->function);
        
        $this->allow = $this->common->get_allow($this->controllers,$this->function);
		
        $this->init();
		
		//server mapping����Ƥ~Ū��model
		if ($this->var['conn_data'])
		{
			$this->load->model('aliasaccount_model', 'aliasaccount_db');
			$this->load->model('gameloginfo_model','gameloginfo_db');
			$this->load->model('memberscoreinfo_model','memberscoreinof_db');
		}
    }
    
    public function init()
    {
		//$setting = $this->setting_db->get_list();
		
		if (!$this->initated)
		{
		//	if ($setting['update_tmp'] == 1)
		//	{
				$this->_init_menu();
				$this->_init_conn();
				
				$this->setting_db->update(array('skey'=>'update_tmp','svalue'=>0));
				$this->setting_db->update(array('skey'=>'sqlite_tmp','svalue'=>json_encode($this->var)));
		//	}
		//	else
		//	{
		//		$this->var = json_decode($setting['sqlite_tmp'],true);
		//	}
		}
		$this->initated = true;
    }
    
    public function _init_menu()
    {
		$this->var['menuname'] = '';
		$this->var['menuid'] = 0;
		$this->var['menudata'] = array();
        $result = $this->menu_db->where(array('upguid'=>0,'display'=>1))->order(array('sort','asc'))->result();
		//�Ĥ@�h
        foreach ($result as $key => $row)
        {
            $current = false;
            $rs = $this->menu_db->where(array('upguid'=>$row['guid'],'display'=>1))->order(array('sort','asc'))->result();
            if (count($rs) > 0)
            {
				//�ĤG�h
                foreach ($rs as $k => $arr)
                {
					$current2 = false;
					$rs2 = $this->menu_db->where(array('upguid'=>$arr['guid'],'display'=>1))->order(array('sort','asc'))->result();
					
					if (count($rs2) > 0)
					{
						//�ĤT�h
						foreach ($rs2 as $k2 => $arr2)
						{
							$ctrl = explode('/',$arr2['controller']);
							$param = explode('/',$arr2['param']);
							$uri = '';
							for ($i=1;$i<=count($param);$i++)
							{
								if ($uri != '') $uri .= '/';
								$uri .= $this->uri->segment($i+count($ctrl)+2);
							}
							$bool = $arr2['controller'] == $this->controllers && ($arr2['function'] == '' || $arr2['function'] == $this->function) && ($arr2['param'] == '' || $arr2['param'] == $uri);
							$arr2['current'] = $bool;
							if ($bool) 
							{
								$current = true;
								$current2 = true;
								$this->var['menuid'] = $arr2['guid'];
								$this->var['menudata'] = $arr2;
								$this->var['menuname'] = lang("menu_$row[name]_$arr[name]_$arr2[name]");
							}
							$rs2[$k2] = $arr2;
						}
					}
					else
					{
						$ctrl = explode('/',$arr['controller']);
						$param = explode('/',$arr['param']);
						$uri = '';
						for ($i=1;$i<=count($param);$i++)
						{
							if ($uri != '') $uri .= '/';
							$uri .= $this->uri->segment($i+count($ctrl)+2);
						}
						$bool = $arr['controller'] == $this->controllers && ($arr['function'] == '' || $arr['function'] == $this->function) && ($arr['param'] == '' || $arr['param'] == $uri);
						if ($bool) 
						{
							$current = true;
							$current2 = true;
							$this->var['menuid'] = $arr['guid'];
							$this->var['menudata'] = $arr;
							$this->var['menuname'] = lang("menu_$row[name]_$arr[name]");
						}
					}
					
					$arr['current'] = $current2;
					$arr['sub'] = $rs2;
					$rs[$k] = $arr;
                }
            }
            else
            {
                $current = $row['controller'] == $this->controllers && ($row['function'] == '' || $row['function'] == $this->function);
            }
            
            $row['current'] = $current;
            $row['sub'] = $rs;
            $result[$key] = $row;
        }
        $this->var['menu'] = $result;
        //$menudata = $this->menu_db->where(array('controller'=>$this->controllers))->result();
        //$this->var['menudata'] = count($menudata) > 0 ? $menudata[0]:array();
		
		//����
		$result = $this->menu_db->where(array('display'=>1))->result();
		$menulist = array();
		foreach ($result as $row)
		{
			$menulist[$row['guid']] = $row;
		}
		
		$favorite = $this->favoritegroup_db->where(array(
			'uid' => $this->session->userdata('uid')
		))->result();
		
		foreach ($favorite as $key => $row)
		{
			$rs = $this->favorite_db->where(array(
				'groupid' => $row['guid']
			))->result();
			
			$row['menu'] = array();
			$sub = 0;
			$current = false;
			foreach ($rs as $arr)
			{
				$sub++;
				$menudata = $menulist[$arr['menuid']];
				$parent = $menulist[$menudata['upguid']];
				$menudata['parent_name'] = $parent['name'];
				$menudata['current'] = false;
				if ($this->var['menuid'] == $arr['menuid'])
				{
					$current = true;
					$menudata['current'] = true;
				}
				$row['menu'][] = $menudata;
			}
			$row['sub'] = $sub;
			$row['current'] = $current;
			$favorite[$key] = $row;
		}
		
		$this->var['favorite'] = $favorite;
    }
    
    public function _init_conn()
    {
        $result = $this->servermapping_db->result();
        
        $server = array();
		$this->var['conn'] = $this->var['conn_data'] = array();
        foreach ($result as $row)
        {
            $this->var['conn'][Servermapping_model::$typeList[$row['type']]] = $row['dbname'];
            $this->var['conn_data'][Servermapping_model::$typeList[$row['type']]] = $row;
        }
		$this->var['conn']['server'] = $server;
    }
}