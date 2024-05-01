<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Article 文章資訊頁面
 */
class Article extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 主頁面
     *
     */
    public function main($pageInit = true)
    {
        $this->load->view('layouts/layout', [
            'title' => 'Article',
            'content' => $this->load->view('article_view', ['articles' => $this->getWaterfallData($pageInit)], TRUE),
            'page' => 'article'
        ]);
    }

    /**
     * 瀑布流獲取文章資訊
     *
     * @param bool $pageInit 首次進入主頁面
     */
    public function getWaterfallData($pageInit = false)
    {
        if ($pageInit) {
            $date = date('Y-m-d');
            $this->session->set_userdata('articleDate', $date);
            return $this->getNews($date);
        }
        $date = date('Y-m-d', strtotime($this->session->userdata('articleDate') . ' -1 day'));
        $this->session->set_userdata('articleDate', $date);
        echo json_encode($this->getNews($date));
    }

    /**
     * 獲取文章資訊
     *
     * @param string $date 指定的日期
     * @return array 包含文章資訊的數組
     */
    private function getNews($date)
    {
        $queryParams = [
            'q' => 'food',
            'from' => $date . 'T00:00:00Z',
            'to' => $date . 'T23:59:59Z',
            'sortBy' => 'popularity',
            'apikey' => $this->config->item('newsapiAppKey'),
            'max' => 30 // 取前30則
        ];
        $curl = curl_init($this->config->item('newsapi') . '?' . http_build_query($queryParams));
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_USERAGENT => 'imdieting',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response ? json_decode($response, true)['articles'] : array();
    }
}
