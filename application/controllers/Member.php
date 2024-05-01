<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Member 會員資料頁
 * @property Member_model $Member_model
 * 
 */
class Member extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	private $memberId;

	/**
	 * 會員頁面
	 *
	 * @param string $page - 瀏覽頁面
	 */
	public function main($page)
	{
		if (!$this->session->userdata('loggedIn')) {
			redirect('home');
			exit();
		}
		$this->memberId = $this->session->userdata('memberId');
		$data = $this->getPageData($page);
		$this->load->view('layouts/layout', [
			'title' => $page,
			'content' => $this->load->view('member/' . $page, $data, TRUE),
			'page' => $page
		]);
	}

	/**
	 * 根據頁面獲取數據
	 *
	 * @param string $page - 頁面名稱
	 * @return array - 頁面數據
	 */
	private function getPageData($page)
	{
		switch ($page) {
			case 'profileSetting':
				$data = $this->getProfileData();
				break;
			default:
				$data = array();
				break;
		}
		return $data;
	}

	/**
	 * 修改會員資料
	 *
	 * @return void - 直接輸出 JSON 回應( $result 為 bool )
	 */
	public function updateProfileData()
	{
		$this->load->model('Member_model');
		$profileData = $this->processImg(json_decode($this->input->raw_input_stream, true));
		$result = $this->Member_model->updateProfileData($this->session->userdata('memberId'), $profileData);
		if ($result) {
			$this->session->set_userdata('memberEmail', $profileData['email']);
			!empty($profileData['img']) ? $this->session->set_userdata('memberImg', unserialize($profileData['img'])['link']) : NULL;
		}
		echo json_encode(array('result' => $result));
	}

	/**
	 * 提取會員資料
	 *
	 * @return array - 會員email與個人資料
	 */
	private function getProfileData()
	{
		$this->load->model('Member_model');
		$profileData = $this->Member_model->getProfileData($this->memberId);
		if ($profileData['img']) {
			$profileData['img'] = unserialize($profileData['img'])['link'];
		}
		return $profileData ? ['profileData' => array_merge($profileData, ['email' => $this->session->userdata('memberEmail')])] : [];
	}

	/**
	 * 處理圖片數據
	 *
	 * @param array $profileData - 傳入的個人資料
	 * @return array - 處理後的個人資料
	 */
	private function processImg($profileData)
	{
		$this->load->helper('image_helper');
		$imageHelper = new Image_helper();
		switch ($profileData['img']['type']) {
			case 'default':
				$profileData['img'] = '';
				break;
			case 'base64':
				$profileImg = $this->Member_model->getProfileData($this->session->userdata('memberId'));
				if ($profileImg['img']) {
					if ($imageHelper->deleteImg(unserialize($profileImg['img'])['deletehash'])) {
						$updateResult = $imageHelper->uploadImg($profileData['img']['src']);
						$updateResult ? ($profileData['img'] = $updateResult) : null;
					}
				} else {
					$profileData['img'] = $imageHelper->uploadImg($profileData['img']['src']);
				}
				break;
			case 'imgur':
				unset($profileData['img']);
				break;
			default:
				break;
		}
		return $profileData;
	}
}
