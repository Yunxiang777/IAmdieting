<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Diet_model 飲食紀錄
 * 
 */
class Diet_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 寫入飲食紀錄
     * 
     * @param array $data 飲食紀錄資訊
     * @return string 新插入資料ID 或者 空字串
     */
    public function addDietRecord($data)
    {
        try {
            $this->db->insert('diet_records', $data);
            return $this->db->insert_id();
        } catch (Exception $e) {
            log_message('error', 'Error occurred while inserting diet record: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * 根據會員ID取出飲食紀錄
     * 
     * @param int $memberId 會員ID
     * @return array 飲食紀錄數組或空數組
     */
    public function getDietRecords($memberId)
    {
        try {
            $query = $this->db->get_where('diet_records', array(
                'memberId' => $memberId,
                "date" => date('Y-m-d')
            ));
            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error occurred while fetching diet records: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * 刪除指定飲食紀錄
     * 
     * @param int $recordId 飲食紀錄ID
     * @return boolean 
     */
    public function deleteMeal($recordId)
    {
        try {
            $this->db->where('id', $recordId);
            $this->db->delete('diet_records');
            return $this->db->affected_rows() > 0 ? true : false;
        } catch (Exception $e) {
            log_message('error', 'Error occurred while deleting diet record: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 更新紀錄
     * 
     * @param array $meal 紀錄數據
     * @return boolean 
     */
    public function updateMeal($meal)
    {
        try {
            $this->db->where('id', $meal['id']);
            unset($meal['id']);
            $this->db->update('diet_records', $meal);
            return $this->db->affected_rows() > 0 ? true : false;
        } catch (Exception $e) {
            log_message('error', 'Error occurred while updating diet record: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 取得特定會員的 TDEE（每日總能量消耗）
     * 
     * @param int $memberId - 會員 ID
     * @return string - TDEE
     */
    public function getTdee($memberId)
    {
        try {
            return $this->db->get_where('profile', array('membersId' => $memberId))->row_array()['tdee'];
        } catch (Exception $e) {
            log_message('error', 'Error occurred while fetching TDEE: ' . $e->getMessage());
            return '';
        }
    }
}
