<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Users_model 使用者與會員資料操作
 * 
 */
class Users_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // 載入資料庫類別
    }

    /**
     * 會員註冊
     *
     * @param array $data - 會員註冊mail與密碼
     * @return bool - 寫入資料庫
     */
    public function signUp($data)
    {
        try {
            $this->db->insert('members', $data);
            return $this->db->affected_rows() > 0 ? true : false;
        } catch (Exception $e) {
            log_message('error', 'Error inserting user data: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 會員登入
     *
     * @param string $data - 會員登入的mail
     * @return array - 如果用戶存在，返回密碼和會員ID；否則返回空数组
     */
    public function auth($email)
    {
        try {
            $this->db->select('*');
            $this->db->where('email', $email);
            $query = $this->db->get('members');
            if ($query->num_rows() > 0) {
                $row = $query->row();
                return array(
                    'id' => $row->id,
                    'email' => $row->email,
                    'password' => $row->password
                );
            } else {
                return array();
            }
        } catch (Exception $e) {
            log_message('error', 'Database error: ' . $e->getMessage());
            return false;
        }
    }
}
