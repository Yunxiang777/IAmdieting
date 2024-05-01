<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 獲取食物api資料
 * 
 */
class GetFoodData extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main($api, $foodName = '')
    {
        if ($api === 'getFood') {
            $result = $this->getFoodData($foodName);
        } else if ($api === 'getMeal') {
            $result = $this->getMealData();
        }

        echo json_encode($result);
    }

    /**
     * 查詢食物數據
     *
     * @return array - JSON 回應
     */
    private function getFoodData($foodName)
    {
        $apiUrl = $this->config->item('foodApi') . "?app_id=" . $this->config->item('foodDataBaseAppId')
            . "&app_key=" . $this->config->item('foodDataBaseAppKey')
            . "&ingr=" . $foodName . "&nutrition-type=cooking";
        try {
            $data = json_decode(file_get_contents($apiUrl), true);
            $data = isset($data['hints']['0']['food']['nutrients']) ? $data['hints']['0']['food']['nutrients'] : array();
        } catch (Exception $e) {
            log_message('error',  $e->getMessage());
            $data = array();
        }
        return array('result' => $data);
    }

    /**
     * 查詢菜單數據
     *
     * @return array - JSON 回應
     */
    private function getMealData()
    {
        $jsonData = json_decode($this->input->raw_input_stream, true);
        $results = array();
        foreach ($jsonData['meals'] as $meal) {
            $apiUrl = sprintf(
                "%s?app_id=%s&app_key=%s&ingr=%s&nutrition-type=cooking",
                $this->config->item('mealApi'),
                $this->config->item('mealDataBaseAppId'),
                $this->config->item('mealDataBaseAppKey'),
                str_replace(" ", "%20", urlencode($meal))
            );
            $curl = curl_init($apiUrl);
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array('Accept: application/json'),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $meal = json_decode($response, true);
            if (curl_errno($curl) || empty($meal['dietLabels'])) {
                return array('result' => array());
            }
            $results[] = array_map(function ($item) {
                return $item['quantity'];
            }, array_slice($meal['totalNutrients'], 0, 5, true));
        }
        $summedResults = array_reduce($results, function ($carry, $meal) {
            foreach ($meal as $key => $value) {
                $carry[$key] = ($carry[$key] ?? 0) + $value;
            }
            return $carry;
        }, array());
        return array('result' => $summedResults);
    }
}
