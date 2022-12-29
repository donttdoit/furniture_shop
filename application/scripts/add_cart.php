<?php 
session_start();

foreach ($_POST as $key => $val){
    echo $key.' => '.$val.'<br>';
}
echo '<br>';
$cart_arr = 'daad';
$_SESSION['cart'] = $cart_arr;
foreach ($_SESSION['cart'] as $key => $val){
    echo $key.' => '.$val.'<br>';
}

?>
