<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Member_model 會員個人頁
 * 
 */
class Member_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 更新會員資料
     *
     * @param int $memberId - 會員ID
     * @param array $profileData - 要更新的個人檔案資料
     * @return bool - 是否成功更新
     */
    public function updateProfileData($memberId, $profileData)
    {
        try {
            $this->db->trans_start();
            $this->db->where('id', $memberId);
            $this->db->update('members', ['email' => $profileData['email']]);
            unset($profileData['email']);
            $this->db->where('membersId', $memberId);
            $this->db->update('profile', $profileData);
            $this->db->trans_complete();
            return $this->db->trans_status();
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error updating member data: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 提取會員資料
     *
     * @param int $memberId - 會員ID
     * @return array - 成功則返回資料，無則為空數組
     */
    public function getProfileData($memberId)
    {
        try {
            $this->db->select('*');
            $this->db->where('membersId', $memberId);
            $query = $this->db->get('profile');
            return $query->num_rows() > 0 ? $query->row_array() : array();
        } catch (Exception $e) {
            log_message('error', 'Error get profile data: ' . $e->getMessage());
            return false;
        }
    }
}
