<?php
session_start();
$link = mysqli_connect("localhost", "root", "1234", "furniture_shop");

// foreach ($_POST as $key => $val){
//     echo $key.' => '.$val.'<br>';
// }

// Составление условий для where
$conditions = 'where True ';

if ($_POST['name']){
    $conditions .= ' and name_client='.'"'.$_POST['name'].'"';
}

if ($_POST['tel']){
    $conditions .= ' and tel_client='.'"'.$_POST['tel'].'"';
}

if ($_POST['email']){
    $conditions .= ' and email_client='.'"'.$_POST['email'].'"';
}

$order_by = 'id_client';
if ($_POST['client_orders'])
    $order_by = $_POST['client_orders'];

$desc = '';
if ($_POST['client_orders_desc'])
    $desc = ' desc';

// Запрос к бд
$sql =
    'select id_client, name_client, tel_client, email_client
    from client '.
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