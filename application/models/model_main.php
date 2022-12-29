<?php

class Model_Main extends Model
{
    public function get_data()
    {
        $link = mysqli_connect("localhost", "root", "1234", "furniture_shop");
        $sql =
            'select id_product, price, name_product, size, material, product_type.name_product_type, storehouse.address_storehouse
                from product join product_type on product.id_product_type = product_type.id_product_type
                 join storehouse on product.id_storehouse = storehouse.id_storehouse';

        $result = mysqli_query($link, $sql);
        $data = [];
        while ($res_arr = mysqli_fetch_array($result)){
            $data['items'][] = $res_arr;
        }

        $sql = 'select * from order_list;';
        $result = mysqli_query($link, $sql);
        while ($res_arr = mysqli_fetch_array($result)){
            $data['cart'][] = $res_arr;
        }
        mysqli_close($link);

        // $result = [];
        // if ($handle = opendir($_SERVER['DOCUMENT_ROOT'] . '/private/uploads')) {
        //     while (false !== ($entry = readdir($handle))) {
        //         if ($entry != "." && $entry != "..") {
        //             $result[] = $entry;
        //         }
        //     }
        //     closedir($handle);
        // }

        return $data;
    }


    public function add_cart($id_order, $id_product, $quantity){
        if ($quantity == ''){
            $quantity = 1;
        }
        
        $cart_empty = true;
        $link = mysqli_connect("localhost", "root", "1234", "furniture_shop");
        $sql = 'select id_order, id_product from order_list';
        $result = mysqli_query($link, $sql);
        while ($val = mysqli_fetch_array($result)){
            if ($val['id_order'] == $id_order && $val['id_product'] == $id_product){
                $cart_empty = false;
                break;
            }        
        }
        
        if (!$cart_empty){
            $sql = 'update order_list set quantity = quantity + '.$quantity. ' where id_order = '.$id_order. ' and id_product = '.$id_product.';';
        }
        else{
            $sql = 'insert into order_list (id_order, id_product, quantity) values('.$id_order.','.$id_product.','.$quantity.');';
        }
        $result = mysqli_query($link, $sql);

        mysqli_close($link);
    }


    public function del_cart($id_order, $id_product){
        $link = mysqli_connect("localhost", "root", "1234", "furniture_shop");
        $sql = 'update order_list set quantity = quantity - 1'. ' where id_order = '.$id_order. ' and id_product = '.$id_product.';';
        $result = mysqli_query($link, $sql);
        $sql = 'select quantity from order_list where id_order = '.$id_order. ' and id_product = '.$id_product.';';
        $result = mysqli_query($link, $sql);
        while ($val = mysqli_fetch_array($result)){
            if ($val['quantity'] == 0){
                $sql = 'delete from order_list where id_order = '.$id_order. ' and id_product = '.$id_product.';';
                $result = mysqli_query($link, $sql);
                break;
            }        
        }
        mysqli_close($link);
    }
}
