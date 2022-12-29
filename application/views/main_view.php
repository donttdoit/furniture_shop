<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="application/styles/style.css">
    <title>Магазин мебели</title>
</head>

<body>
    <div class="main_wrapper">
        <div class="items_wrapper">
            <?php
            foreach ($data['items'] as $val) {
                echo '
            <div class="item">
                <img src="" alt="">
                <div class="info">' .
                    $val['name_product'] . '<br>' . $val['price'] . ' руб' . '<br>' . $val['size'] .
                    '</div>
                <form action="" method="post">
                    <input type="text" hidden name="id" value="' . $val['id_product'] . '">
                    <input type="text" name="count" placeholder="Количество">
                    <input type="submit" name="add" value="Добавить в корзину">
                </form>
            </div>';
            }

            ?>
        </div>

        <div class="cart">
            <div class="items_cart">
                <img src="application/images/cart.png" alt="cart.png">
                <p>Корзина</p>
            </div>
            <?php 
                $total_price = 0;
                foreach ($data['cart'] as $val)
                {
                if ($val['id_order'] == $_SESSION['id_user'])
                    foreach ($data['items'] as $item){
                        if ($item['id_product'] == $val['id_product']){
                            $total_price += $item['price'] * $val['quantity'];
                            echo '
                                <form action="" method="post">
                                    '.$item['name_product'].' x'.$val['quantity'].' '.$item['price'] * $val['quantity'].' руб.
                                    <input type="text" hidden name="id" value="' . $item['id_product'] . '">
                                    <input type="submit" name="del" value="Удалить">
                                </form>
                                ';
                        }
                    }
                }      
            ?>

            <form action="" method="post">
                Итого: <?php echo $total_price.' руб.'; ?>
                <input type="submit" value="Оформить заказ">
            </form>
        </div>
    </div>

</body>

</html>