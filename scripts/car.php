<?php
session_start();
$link = mysqli_connect("localhost", "root", "1234", "furniture_shop");

// foreach ($_POST as $key => $val){
//     echo $key.' => '.$val.'<br>';
// }

// Составление условий для where
$conditions = 'where True ';
if ($_POST['capacity_from'] and $_POST['capacity_to'] and $_POST['capacity_from'] > 0 and $_POST['capacity_from'] < $_POST['capacity_to']){
    $conditions .= ' and capacity between'.'"'.$_POST['capacity_from'].'"'.' and '.'"'.$_POST['capacity_to'].'"';
}else{
    $min_price = 0;
    $max_price = mysqli_fetch_array(mysqli_query($link, 'select max(capacity) as max_capacity from car'))['max_capacity'];
    $conditions .= ' and capacity between '.$min_price. ' and '.$max_price;
}

$order_by = 'id_car';
if ($_POST['car_orders'])
    $order_by = $_POST['car_orders'];

$desc = '';
if ($_POST['product_orders_desc'])
    $desc = ' desc';

// Запрос к бд
$sql =
    'select id_car, capacity, name_employee
    from car join employee on car.id_employee = employee.id_employee '.
    $conditions.
    ' order by '.$order_by.$desc.';';
$result = mysqli_query($link, $sql);

// Составление ответа
$finfo = $result->fetch_fields();
$response = '<table cellspacing="1">
    <thead>
        <tr>
            <th colspan="'.count($finfo).'">' . $finfo[0]->table . '</th>
        </tr>
    </thead>
    <tbody>
        <tr>';
            foreach ($finfo as $val) {
                $response .= '<td>' . $val->name . '</td>';
            }
            
        $response .= '</tr>';

        while($row = mysqli_fetch_array($result, MYSQLI_NUM))
        {
            $response .= '<tr>';
            for($i = 0; $i < count($row); $i++){
                 $response .= '<td>'.$row[$i].'</td>';
            }
            $response .= '</tr>';
        }


$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$_SESSION['response'] = $response;
$_SESSION['Post'] = $_POST;
header("Location: $redirect");
exit();

?>