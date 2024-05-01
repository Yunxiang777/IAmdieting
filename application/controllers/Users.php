<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Users 使用者與會員前台操作
 * @property Users_model $Users_model
 * 
 */
class Users extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 會員登入頁
	 *
	 */
	public function login()
	{
		$data['rememberMeEmail'] = $this->input->cookie('rememberMeEmail');
		$this->load->view('layouts/layout', [
			'title' => 'Login Page',
			// NULL 沒有要傳遞給視圖的資料。
			// TRUE 返回視圖的內容，而不是直接輸出到瀏覽器。
			'content' => $this->load->view('login_view', $data, TRUE),
			'page' => 'login'
		]);
	}

	/**
	 * 會員登出
	 *
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}

	/**
	 * 會員註冊頁
	 *
	 */
	public function register()
	{
		$this->load->view('layouts/layout', [
			'title' => 'Login Page',
			'content' => $this->load->view('register_view', NULL, TRUE)
		]);
	}

	/**
	 * 註冊會員，ajax資料寫入
	 *
	 * @return void - 直接輸出 JSON 回應
	 */
	//void 是一種表示某個函式或方法沒有返回值的型別
	public function signUp()
	{
		$this->load->model('Users_model');
		// CI3 框架提供的一個方法，用來取得 HTTP 請求的原始內容
		$jsonData = json_decode($this->input->raw_input_stream, true);
		$user_data = array(
			'email' => $jsonData['email'],
			// 使用 bcrypt 加密密碼
			'password' => password_hash($jsonData['password'], PASSWORD_BCRYPT),
		);

		echo json_encode(array('result' => $this->Users_model->signUp($user_data)));
	}

	/**
	 * 登入會員驗證
	 *
	 * @return void - 直接輸出 JSON 回應( $result 為 bool )
	 */
	public function auth()
	{
		$this->load->model('Users_model');
		$jsonData = json_decode($this->input->raw_input_stream, true);
		$memberData = $this->Users_model->auth($jsonData['email']);
		$result = $memberData ? password_verify($jsonData['password'], $memberData['password']) : false;
		if ($result) {
			$this->load->model('Member_model');
			$profileData = $this->Member_model->getProfileData($memberData['id']);
			$this->session->set_userdata(array(
				'loggedIn' => true,
				'memberId' => $memberData['id'],
				'memberEmail' => $memberData['email'],
				'memberImg' => $profileData['img'] ? unserialize($profileData['img'])['link'] : ''
			));
			$this->setRememberMeCookie($memberData['email'], $jsonData['checkMeOut']);
		}

		echo json_encode(array('result' => $result));
	}

	/**
	 * 設置「記住我」Cookie
	 *
	 * @param string $email - 要保存的郵件地址
	 * @param boolean $checkMeOut - 記住我
	 * @return void - 設置Cookie
	 */
	private function setRememberMeCookie($email, $checkMeOut)
	{
		if ($checkMeOut) {
			$cookie_data = array(
				'name'   => 'rememberMeEmail',
				'value'  => $email,
				'expire' => '604800', // Cookie 的有效期一周
			);
			set_cookie($cookie_data);
		} else {
			delete_cookie('rememberMeEmail');
		}
	}
}
