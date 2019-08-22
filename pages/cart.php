<?php
echo '<form action="index.php?page=2" method="post">';

// проверка текущего имени пользователя
$ruser='';
if(!isset($_SESSION['reg']) || $_SESSION['reg']=='') {
    $ruser="cart";
} else {
    $ruser=$_SESSION['reg'];
}

// полная стоимость корзины
$total=0;
foreach ($_COOKIE as $k => $v) {
    $pos=strpos($k,"_"); // cart
    if(substr($k,0,$pos)== $ruser) {
        //получить номер товара
        $id=substr($k, $pos+1);
        //выбор товара по id
        $item=Item::fromDb($id);
        //формируем общую цену товаров
        $total+=$item->pricesale;
        //отрисовать товар
        $item->DrawForCart();
    }
}

    // блок для вывода общей стоимости и кнопки оформления заказа
    echo '<hr>';
    echo "<span style='margin-left: 100px; color:blue;'>Total cost is:</span>
          <span style='color: red; background-color: lightblue;'>$total</span>";
    echo '<button type="submit" class="btn btn-success" name="suborder" style="margin-left: 150px;" onclick=eraseCookie("'.$ruser.'")>Purchase order</button>';
   // echo $ruser;
    echo '</form>';

    // обработчик для оформления заказа
    if(isset($_POST['suborder'])) {
        foreach ($_COOKIE as $k => $v) {
            $pos=strpos($k,"_"); // cart
            if(substr($k,0,$pos)== $ruser) {
                //получить номер товара
                $id=substr($k, $pos+1);
                //выбор товара по id
                $item=Item::fromDb($id);
                // Sale() метод для оформления заказа
                $item->Sale();
            }
        }
    }

?>
<script>
    function createCookie(uname, id) {
        $.cookie(uname, id, { expires: 7, path: '/' });
    }

    function eraseCookie(uname) {
        $.removeCookie(uname, { path: '/' });
        window.location.reload();
    }
</script>

