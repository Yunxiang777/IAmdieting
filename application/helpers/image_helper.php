<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 圖片處裡helper
 * @property Member_model $Member_model
 * 
 */
class Image_helper
{

    protected $CI;
    private $imgurClientId;
    private $imgurApi;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('Member_model');
        $this->imgurClientId = $this->CI->config->item('imgurClientId');
        $this->imgurApi = $this->CI->config->item('imgurApi');
    }

    /**
     * 刪除圖片
     * @param string $deletehash 圖片刪除哈希
     * @return bool 刪除是否成功
     */
    public function deleteImg($deletehash)
    {
        $headers = array(
            'Authorization: Client-ID ' . $this->imgurClientId,
        );
        $curl = curl_init($this->imgurApi . $deletehash);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => $headers,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return !curl_errno($curl) && json_decode($response)->success;
    }

    /**
     * 上傳圖片到 Imgur
     * @param string $img 圖片的 base64 字符串
     * @return string 上傳成功返回圖片信息的序列化字符串，否則返回空字串
     */
    public function uploadImg($img)
    {
        $headers = array(
            'Authorization: Client-ID ' . $this->imgurClientId,
        );
        $curl = curl_init($this->imgurApi);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => array('image' => $img),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        if (!curl_errno($curl) && json_decode($response)->success) {
            $resData = json_decode($response, true)['data'];
            return serialize([
                'deletehash' => $resData['deletehash'],
                'link' => $resData['link']
            ]);
        }
        return '';
    }
}
