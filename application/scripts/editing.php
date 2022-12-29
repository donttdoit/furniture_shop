<?php
session_start();
$link = mysqli_connect("localhost", "root", "1234", "furniture_shop");

// foreach ($_POST as $key => $val) {
//     echo $key . ' => ' . $val . '<br>';
// }

$name_del_id = '';
$del_id = $_POST['del_id'] ? $_POST['del_id'] : -1;

$table_car = $_POST['insert_car'] ? 'car' : '';
$table_del_car = $_POST['delete_car'] ? 'car' : '';

$table_client = $_POST['insert_client'] ? 'client' : '';
$table_del_client = $_POST['delete_client'] ? 'client' : '';

$table_delivery = $_POST['insert_delivery'] ? 'delivery' : '';
$table_del_delivery = $_POST['delete_delivery'] ? 'delivery' : '';

$table_employee = $_POST['insert_employee'] ? 'employee' : '';
$table_del_employee = $_POST['delete_employee'] ? 'employee' : '';

$table_order_list = $_POST['insert_order_list'] ? 'order_list' : '';
$table_del_order_list = $_POST['delete_order_list'] ? 'order_list' : '';

$table_orders = $_POST['insert_orders'] ? 'orders' : '';
$table_del_orders = $_POST['delete_orders'] ? 'orders' : '';

$table_product = $_POST['insert_product'] ? 'product' : '';
$table_del_product = $_POST['delete_product'] ? 'product' : '';

$table_product_type = $_POST['insert_product_type'] ? 'product_type' : '';
$table_del_product_type = $_POST['delete_product_type'] ? 'product_type' : '';

$table_status = $_POST['insert_status'] ? 'status' : '';
$table_del_status = $_POST['delete_status'] ? 'status' : '';

$table_storehouse = $_POST['insert_storehouse'] ? 'storehouse' : '';
$table_del_storehouse = $_POST['delete_storehouse'] ? 'storehouse' : '';


if ($table_car) {
    $table = $table_car;
} else if ($table_client) {
    $table = $table_client;
} else if ($table_delivery) {
    $table = $table_delivery;
} else if ($table_employee) {
    $table = $table_employee;
} else if ($table_order_list) {
    $table = $table_order_list;
} else if ($table_orders) {
    $table = $table_orders;
} else if ($table_product) {
    $table = $table_product;
} else if ($table_product_type) {
    $table = $table_product_type;
} else if ($table_status) {
    $table = $table_status;
} else if ($table_storehouse) {
    $table = $table_storehouse;
} else {
    $table = '';
}



if ($table_del_car) {
    $table = $table_del_car;
    $name_del_id = 'id_car';
} else if ($table_del_client) {
    $table = $table_del_client;
    $name_del_id = 'id_client';
} else if ($table_del_delivery) {
    $table = $table_del_delivery;
    $name_del_id = 'id_delivery';
} else if ($table_del_employee) {
    $table = $table_del_employee;
    $name_del_id = 'id_employee';
} else if ($table_del_order_list) {
    $table = $table_del_order_list;
    $name_del_id = 'id_order';
} else if ($table_del_orders) {
    $table = $table_del_orders;
    $name_del_id = 'id_order';
} else if ($table_del_product) {
    $table = $table_del_product;
    $name_del_id = 'id_product';
} else if ($table_del_product_type) {
    $table = $table_del_product_type;
    $name_del_id = 'id_product_type';
} else if ($table_del_status) {
    $table = $table_del_status;
    $name_del_id = 'id_status';
} else if ($table_del_storehouse) {
    $table = $table_del_storehouse;
    $name_del_id = 'id_storehouse';
}


if ($table) {
    // Запрос к бд
    $sql =
        'select * from ' . $table;
    $result = mysqli_query($link, $sql);

    $finfo = $result->fetch_fields();

    $len = count($_POST) - 2;
    $all_params = true;

    $values = ' (';
    $counter = 0;
    foreach ($finfo as $val) {
        if ($counter > 0 && $table != 'order_list') {
            $values .= $val->name;
            if ($counter != $len) {
                $values .= ',';
            }
        }
        $counter++;
    }
    $values .= ') values';
    $values_post = ' (';
    $counter = 0;
    foreach ($_POST as $val) {

        if ($counter < $len) {
            if (!$val) {
                $all_params = false;
                break;
            }

            $values_post .= '"' . $val . '"';
            if ($counter < $len - 1) {
                $values_post .= ',';
            }
        }

        $counter++;
    }
    $values_post .= ')';

    if ($all_params && $del_id == -1) {
        $sql = 'insert into ' . $table . $values . $values_post . ';';

        // $result = mysqli_query($link, 'select * from '.$table);
        try {
            $result = mysqli_query($link, $sql);
        } catch (Exception $e) {
            $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            $_SESSION['response'] = '<h2>Невозможно добавить данные</h2>';
            $_SESSION['Post'] = $_POST;
            header("Location: $redirect");
            exit();
        }
    } else if ($del_id != -1) {
        $sql = 'delete from ' . $table . ' where ' . $name_del_id . ' = ' . $del_id . ';';
        try {
            $result = mysqli_query($link, $sql);
        } catch (Exception $e) {
            $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            $_SESSION['response'] = '<h2>Невозможно удалить данные</h2>';
            $_SESSION['Post'] = $_POST;
            header("Location: $redirect");
            exit();
        }
    }


    // Составление ответа
    $sql =
        'select * from ' . $table;
    $result = mysqli_query($link, $sql);
    $response = '<table cellspacing="1">
<thead>
    <tr>
        <th colspan="' . count($finfo) . '">' . $finfo[0]->table . '</th>
    </tr>
</thead>
<tbody>
    <tr>';
    foreach ($finfo as $val) {
        $response .= '<td>' . $val->name . '</td>';
    }

    $response .= '</tr>';

    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
        $response .= '<tr>';
        for ($i = 0; $i < count($row); $i++) {
            $response .= '<td>' . $row[$i] . '</td>';
        }
        $response .= '</tr>';
    }
}




$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$_SESSION['response'] = $response;
$_SESSION['Post'] = $_POST;
header("Location: $redirect");
exit();