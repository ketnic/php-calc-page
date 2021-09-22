<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

function get_sum()
{
    $params = $_POST;

    $startDate = array_key_exists("startDate", $params) ? new DateTime($params["startDate"]) : null;
    $sum = array_key_exists("sum", $params) ? $params["sum"] : 0;
    $term = array_key_exists("term", $params) ? $params["term"] : 0;
    $percent = array_key_exists("percent", $params) ? $params["percent"] : 0;
    $sumAdd = array_key_exists("sumAdd", $params) ? $params["sumAdd"] : 0;

    if (validate_data($startDate, $sum, $term, $percent, $sumAdd)) {
        return sum_n($startDate, $sum, $term, $percent, $sumAdd);
    } else {
        http_response_code(400); 
        return [
            "error" => "ValidationError",
            "message" => "Error message"
        ];
    }
}

function validate_data($startDate, $sum, $term, $percent, $sumAdd)
{
    if (!isset($startDate)) {
        return false;
    }
    // Сумма вклада - число от 1000 до 3000000
    if (!($sum>=1000 && $sum <= 3000000)) {
        return false;
    }
    // Срок вклада - число от 1 до 60 месяцев (или 1 до 5 лет)
    if (!($term>=1 && $term <= 60)) {
        return false;
    }
    // Процентная ставка, % годовых - целое число от 3 до 100
    if (!($percent>=3 && $percent <= 100)) {
        return false;
    }
    //Сумма пополнения вклада - число от 0 до 3000000
    if (!($sumAdd>=0 && $sumAdd <= 3000000)) {
        return false;
    }
    return true;
}

function sum_n($start_date, $sum, $term, $percent, $sumAdd)
{
    $sum_n = $sum;
    for ($i = 0; $i < $term; $i++){
        $days_n = $start_date->format('t');
        $days_y = $start_date->format("L") ? 366 : 365;

        $sum_n = $sum_n + $sumAdd + ($sum_n + $sumAdd) * $days_n * ($percent /100 / $days_y);

        $date = new DateTime();
        $start_date = $start_date->format("m") == 12 ? $date->setDate($start_date->format('Y') + 1, 1, 1) : $date->setDate($start_date->format('Y'), $start_date->format('m') + 1, 1);
    }
    return $sum_n;
}

echo json_encode(get_sum()); 
