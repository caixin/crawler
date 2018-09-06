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

    public function happy10($numbers,$data)
    {
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

        return $data;
    }

    public function fast3($numbers,$data)
    {
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

        return $data;
    }

    public function tat($numbers,$data)
    {
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

        return $data;
    }

    public function pc28($numbers,$data)
    {
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

        return $data;
    }

    public function pk10($numbers,$data)
    {
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

        return $data;
    }

    public function lottery3($numbers,$data)
    {
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

        return $data;
    }

    public static $lottoryList = array(
        'hnkl10' => array('crontabs'=>'hnkl10','table'=>'bc_ettm_hn_v_happy_record'),
        'tjkl10' => array('crontabs'=>'tjkl10','table'=>'bc_ettm_tianjin_v_happy_record'),
        'gxk3' => array('crontabs'=>'gxf3','table'=>'bc_ettm_guangxi_fast_three_record'),
        'shk3' => array('crontabs'=>'shf3','table'=>'bc_ettm_shanghai_fast_three_record'),
        'jsk3' => array('crontabs'=>'jsf3','table'=>'bc_ettm_js_fast_three_record'),
        'xjssc' => array('crontabs'=>'xjtat','table'=>'bc_ettm_xinjiang_tat_record'),
        'cqssc' => array('crontabs'=>'cqtat','table'=>'bc_ettm_cq_tat_record'),
        'tjssc' => array('crontabs'=>'tjtat','table'=>'bc_ettm_tianjin_tat_record'),
        'canadapc28' => array('crontabs'=>'canadapc28','table'=>'bc_ettm_canada_pc_te_record'),
        'bjpc28' => array('crontabs'=>'bjpc28','table'=>'bc_ettm_bj_pc_te_record'),
        'gd11x5' => array('crontabs'=>'gd11x5','table'=>'bc_ettm_guangdong_eleven_select_five_record'),
        'jx11x5' => array('crontabs'=>'jx11x5','table'=>'bc_ettm_jiangxi_eleven_select_five_record'),
        'sd11x5' => array('crontabs'=>'sd11x5','table'=>'bc_ettm_shandong_eleven_select_five_record'),
        'xyft' => array('crontabs'=>'luckyap','table'=>'bc_ettm_lucky_ap_record'),
        'bjpk10' => array('crontabs'=>'bjpk10','table'=>'bc_ettm_bj_pk_record'),
        'pl3' => array('crontabs'=>'pl3','table'=>'bc_ettm_array_three_record'),
        'fc3d' => array('crontabs'=>'fc3d','table'=>'bc_ettm_blessing_color_three_d_record'),
    );
}