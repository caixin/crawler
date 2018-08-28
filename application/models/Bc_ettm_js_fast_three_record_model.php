<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bc_ettm_js_fast_three_record_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->_db_name    = 'default';
        $this->_table_name = 'bc_ettm_js_fast_three_record';
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
            array('field' => 'dbname','label' => lang('servermapping_column_dbname'),'rules' => 'trim|required'),
        );
    }

    public function create($row)
    {
		$this->load_db();
        $date = date('Y-m-d H:i:s');
        $data = $row;
        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['create_by'] = 'crawler';
        $data['update_by'] = 'crawler';
        
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
        $data['update_time'] = time();
        $data['update_by'] = 'crawler';
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
            if (isset($this->_where['qishu'])) $this->db->where('t.qishu', $this->_where['qishu']);
        }
		return $this;
    }
}