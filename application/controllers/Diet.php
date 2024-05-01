<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Diet 會員飲食資料
 * 
 * @property Diet_model $Diet_model
 */
class Diet extends CI_Controller
{
    private $memberId;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('loggedIn')) {
            redirect('home');
            exit();
        }
        $this->memberId = $this->session->userdata('memberId');
        $this->load->model('Diet_model');
    }

    /**
     * 飲食紀錄主頁面
     *
     */
    public function main()
    {
        $content = array(
            'dietRecord' => $this->getDietRecord($this->memberId),
            'tdee' =>  $this->getTdee($this->memberId)
        );
        $this->load->view('layouts/layout', [
            'title' => 'Diet',
            'content' => $this->load->view('diet/diet_view', $content, TRUE),
            'page' => 'diet'
        ]);
    }

    /**
     * 紀錄飲食
     */
    public function recordMeal()
    {
        $this->load->model('Diet_model');
        $jsonData = json_decode($this->input->raw_input_stream, true);
        $dietData = array(
            'memberId' => $this->memberId,
            'food' => $jsonData['food'],
            'calories' => $jsonData['calories'],
            'date' => date("Y-m-d")
        );
        echo json_encode(array('result' => $this->Diet_model->addDietRecord($dietData) ?? '', 'date' => date("Y-m-d")));
    }

    /**
     * 刪除飲食紀錄
     *
     * @param int $recordId 飲食紀錄ID
     */
    public function deleteMeal($recordId)
    {
        $this->setJsonencode($this->Diet_model->deleteMeal($recordId));
    }

    /**
     * 更新飲食紀錄
     */
    public function updateMeal()
    {
        $this->setJsonEncode($this->Diet_model->updateMeal(json_decode($this->input->raw_input_stream, true)));
    }

    /**
     * 獲取飲食紀錄
     *
     * @param int $memberId 會員ID
     * @return array 飲食紀錄數據
     */
    private function getDietRecord($memberId)
    {
        return $this->Diet_model->getDietRecords($memberId);
    }

    /**
     * 獲取TDEE
     *
     * @param int $memberId 會員ID
     * @return int TDEE值
     */
    private function getTdee($memberId)
    {
        return $this->Diet_model->getTdee($memberId);
    }

    /**
     * 設置 JSON 格式數據
     *
     * @param mixed $data 數據
     */
    private function setJsonEncode($data)
    {
        echo json_encode(array('result' => $data));
    }
}
