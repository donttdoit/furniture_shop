<?php
session_start();
$link = mysqli_connect("localhost", "root", "1234", "furniture_shop");

// foreach ($_POST as $key => $val){
//     echo $key.' => '.$val.'<br>';
// }

// Составление условий для where
$conditions = 'where True ';
if ($_POST['time_delivery_from'] and $_POST['time_delivery_to'] and $_POST['time_delivery_from'] > 0 and $_POST['time_delivery_from'] < $_POST['time_delivery_to']){
    $conditions .= ' and delivery_date between'.'"'.$_POST['time_delivery_from'].'"'.' and '.'"'.$_POST['time_delivery_to'].'"';
}else{
    $min_delivery_date = '0';
    $max_delivery_date = mysqli_fetch_array(mysqli_query($link, 'select max(delivery_date) as max_delivery_date from delivery'))['max_delivery_date'];
    $conditions .= ' and delivery_date between "'.$min_delivery_date. '" and "'.$max_delivery_date.'"';
}

if ($_POST['id_employee']){
    $conditions .= ' and employee.id_employee='.'"'.$_POST['id_employee'].'"';
}

$order_by = 'id_delivery';
if ($_POST['delivery_orders'])
    $order_by = $_POST['delivery_orders'];

$desc = '';
if ($_POST['delivery_orders_desc'])
    $desc = ' desc';

// Запрос к бд
$sql =
    'select id_delivery, delivery_date, delivery_address, name_employee, tel_employee, employee.id_employee
    from delivery join employee on delivery.id_employee = employee.id_employee '.
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