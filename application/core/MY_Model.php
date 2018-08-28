<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $_db_name    = null;
    protected $_table_name = null;
    protected $_key        = null;
    protected $_select     = array();
    protected $_where      = array();
    protected $_order      = array();
    protected $_limit      = array();
    protected $crossDB       = 0;

    public function __construct()
    {
        parent::__construct();
    }
	
	public function load_db()
	{
        $CI =& get_instance();
        $CI->db = $this->load->database($this->_db_name, TRUE);
	}
	
	public function field_data()
	{
		$this->load_db();
		return $this->db->field_data($this->_table_name);
	}

    public function select($data)
    {
        $this->_select = $data;
        return $this;
    }

    public function where($data)
    {
        $this->_where = $data;
        return $this;
    }

    public function order($data)
    {
        $this->_order = $data;
        return $this;
    }

    public function limit($data)
    {
        $this->_limit = $data;
        return $this;
    }

    public function reset()
    {
        $this->_select = array();
        $this->_where  = array();
        $this->_order  = array();
        $this->_limit  = array();
        return $this;
    }

    public function create($row)
    {
		$this->load_db();
        $date = date('Y-m-d H:i:s');
        $data = $row;
        
        $dbname = $this->crossDB ? $this->_db_name.'.':'';
        $this->db->insert($dbname.$this->_table_name, $data);
        $id = $this->db->insert_id();

        return $id;
    }

    public function quick_update($row)
    {
		$this->load_db();
        $data = $row;
		unset($data[$this->_key]);

        $dbname = $this->crossDB ? $this->_db_name.'.':'';
        $this->db->where($this->_key, $row[$this->_key])
                ->update($dbname.$this->_table_name, $data);
    }

    public function update($row)
    {
		$this->load_db();
        $date = date('Y-m-d H:i:s');
        $data = $row;
		unset($data[$this->_key]);

        $dbname = $this->crossDB ? $this->_db_name.'.':'';
        $this->db->where($this->_key, $row[$this->_key])
                ->update($dbname.$this->_table_name, $data);
    }
    
    public function update_where($row)
    {
		$this->load_db();
        $this->_do_where()->join();
        
        $date = date('Y-m-d H:i:s');
        $data = $row;
        
        $dbname = $this->crossDB ? $this->_db_name.'.':'';
        $this->db->update($dbname.$this->_table_name.' t', $data);
    }

    public function delete($id)
    {
		$this->load_db();
        $dbname = $this->crossDB ? $this->_db_name.'.':'';
        $this->db->where($this->_key, $id)->delete($dbname.$this->_table_name);
    }
	
    public function truncate()
    {
		$this->load_db();
        $dbname = $this->crossDB ? $this->_db_name.'.':'';
        $this->db->truncate($dbname.$this->_table_name);
    }

    public function row($id)
    {
		$this->load_db();
        $this->join();
        $this->db->where('t.'.$this->_key, $id);
        
        $dbname = $this->crossDB ? $this->_db_name.'.':'';
        return $this->db->get($dbname.$this->_table_name.' t')->row_array();
    }
    
    public function _do_where()
    {
        if (! empty($this->_where))
        {
            if (isset($this->_where[$this->_key])) $this->db->where($this->_key, $this->_where[$this->_key]);
        }
		
		return $this;
    }
    
    public function row_where()
    {
		$this->load_db();
        $this->_do_where()->join();

        if (! empty($this->_order))
        {
            if (isset($this->_order[0]))
            {
                $this->db->order_by($this->_order[0],$this->_order[1]);
            }
            else
            {
                foreach ($this->_order as $key => $val)
                {
                    $this->db->order_by($key, $val);
                }
            }
        }
		
        if (! empty($this->_limit))
        {
            $this->db->limit($this->_limit[1], $this->_limit[0]);
        }
        
        $dbname = $this->crossDB ? $this->_db_name.'.':'';
        $row = $this->db->get($dbname.$this->_table_name.' t')->row_array();
        $this->reset();

        return $row;
    }

    public function result()
    {
		$this->load_db();
        $this->_do_where()->join();

        if (! empty($this->_order))
        {
            if (isset($this->_order[0]))
            {
                $this->db->order_by($this->_order[0],$this->_order[1]);
            }
            else
            {
                foreach ($this->_order as $key => $val)
                {
                    $this->db->order_by($key, $val);
                }
            }
        }

        if (! empty($this->_limit))
        {
            $this->db->limit($this->_limit[1], $this->_limit[0]);
        }
        
        $dbname = $this->crossDB ? $this->_db_name.'.':'';
        $result = $this->db->get($dbname.$this->_table_name.' t')->result_array();
        $this->reset();

        return $result;
    }

    public function count()
    {
		$this->load_db();
        $this->_do_where()->join();
        $dbname = $this->crossDB ? $this->_db_name.'.':'';
        $count = $this->db->count_all_results($dbname.$this->_table_name.' t');
        $this->reset();
        return $count;
    }
    
    public function join()
    {
        return $this;
    }
    /*
    public function log_record($db='')
    {
        $CI =& get_instance();
        $connect = decrypt($CI->var['connect'],'runewaker');
        $node = explode(",", $connect);
        $logviewdb = load_db($node[0],$node[1],$node[2],$node[3]);
		//modify 2016/12/23
		$uid = ($this->session->userdata('uid'))?$this->session->userdata('uid'):"123";
		
		//modify 2016/12/23
		$data = array(
            //'uid' => $this->session->userdata('uid'),
			'uid' => $uid,
            'controller' => $this->router->fetch_class(),
            'action' => $this->router->fetch_method(),
            'tablename' => $this->_table_name,
            'createtime' => date('Y-m-d H:i:s'),
        );
        $data['sql_string'] = $db == '' ? $this->db->last_query():$db->last_query();
		$logviewdb->insert('log_operation',$data);
    }*/
}