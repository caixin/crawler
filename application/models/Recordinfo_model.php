<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recordinfo_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->_db_name    = 'sqlite';
        $this->_table_name = 'record_info';
        $this->_key        = 'id';
    }
	
	public function load_db()
	{
        $CI =& get_instance();
        $CI->db = $this->load->database($this->_db_name, TRUE);
	}
    
    public function rules()
    {
        return array(
            array('field' => 'id','label' => 'id','rules' => 'trim|required'),
        );
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
	
	public function empty_table()
	{
		$this->load_db();
		$this->db->empty_table($this->_table_name);
	}
	
    public function _do_where()
    {
        if (! empty($this->_where))
        {
            if (isset($this->_where['servername'])) $this->db->where('t.servername', $this->_where['servername']);
            if (isset($this->_where['type'])) $this->db->where('t.type', $this->_where['type']);
        }
		return $this;
    }

    public function get_updatetime($type)
    {
        $servername = $_SERVER["SERVER_NAME"];
        $data = array(
            'servername' => $servername,
            'type' => $type,
        );

        $row = $this->where($data)->row_where();
        return isset($row['updatetime']) ? $row['updatetime']:'2000-01-01';
    }

    public function update_qishu($type,$qishu)
    {
        $servername = $_SERVER["SERVER_NAME"];
        $data = array(
            'servername' => $servername,
            'type' => $type,
        );

        $row = $this->where($data)->row_where();
        $data['lastqishu'] = $qishu;
        $data['updatetime'] = date('Y-m-d H:i:s');
        if (isset($row['id']))
        {
            $data['id'] = $row['id'];
            $this->update($data);
        }
        else
        {
            $this->create($data);
        }
    }
}