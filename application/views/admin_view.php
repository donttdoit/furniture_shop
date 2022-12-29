<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>База данных - Магазин мебели</title>
    <link rel="stylesheet" href="application/styles/style.css">
</head>

<body>
    <?php
    session_start();
    $link = mysqli_connect("localhost", "root", "1234", "furniture_shop");
    if (!empty($_SESSION['Post']))
        $_POST = $_SESSION['Post'];

    $checked_employee = '';
    $checked_car = '';
    $checked_client = '';
    $checked_delivery = '';
    $checked_orders = '';
    $checked_storehouse = '';


    if (!empty($_POST['filter_employee']))
        $checked_employee = ' checked';

    if (!empty($_POST['filter_car']))
        $checked_car = ' checked';

    if (!empty($_POST['filter_client']))
        $checked_client = ' checked';

    if (!empty($_POST['filter_delivery']))
        $checked_delivery = ' checked';

    if (!empty($_POST['filter_orders']))
        $checked_orders = ' checked';

    if (!empty($_POST['filter_storehouse']))
        $checked_storehouse = ' checked';
    ?>

    <p>Фильтры: </p>
    <input type="radio" name="filters" checked value="radio_filter_product"><label>Продукты</label>
    <input type="radio" name="filters" <?php echo $checked_employee ?> value="radio_filter_employee"><label>Сотрудники</label>
    <input type="radio" name="filters" <?php echo $checked_car ?> value="radio_filter_car"><label>Машины</label>
    <input type="radio" name="filters" <?php echo $checked_client ?> value="radio_filter_client"><label>Клиенты</label>
    <input type="radio" name="filters" <?php echo $checked_delivery ?> value="radio_filter_delivery"><label>Доставки</label>
    <input type="radio" name="filters" <?php echo $checked_orders ?> value="radio_filter_orders"><label>Заказы</label>
    <input type="radio" name="filters" <?php echo $checked_storehouse ?> value="radio_filter_storehouse"><label>Склады</label>
    <br><br>

    <div class="product">
        <form action="application/scripts/product.php" method="post">
            <label>Цена: </label>
            <input type="text" name="price_from" placeholder="От">
            <input type="text" name="price_to" placeholder="До">

            <label for="products">Тип продукта: </label>
            <select name="product_type" id="products">
                <option value="">--Выберите тип продукта--</option>
                <?php
                $sql = 'select distinct name_product_type from product_type';
                $result = mysqli_query($link, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    $selected = '';
                    if ($_POST['product_type'] == $row['name_product_type'])
                        $selected = 'selected';
                    echo '<option ' . $selected . ' value=' . '"' . $row['name_product_type'] . '">' . $row['name_product_type'] . '</option>';
                }
                ?>
            </select>

            <label for="materials">Материал: </label>
            <select name="material" id="materials">
                <option value="">--Выберите материал--</option>
                <?php
                $sql = 'select distinct material from product';
                $result = mysqli_query($link, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    $selected = '';
                    if ($_POST['material'] == $row['material'])
                        $selected = 'selected';
                    echo '<option ' . $selected . ' value=' . '"' . $row['material'] . '">' . $row['material'] . '</option>';
                }
                ?>
            </select>

            <label for="materials">Склад: </label>
            <select name="storehouse" id="storehouses">
                <option value="">--Выберите склад--</option>
                <?php
                $sql = 'select distinct address_storehouse from storehouse';
                $result = mysqli_query($link, $sql);

                while ($row = mysqli_fetch_array($result)) {
                    $selected = '';
                    if ($_POST['storehouse'] == $row['address_storehouse'])
                        $selected = 'selected';
                    echo '<option ' . $selected . ' value=' . '"' . $row['address_storehouse'] . '">' . $row['address_storehouse'] . '</option>';
                }
                ?>
            </select>

            <div class="order_by">
                <label for="product_orders">Сортировать по: </label>
                <select name="product_orders" id="product_orders">
                    <option value="">--Выберите поле--</option>
                    <?php
                    $sql = 'select id_product, price, name_product, size, material, product_type.name_product_type, storehouse.address_storehouse 
                            from product join product_type on product.id_product_type = product_type.id_product_type 
                            join storehouse on product.id_storehouse = storehouse.id_storehouse';
                    $result = mysqli_query($link, $sql);
                    $finfo = $result->fetch_fields();

                    foreach ($finfo as $val) {
                        $selected = '';
                        if ($_POST['product_orders'] == $val->name)
                            $selected = 'selected';
                        echo '<option ' . $selected . ' value=' . '"' . $val->name . '">' . $val->name . '</option>';
                    }
                    ?>
                </select>

                <?php
                $checked = '';
                if (!empty($_POST['product_orders_desc']))
                    $checked = ' checked';
                ?>
                <input type="checkbox" id="product_orders_desc" name="product_orders_desc" <?php echo $checked ?>>
                <label for="product_orders_desc">Обратный порядок</label>
            </div>



            <input type="submit" name="filter_product" value="Поиск">
        </form>
    </div>

    <div class="employee">
        <form action="application/scripts/employee.php" method="post">
            <label>Имя: </label>
            <input type="text" name="name">

            <label>Телефон: </label>
            <input type="text" name="tel">

            <label>Email: </label>
            <input type="text" name="email">

            <label for="post">Должность: </label>
            <select name="post" id="post">
                <option value="">--Выберите должность--</option>
                <?php
                $sql = 'select distinct post from employee';
                $result = mysqli_query($link, $sql);

                while ($row = mysqli_fetch_array($result)) {
                    $selected = '';
                    if ($_POST['post'] == $row['post'])
                        $selected = 'selected';
                    echo '<option ' . $selected . ' value=' . '"' . $row['post'] . '">' . $row['post'] . '</option>';
                }
                ?>
            </select>

            <div class="order_by">
                <label for="employee_orders">Сортировать по: </label>
                <select name="employee_orders" id="employee_orders">
                    <option value="">--Выберите поле--</option>
                    <?php
                    $sql = 'select id_employee, name_employee, email_employee, tel_employee, post
                    from employee';
                    $result = mysqli_query($link, $sql);
                    $finfo = $result->fetch_fields();

                    foreach ($finfo as $val) {
                        $selected = '';
                        if ($_POST['employee_orders'] == $val->name)
                            $selected = 'selected';
                        echo '<option ' . $selected . ' value=' . '"' . $val->name . '">' . $val->name . '</option>';
                    }
                    ?>
                </select>

                <?php
                $checked = '';
                if (!empty($_POST['employee_orders_desc']))
                    $checked = ' checked';
                ?>
                <input type="checkbox" id="employee_orders_desc" name="employee_orders_desc" <?php echo $checked ?>>
                <label for="employee_orders_desc">Обратный порядок</label>
            </div>

            <input type="submit" name="filter_employee" value="Поиск">
        </form>
    </div>

    <div class="car">
        <form action="application/scripts/car.php" method="post">
            <label>Вместимость: </label>
            <input type="text" name="capacity_from" placeholder="От">
            <input type="text" name="capacity_to" placeholder="До">

            <div class="order_by">
                <label for="car_orders">Сортировать по: </label>
                <select name="car_orders" id="car_orders">
                    <option value="">--Выберите поле--</option>
                    <?php
                    $sql = 'select id_car, capacity, name_employee
                    from car join employee on car.id_employee = employee.id_employee ';
                    $result = mysqli_query($link, $sql);
                    $finfo = $result->fetch_fields();

                    foreach ($finfo as $val) {
                        $selected = '';
                        if ($_POST['car_orders'] == $val->name)
                            $selected = 'selected';
                        echo '<option ' . $selected . ' value=' . '"' . $val->name . '">' . $val->name . '</option>';
                    }
                    ?>
                </select>

                <?php
                $checked = '';
                if (!empty($_POST['car_orders_desc']))
                    $checked = ' checked';
                ?>
                <input type="checkbox" id="car_orders_desc" name="car_orders_desc" <?php echo $checked ?>>
                <label for="car_orders_desc">Обратный порядок</label>
            </div>

            <input type="submit" name="filter_car" value="Поиск">
        </form>
    </div>

    <div class="client">
        <form action="application/scripts/client.php" method="post">
            <label>Имя: </label>
            <input type="text" name="name">

            <label>Телефон: </label>
            <input type="text" name="tel">

            <label>Email: </label>
            <input type="text" name="email">

            <div class="order_by">
                <label for="client_orders">Сортировать по: </label>
                <select name="client_orders" id="client_orders">
                    <option value="">--Выберите поле--</option>
                    <?php
                    $sql = 'select id_client, name_client, tel_client, email_client
                    from client ';
                    $result = mysqli_query($link, $sql);
                    $finfo = $result->fetch_fields();

                    foreach ($finfo as $val) {
                        $selected = '';
                        if ($_POST['client_orders'] == $val->name)
                            $selected = 'selected';
                        echo '<option ' . $selected . ' value=' . '"' . $val->name . '">' . $val->name . '</option>';
                    }
                    ?>
                </select>

                <?php
                $checked = '';
                if (!empty($_POST['client_orders_desc']))
                    $checked = ' checked';
                ?>
                <input type="checkbox" id="client_orders_desc" name="client_orders_desc" <?php echo $checked ?>>
                <label for="client_orders_desc">Обратный порядок</label>
            </div>

            <input type="submit" name="filter_client" value="Поиск">
        </form>
    </div>

    <div class="delivery">
        <form action="application/scripts/delivery.php" method="post">
            <label>Время дотсавки: </label>
            <input type="text" name="time_delivery_from" placeholder="От(2000-01-01)">
            <input type="text" name="time_delivery_to" placeholder="До(3000-01-01)">

            <label>Водитель: </label>
            <input type="text" name="id_employee" placeholder="id">

            <div class="order_by">
                <label for="delivery_orders">Сортировать по: </label>
                <select name="delivery_orders" id="delivery_orders">
                    <option value="">--Выберите поле--</option>
                    <?php
                    $sql = 'select id_delivery, delivery_date, delivery_address, name_employee, tel_employee, employee.id_employee 
                    from delivery join employee on delivery.id_employee = employee.id_employee ';
                    $result = mysqli_query($link, $sql);
                    $finfo = $result->fetch_fields();

                    foreach ($finfo as $val) {
                        $selected = '';
                        if ($_POST['delivery_orders'] == $val->name)
                            $selected = 'selected';
                        echo '<option ' . $selected . ' value=' . '"' . $val->name . '">' . $val->name . '</option>';
                    }
                    ?>
                </select>

                <?php
                $checked = '';
                if (!empty($_POST['delivery_orders_desc']))
                    $checked = ' checked';
                ?>
                <input type="checkbox" id="delivery_orders_desc" name="delivery_orders_desc" <?php echo $checked ?>>
                <label for="delivery_orders_desc">Обратный порядок</label>
            </div>

            <input type="submit" name="filter_delivery" value="Поиск">
        </form>
    </div>

    <div class="orders">
        <form action="application/scripts/orders.php" method="post">
            <label>Заказ: </label>
            <input type="text" name="id_orders" placeholder="id">
            <label>Наименование продукта: </label>
            <input type="text" name="product_name">
            <label>Цена: </label>
            <input type="text" name="price_from" placeholder="От">
            <input type="text" name="price_to" placeholder="До">
            <label>Доставка: </label>
            <input type="text" name="id_delivery" placeholder="id">

            <label for="order_status">Статус заказа: </label>
            <select name="order_status" id="order_status">
                <option value="">--Выберите поле--</option>
                <?php
                $sql = 'select name_status  
                    from status ';
                $result = mysqli_query($link, $sql);

                while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                    for ($i = 0; $i < count($row); $i++) {

                        $selected = '';
                        if ($_POST['order_status'] == $val->name)
                            $selected = 'selected';
                        echo '<option ' . $selected . ' value=' . '"' . $row[$i] . '">' . $row[$i] . '</option>';
                    }
                }
                ?>
            </select>

            <div class="order_by">
                <label for="orders_orders">Сортировать по: </label>
                <select name="orders_orders" id="orders_orders">
                    <option value="">--Выберите поле--</option>
                    <?php
                    $sql = 'select order_list.id_order, name_product, price, order_date, id_delivery, name_status 
                    from order_list join product on order_list.id_product = product.id_product 
                    join orders on order_list.id_order = orders.id_order 
                    join status on orders.id_status = status.id_status ';
                    $result = mysqli_query($link, $sql);
                    $finfo = $result->fetch_fields();

                    foreach ($finfo as $val) {
                        $selected = '';
                        if ($_POST['orders_orders'] == $val->name)
                            $selected = 'selected';
                        echo '<option ' . $selected . ' value=' . '"' . $val->name . '">' . $val->name . '</option>';
                    }
                    ?>
                </select>

                <?php
                $checked = '';
                if (!empty($_POST['orders_orders_desc']))
                    $checked = ' checked';
                ?>
                <input type="checkbox" id="orders_orders_desc" name="orders_orders_desc" <?php echo $checked ?>>
                <label for="orders_orders_desc">Обратный порядок</label>
            </div>

            <input type="submit" name="filter_orders" value="Поиск">
        </form>
    </div>

    <div class="storehouse">
        <form action="application/scripts/storehouse.php" method="post">
            <label>Количество сотрудников: </label>
            <input type="text" name="employee_count_from" placeholder="От">
            <input type="text" name="employee_count_to" placeholder="До">

            <div class="order_by">
                <label for="storehouse_orders">Сортировать по: </label>
                <select name="storehouse_orders" id="storehouse_orders">
                    <option value="">--Выберите поле--</option>
                    <?php
                    $sql = 'select id_storehouse, address_storehouse, employee_count 
                    from storehouse ';
                    $result = mysqli_query($link, $sql);
                    $finfo = $result->fetch_fields();

                    foreach ($finfo as $val) {
                        $selected = '';
                        if ($_POST['storehouse_orders'] == $val->name)
                            $selected = 'selected';
                        echo '<option ' . $selected . ' value=' . '"' . $val->name . '">' . $val->name . '</option>';
                    }
                    ?>
                </select>

                <?php
                $checked = '';
                if (!empty($_POST['storehouse_orders_desc']))
                    $checked = ' checked';
                ?>
                <input type="checkbox" id="storehouse_orders_desc" name="storehouse_orders_desc" <?php echo $checked ?>>
                <label for="storehouse_orders_desc">Обратный порядок</label>
            </div>

            <input type="submit" name="filter_storehouse" value="Поиск">
        </form>
    </div>


    <div class="editing">
        <p>Редактирование таблиц: </p>
        <?php
        $sql = 'show tables;';
        $result = mysqli_query($link, $sql);

        while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
            for ($i = 0; $i < count($row); $i++) {
                echo '<input type="radio" name="edit" value="radio_edit_' . $row[$i] . '"><label>' . $row[$i] . '</label>';
            }
        }
        ?>


        <div class="table_car">
            <form action="application/scripts/editing.php" method="post">
                <?php
                $sql = 'select * from car;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();
                $counter = 0;
                foreach ($finfo as $val) {
                    if ($counter > 0)
                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                    $counter++;
                }
                ?>
                <input type="submit" name="insert_car" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_car" value="Удалить запись">
            </form>
        </div>

        <div class="table_client">
            <form action="application/scripts/editing.php" method="post">
                <?php
                $sql = 'select * from client;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();
                $counter = 0;
                foreach ($finfo as $val) {
                    if ($counter > 0)
                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                    $counter++;
                }
                ?>
                <input type="submit" name="insert_client" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_client" value="Удалить запись">
            </form>
        </div>

        <div class="table_delivery">
            <form action="application/scripts/editing.php" method="post">
                <?php
                $sql = 'select * from delivery;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();
                $counter = 0;
                foreach ($finfo as $val) {
                    if ($counter > 0)
                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                    $counter++;
                }
                ?>
                <input type="submit" name="insert_delivery" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_delivery" value="Удалить запись">
            </form>
        </div>

        <div class="table_employee">
            <form action="application/scripts/editing.php" method="post">
                <?php
                $sql = 'select * from employee;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();
                $counter = 0;
                foreach ($finfo as $val) {
                    if ($counter > 0)
                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                    $counter++;
                }
                ?>
                <input type="submit" name="insert_employee" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_employee" value="Удалить запись">
            </form>
        </div>

        <div class="table_order_list">
            <form action="application/scripts/editing.php" method="post">
                <?php
                $sql = 'select * from order_list;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();

                foreach ($finfo as $val) {
                    echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';

                }
                ?>
                <input type="submit" name="insert_order_list" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_order_list" value="Удалить запись">
            </form>
        </div>

        <div class="table_orders">
            <form action="application/scripts/editing.php" method="post">
                <?php
                $sql = 'select * from orders;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();
                $counter = 0;
                foreach ($finfo as $val) {
                    if ($counter > 0)
                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                    $counter++;
                }
                ?>
                <input type="submit" name="insert_orders" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_orders" value="Удалить запись">
            </form>
        </div>

        <div class="table_product">
            <form action="application/scripts/editing.php" method="post">
                <?php
                $sql = 'select * from product;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();
                $counter = 0;
                foreach ($finfo as $val) {
                    if ($counter > 0)
                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                    $counter++;
                }
                ?>
                <input type="submit" name="insert_product" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_product" value="Удалить запись">
            </form>
        </div>

        <div class="table_product_type">
            <form action="application/scripts/editing.php" method="post">
                <?php
                $sql = 'select * from product_type;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();
                $counter = 0;
                foreach ($finfo as $val) {
                    if ($counter > 0)
                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                    $counter++;
                }
                ?>
                <input type="submit" name="insert_product_type" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_product_type" value="Удалить запись">
            </form>
        </div>

        <div class="table_status">
            <form action="application/scripts/editing.php" method="post">
                <?php
                $sql = 'select * from status;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();
                $counter = 0;
                foreach ($finfo as $val) {
                    if ($counter > 0)
                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                    $counter++;
                }
                ?>
                <input type="submit" name="insert_status" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_status" value="Удалить запись">
            </form>
        </div>

        <div class="table_storehouse">
            <form action="application/scripts/editing.php" method="post">
                <?php
                $sql = 'select * from storehouse;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();
                $counter = 0;
                foreach ($finfo as $val) {
                    if ($counter > 0)
                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                    $counter++;
                }
                ?>
                <input type="submit" name="insert_storehouse" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_storehouse" value="Удалить запись">
            </form>
        </div>

    </div>



    <?php if (isset($_SESSION['response'])) echo $_SESSION['response'] ?>


    <?php mysqli_close($link) ?>

</body>

</html>