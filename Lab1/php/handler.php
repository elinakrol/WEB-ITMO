<?php
function validate_number($val, $min, $max){
	return isset($val) && is_numeric($val) && ($val>=$min) && ($val<=$max);
}

function validate_timezone($timezone) {
	return isset($timezone);
}


// Проверка на попадание в область круга
function check_first_area($x, $y, $r){
	return ($x <= 0 && $x >= -$r / 2 && $y <= 0 && (pow($x, 2) + pow($y, 2) <= pow($r / 2, 2)));
}
// Проверка на попадание в область квадрата
function check_second_area($x, $y, $r){
	return ($x >= 0 && $x <= $r && $y >= 0 && $y <= $r);
}
// Проверка на попадание в треугольник
function check_third_area($x, $y, $r){
	return ($x <= 0 && $x >= -$r / 2 && $y >= 0 && $y <= $r);
}

session_start();
if (!isset($_SESSION['data']))
    $_SESSION['data'] = array();


$x = $_POST["x_coordinate"];
$y = $_POST["y_coordinate"];
$r = $_POST["r_coordinate"];

$timezone= $_POST["timezone"];
$rcor = sprintf("%01.3f", $r);
$ycor = sprintf("%01.3f", $y);
$xcor = sprintf("%01.3f", $x);


if(validate_number($x,-5,3) && validate_number($y,-3,5) && validate_number($r,2,5) && validate_timezone($timezone)){
    $is_inside = check_first_area($x, $y, $r) || check_second_area($x, $y, $r) || check_third_area($x, $y, $r);
	$hit = $is_inside ? "Hit": "Miss";
	$current_time = date("j M o G:i:s", time()-$timezone*60);
	$execution_time = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 7);
	
	$answer = array("x"=>$xcor, "y"=>$ycor, "r"=>$rcor, "hit"=>$hit, "current_time"=>$current_time, "execution_time"=>$execution_time);
	array_push($_SESSION['data'], $answer);
}

foreach ($_SESSION['data'] as $elem){
	echo "<tr class='columns'>";
	echo "<td>" . $elem['x']. "</td>";
	echo "<td>" . $elem['y'] . "</td>";
	echo "<td>" . $elem['r'] . "</td>";
	echo "<td>" . $elem['hit']  . "</td>";
	echo "<td>" . $elem['current_time']  . "</td>";
	echo "<td>" . $elem['execution_time'] . "</td>";
	echo "</tr>";
}

?> 