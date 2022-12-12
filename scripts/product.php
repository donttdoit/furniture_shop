<?php
session_start();
$link = mysqli_connect("localhost", "root", "1234", "furniture_shop");

// foreach ($_POST as $key => $val){
//     echo $key.' => '.$val.'<br>';
// }

// Составление условий для where
$min_price = 0;
$max_price = 0;
if ($_POST['price_from'] and $_POST['price_to'] and $_POST['price_from'] > 0 and $_POST['price_from'] < $_POST['price_to']){
    $min_price = $_POST['price_from'];
    $max_price = $_POST['price_to'];
}else{
    $min_price = 0;
    $max_price = mysqli_fetch_array(mysqli_query($link, 'select max(price) as max_price from product'))['max_price'];
}

$product_type_condition = '';
if ($_POST['product_type'])
    $product_type_condition = ' and name_product_type = '.'"'.$_POST['product_type'].'"';

$material_condition = '';
if ($_POST['material'])
    $material_condition = ' and material = '.'"'.$_POST['material'].'"';

$storehouse_condition = '';
if ($_POST['storehouse'])
    $storehouse_condition = ' and address_storehouse = '.'"'.$_POST['storehouse'].'"';

$order_by = 'id_product';
if ($_POST['product_orders'])
    $order_by = $_POST['product_orders'];

$desc = '';
if ($_POST['product_orders_desc'])
    $desc = ' desc';
// Запрос к бд
$sql =
    'select id_product, price, name_product, size, material, product_type.name_product_type, storehouse.address_storehouse
    from product join product_type on product.id_product_type = product_type.id_product_type
                 join storehouse on product.id_storehouse = storehouse.id_storehouse
    where price between '.$min_price.' and '.$max_price.$product_type_condition.$material_condition.$storehouse_condition.
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
            
        $response .= '</tbody></table>';



$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$_SESSION['response'] = $response;
$_SESSION['Post'] = $_POST;
header("Location: $redirect");
exit();

?>