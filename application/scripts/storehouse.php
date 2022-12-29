<?php
session_start();
$link = mysqli_connect("localhost", "root", "1234", "furniture_shop");

// foreach ($_POST as $key => $val){
//     echo $key.' => '.$val.'<br>';
// }

// Составление условий для where
$conditions = 'where True ';
if ($_POST['employee_count_from'] and $_POST['employee_count_to'] and $_POST['employee_count_from'] > 0 and $_POST['employee_count_from'] < $_POST['employee_count_to']){
    $conditions .= ' and employee_count between'.'"'.$_POST['employee_count_from'].'"'.' and '.'"'.$_POST['employee_count_to'].'"';
}else{
    $min_employee_count = 0;
    $max_employee_count = mysqli_fetch_array(mysqli_query($link, 'select max(employee_count) as max_employee_count from storehouse'))['max_employee_count'];
    $conditions .= ' and employee_count between '.$min_employee_count. ' and '.$max_employee_count;
}

$order_by = 'id_storehouse';
if ($_POST['storehouse_orders'])
    $order_by = $_POST['storehouse_orders'];

$desc = '';
if ($_POST['storehouse_orders_desc'])
    $desc = ' desc';

// Запрос к бд
$sql =
    'select id_storehouse, address_storehouse, employee_count  
    from storehouse '.
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