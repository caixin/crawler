<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bc_ettm_record_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->_db_name    = 'default';
        $this->_table_name = '';
        $this->_key        = 'id';
    }

    public function set_table($table)
    {
        $this->_table_name = $table;
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

    public static $lottoryList = array(
        'hnkl10' => array('crontabs'=>'crontabsSetHnVHappyOpen','table'=>'bc_ettm_hn_v_happy_record'),
        'tjkl10' => array('crontabs'=>'crontabsSetTjVHappyOpenAction','table'=>'bc_ettm_tianjin_v_happy_record'),
        'gxk3' => array('crontabs'=>'crontabsSetGxFastThreeOpenAction','table'=>'bc_ettm_guangxi_fast_three_record'),
        'shk3' => array('crontabs'=>'crontabsSetShFastThreeOpen','table'=>'bc_ettm_shanghai_fast_three_record'),
        'jsk3' => array('crontabs'=>'crontabsSetJsFastThreeOpen','table'=>'bc_ettm_js_fast_three_record'),
        'xjssc' => array('crontabs'=>'crontabsSetXjTatOpenAction','table'=>'bc_ettm_xinjiang_tat_record'),
        'cqssc' => array('crontabs'=>'crontabsSetCqTatOpenAction','table'=>'bc_ettm_cq_tat_record'),
        'tjssc' => array('crontabs'=>'crontabsSetTjTatOpenAction','table'=>'bc_ettm_tianjin_tat_record'),
        'canadapc28' => array('crontabs'=>'crontabsSetCanadaPcTeOpen','table'=>'bc_ettm_canada_pc_te_record','url'=>'dt_result_jnd'),
        'bjpc28' => array('crontabs'=>'crontabsSetBjPcTeOpen','table'=>'bc_ettm_bj_pc_te_record','url'=>'dt_result'),
        'gd11x5' => array('crontabs'=>'crontabsSetOfficialGd11x5OpenAction','table'=>'bc_ettm_guangdong_eleven_select_five_record'),
        'jx11x5' => array('crontabs'=>'crontabsSetOfficialJx11x5OpenAction','table'=>'bc_ettm_jiangxi_eleven_select_five_record'),
        'sd11x5' => array('crontabs'=>'crontabsSetOfficialSd11x5OpenAction','table'=>'bc_ettm_shandong_eleven_select_five_record'),
        'xyft' => array('crontabs'=>'crontabsSetLuckyApOpenAction','table'=>'bc_ettm_lucky_ap_record'),
        'bjpk10' => array('crontabs'=>'crontabsSetBjPKOpenAction','table'=>'bc_ettm_bj_pk_record'),
        'pl3' => array('crontabs'=>'crontabsSetOfficialPl3OpenAction','table'=>'bc_ettm_array_three_record'),
        'fc3d' => array('crontabs'=>'crontabsSetOfficialFc3dOpenAction','table'=>'bc_ettm_blessing_color_three_d_record'),
    );
}