<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Магазин мебели</title>
</head>

<?php
session_start();
$link = mysqli_connect("localhost", "root", "1234", "furniture_shop");
$sql =
    'select id_product, price, name_product, size, material, product_type.name_product_type, storehouse.address_storehouse
    from product join product_type on product.id_product_type = product_type.id_product_type
                 join storehouse on product.id_storehouse = storehouse.id_storehouse';

$result = mysqli_query($link, $sql);
?>

<body>
    <div class="user">
        <!-- <a href="#">Войти</a>
        <a href="#">Регистрация</a> -->
        <h4>Иванов</h4>
        <a href="">Выйти</a>
    </div>
    <div class="main_wrapper">
        <div class="items_wrapper">
            <?php
            while ($res_arr = mysqli_fetch_array($result)) {
                echo '
            <div class="item">
                <img src="" alt="">
                <div class="info">' .
                    $res_arr['name_product'] . '<br>' . $res_arr['price'] . ' руб' . '<br>' . $res_arr['size'] .
                    '</div>
                <form action="scripts/add_cart.php" method="post">
                    <input type="text" hidden name="id" value="' . $res_arr['id_product'] . '">
                    <input type="text" name="count" placeholder="Количество">
                    <input type="submit" value="Добавить в корзину">
                </form>
            </div>';
            }

            ?>
        </div>

        <div class="cart">
            <div class="items_cart">
                <img src="images/cart.png" alt="cart.png">
                <p>Корзина</p>
            </div>

            <form action="">
                Стол дубовый x2 20000 руб.
                <input type="submit" value="Удалить">
            </form>
            
            <form action="">
                Офисное кресло x1 4000 руб.
                <input type="submit" value="Удалить">
            </form>

            <form action="">
                Итого: 24000 руб.
                <input type="submit" value="Оформить заказ">
            </form>
        </div>
    </div>

</body>

</html>