<?php

if (!function_exists('ymdToDmy')) {
    function ymdToDmy($ymd) {
        if(empty($ymd)||substr($ymd,0,4)==='0000')
            return '';

        return date('d/m/Y',strtotime($ymd));
    }
}

if (!function_exists('dmyToYmd')) {
    function dmyToYmd($dmy,$separator='/') {
        // $path = FCPATH.$path;
        if($dmy) {
            $dt = DateTime::createFromFormat("d".$separator."m".$separator."Y", $dmy);
            return $dt->format('Y-m-d');
        }
        return $dmy;
    }
}

if (!function_exists('dobToAge')) {
    function dobToAge($dob) {
        if($dob) {
            $from = new DateTime($dob);
        } else {
            $from = new DateTime('today');
        }
        $to   = new DateTime('today');
        return $from->diff($to)->y;
    }
}

if (!function_exists('my_date_range')) {
    function my_date_range($start_date,$end_date = null) {
        if(!$end_date)
            $end_date = $start_date;
        $start_day = date('d',strtotime($start_date));
        $end_day = date('d',strtotime($end_date));
        $start_month = date('m',strtotime($start_date));
        $end_month = date('m',strtotime($end_date));
        $start_year = date('Y',strtotime($start_date));
        $end_year = date('Y',strtotime($end_date));
        $date = '';
        if($start_year==$end_year){
            if($start_month==$end_month){
                $date .= date("M", mktime(0, 0, 0, $start_month, $start_day));
                $date .= ' ';
                $date .= $start_day;
                $date .= ' - ';
                $date .= $end_day;
                $date .= ', '.$start_year;
            } else {
                $date .= date("M d", mktime(0, 0, 0, $start_month, $start_day));
                $date .= ' - ';
                $date .= date("M d", mktime(0, 0, 0, $end_month, $end_day));
                $date .= ', '.$start_year;
            }
        } else {
            $date = date('M d, Y',strtotime($start_date)).' - '.date('M d, Y',strtotime($end_date));
        }
        return $date;
    }
}

if (!function_exists('time_ago')) {
    function time_ago($date) {

        if(empty($date)) {
            return "No date provided";
        }

        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");
        $now = time();
        $unix_date = strtotime($date);

        // check validity of date

        if(empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "ago";
        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }

}

global $monthModel;

$monthModel = model('Admin\Common\Models\MonthModel');

if (!function_exists('getMonths')) {
    function getMonths(){
        global $monthModel;
        return $monthModel->getMonths();
    }
}

if (!function_exists('getMonthById')) {
    function getMonthById($month_id){
        global $monthModel;
        return $monthModel->getMonthById($month_id);
    }
}

if (!function_exists('getMonthIdByMonth')) {
    function getMonthIdByMonth($month_num){
        global $monthModel;
        return $monthModel->getMonthIdByMonth($month_num);
    }
}

if (!function_exists('getMonthsArray')) {
    function getMonthsUpto($upto_month){
        global $monthModel;
        return $monthModel->getMonthsUpto($upto_month);
    }
}

if (!function_exists('previousMonth')) {
    function previousMonth($month){
        global $monthModel;
        return $monthModel->getPreviousMonth($month);
    }
}

if (!function_exists('getPreviousMonths')) {
    /**
     * @param int $month
     * @return array $months
     */
    function getPreviousMonths($upto_month){
        global $monthModel;
        return $monthModel->getMonthsUpto($upto_month);
    }
}

global $yearModel;

$yearModel = new \Admin\Common\Models\YearModel();

function getAllYears($asArray = true){
    global  $yearModel;
    if($asArray)
        $years = $yearModel->asArray()->findAll();
    else
        $years = $yearModel->findAll();
    return $years;
}
//get fin year
function getYear($id){
    global  $yearModel;
    $year = $yearModel->find($id)->name;
    return $year;
}

function getAllMonths(){
    global  $monthModel;
    $months = $monthModel->asArray()->findAll();
    return $months;
}

/**
 * returns financial year
 * @return mixed
 */
function getCurrentYear(){
    return getYear(getCurrentYearId());
}

function getLastYear(){
    return getYear(getLastYearId());
}

function getCurrentYearId(){
    $years = getAllYears();

    $currentyr = date('Y').'-'.(date('y')+1);
    $lastyr = (date('Y')-1).'-'.(date('y'));
    $year = date('Y');
    if(strtotime('now')<strtotime($year.'-04-01')){
        $year = array_search($lastyr,array_column($years,'name'));
    } else{
        $year = array_search($currentyr,array_column($years,'name'));
    }

    return $years[$year]['id'];
}

function getCurrentMonthId(){
    return getMonthIdByMonth(date('m'));
}

function getLastYearId(){
    $current_year = getCurrentYearId();
    return $current_year-1;
}

function getYearIDByMonthYear($month,$year){
    $years = range(2017,date('Y',strtotime('next year')));
    $year_id = 1;
    foreach ($years as $_year) {
        $year1 = $_year;
        $year2 = $_year + 1;
        if (strpos($year, '-') === false) {
            if (($year == $year1 && ($month >= 4 && $month <= 12)) || ($year == $year2 && ($month >= 1 && $month <= 3))) {
                return $year_id;
            }
        } else {
            $parts = explode('-',$year);
            $year_1 = $parts[0];
            $year_2 = 2000+(int)$parts[1];
            if(($year_1==$year1 && ($month>=4&&$month<=12))||($year_2==$year2 && ($month>=1&&$month<=3))){
                return $year_id;
            }
        }


        $year_id++;

    }

}

function getYearByYearID($year_id){
    $year = getYear($year_id);
    return substr($year,0,4);
}

function getYearIDByYear($year){
    $y1 = substr($year,-2);
    $y2 = $y1+1;
    $years = getAllYears();
    $year = array_search($year.'-'.$y2,array_column($years,'name'));
    return $years[$year]['id'];
}

function getCurrentSeason() {
    $month = date('m');
    $kharif = [6,7,8,9,10];
    if(in_array($month,$kharif)){
        return 'kharif';
    } else {
        return 'rabi';
    }

}

function getSeasons(){
    return [
        [
            'id' => 'rabi',
            'name' => 'Rabi'
        ],
        [
            'id' => 'kharif',
            'name' => 'Kharif'
        ],
    ];
}