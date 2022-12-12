<?php
session_start();
$link = mysqli_connect("localhost", "root", "1234", "furniture_shop");

// foreach ($_POST as $key => $val){
//     echo $key.' => '.$val.'<br>';
// }

// Составление условий для where
$conditions = 'where True ';
if ($_POST['id_orders']){
    $conditions .= ' and order_list.id_order='.'"'.$_POST['id_orders'].'"';
}

if ($_POST['product_name']){
    $conditions .= ' and name_product='.'"'.$_POST['product_name'].'"';
}

if ($_POST['price_from'] and $_POST['price_to'] and $_POST['price_from'] > 0 and $_POST['price_from'] < $_POST['price_to']){
    $conditions .= ' and price between'.'"'.$_POST['price_from'].'"'.' and '.'"'.$_POST['price_to'].'"';
}else{
    $min_price = 0;
    $max_price = mysqli_fetch_array(mysqli_query($link, 'select max(price) as max_price from product'))['max_price'];
    $conditions .= ' and price between '.$min_price. ' and '.$max_price;
}

if ($_POST['id_delivery']){
    $conditions .= ' and orders.id_delivery='.'"'.$_POST['id_delivery'].'"';
}

if ($_POST['order_status']){
    $conditions .= ' and name_status='.'"'.$_POST['order_status'].'"';
}


$order_by = 'order_list.id_order';
if ($_POST['orders_orders'])
    $order_by = $_POST['orders_orders'];

$desc = '';
if ($_POST['orders_orders_desc'])
    $desc = ' desc';

// Запрос к бд
$sql =
    'select order_list.id_order, name_product, price, order_date, id_delivery, name_status 
    from order_list join product on order_list.id_product = product.id_product 
    join orders on order_list.id_order = orders.id_order 
    join status on orders.id_status = status.id_status '.
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