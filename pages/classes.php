<?php

class Tools {
    static function connect($host="127.0.0.1:3306",
                            $user='root',
                            $pass="123456",
                            $dbname="shop") {
        // настройка подключения через PDO(PHP DATA OBJECT)
        $cs = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8;';
        $options = array(
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'
        );

        try {
            $pdo = new PDO($cs, $user, $pass, $options);
            return $pdo;
        } catch(PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }

    static function register($login,$pass,$imagepath){
        $login = trim($login);
        $pass = trim($pass);
        $imagepath = trim($imagepath);

        if($login==''||$pass==''){
            echo "<h3 style='color:red;'>Заполните все поля</h3>";
            return false;
        }

        if(strlen($login) <3 || strlen($login) > 30 || strlen($pass) <3 || strlen($pass) >30 ) {
            echo "<h3 style='color:red;'>От 3 до 30 символов</h3>";
            return false;
        }

        Tools::connect();
        $customer = new Customer($login,$pass,$imagepath);
        $err=$customer->intoDb();
        if($err !=''){
            return false;
        }
        return true;
    }
}

class Customer {
    public $id;
    public $login;
    public $pass;
    public $roleid;
    public $discount;
    public $total;
    public $imagepath;

    function __construct($login, $pass, $imagepath, $id=0){
        $this->login=$login;
        $this->pass=$pass;
        $this->imagepath=$imagepath;
        $this->id=$id;
        $this->total=0;
        $this->discount=0;
        $this->roleid=2;
    }

    /**
     * @return int
     */
    public function intoDb(){
        try {
            $pdo = Tools::connect();
            // создание (подготовка) запроса
            $ps = $pdo->prepare("INSERT INTO customers(login,pass,roleid,discount,total,imagepath) VALUES(:login,:pass,:roleid,:discount,:total,:imagepath)");
            // разименовывание массива
            $ar=(array)$this;
            array_shift($ar); // array_shift () позволяет удалить первый элемент массива, т.е. в нашем случае это id
            $ps->execute($ar); // Запускает подготовленный запрос на выполнение
        } catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }

    // метод, который возвращает объект класса Customer по указанному id
    static function fromDb($id){
        $customer = null;
        try {
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM customers WHERE id=?");
            $res = $ps->execute(array($id));
            $row = $res->fetch();
            $customer=new Customer($row['login'], $row['pass'],$row['imagepath'],$row['id']);
            return $customer;
        } catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }
}

class Item {
    public $id, $itemname, $catid, $pricein, $pricesale, $info, $rate, $imagepath, $action;

    function  __construct($itemname, $catid, $pricein, $pricesale, $info, $imagepath, $rate=0, $action=0, $id=0) {
        $this->id = $id;
        $this->itemname = $itemname;
        $this->catid = $catid;
        $this->pricein = $pricein;
        $this->pricesale = $pricesale;
        $this->info = $info;
        $this->rate = $rate;
        $this->imagepath = $imagepath;
        $this->action = $action;
    }

    function intoDb() {
        try {
            $pdo = Tools::connect();
            // создание (подготовка) запроса
            $ps = $pdo->prepare("INSERT INTO items(itemname, catid, pricein, pricesale, info, rate, imagepath, action) VALUES(:itemname, :catid, :pricein, :pricesale, :info, :rate, :imagepath, :action)");

            $ar=(array)$this;
            array_shift($ar); // array_shift () позволяет удалить первый элемент массива, т.е. в нашем случае это id
            $ps->execute($ar); // Запускает подготовленный запрос на выполнение
        } catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }
    
    static function fromDb($id){
        $item = null;
        try {
            $pdo = Tools::connect();
            $ps = $pdo->prepare("SELECT * FROM items WHERE id=?");
            $ps->execute(array($id));
            $row = $ps->fetch();
            $item=new Item($row['itemname'], $row['catid'],$row['pricein'],$row['pricesale'], $row['info'], $row['imagepath'], $row['rate'], $row['action'], $row['id']);
            return $item;
        } catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }

    static function getItems($catid=0) {
        $ps = null;
        $items = null;

        try {
            $pdo = Tools::connect();
            if($catid==0) {
                $ps = $pdo->prepare("SELECT * FROM items");
                $ps->execute();
            } else {
                $ps=$pdo->prepare("SELECT * FROM items WHERE catid=?");
                $ps->execute(array($catid));
            }

            while($row=$ps->fetch()) {
                $item=new Item($row['itemname'],$row['catid'],$row['pricein'],$row['pricesale'],$row['info'],$row['imagepath'],$row['rate'],$row['action'],$row['id']);
                $items[]=$item;
            }
            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    function Draw() {

        // верхушка описания товара
        echo "<div class='col-12 col-sm-12 col-md-4 col-lg-4 mt-4'>";
        echo "<div class='row mr-1 mt-1 p-2 top'>";
        echo "<a href='pages/itemInfo.php?name=".$this->id."' class='mr-auto' target='_blank'>";
        echo $this->itemname;
        echo "</a>";
        echo "<span class='ml-auto'>";
        echo $this->rate . "&nbsp;rate";
        echo "</span>";
        echo "</div>";

        // изображение товара
        echo "<div class='row mr-1'>";
        echo "<img src=".$this->imagepath." class='w-50'>";
        echo "<span class='d-block pr-2 ml-auto'>";
        echo "&#8381; &nbsp;".$this->pricesale;
        echo "</span>";
        echo "</div>";

        // описание товара
        echo "<div class='row mr-1 mt-1'>";
        echo "<p class='col-12 description'>".$this->info."</p>";
        echo "</div>";

        // кнопка добавления в корзину
        echo "<div class='row mr-1'>";
        // хранение товара в куки после добавления в корзину
        $ruser='';
        if(!isset($_SESSION['reg'])|| $_SESSION['reg']==''){
            $ruser="cart_".$this->id;

        } else {
            $ruser=$_SESSION['reg']."_".$this->id;
        }

        echo "<button class='btn btn-success col-12' onclick=createCookie('".$ruser."','".$this->id."')>Add To My Cart</button>";
        echo "</div>";
        echo "</div>";
    }
    
    

    public function DrawForCart() {
        echo "<div class='row m-2'>";
        echo "<img src=".$this->imagepath." class='col-1 img-fluid'>";
        echo "<span style='margin-right:10px; background-color: khaki' class='col-3'>".$this->itemname."</span>";
        echo "<span style='margin-right:10px; background-color: #6ef0cb' class='col-3'>" .$this->pricesale."</span>";

        $ruser='';
        if(!isset($_SESSION['reg']) || $_SESSION['reg']=='') {
            $ruser="cart_".$this->id;
        } else {
            $ruser=$_SESSION['reg']. "_" . $this->id;
        }

        echo "<button class='btn btn-danger btn-sm' onclick=eraseCookie('".$ruser."')>x</button>";
        echo "</div>";
    }

    public function Sale() {
        try {
            $pdo=Tools::connect();
            $ruser='cart';
            if(isset($_SESSION['reg']) && $_SESSION['reg'] !=='') {
                $ruser=$_SESSION['reg'];
            }

            $sql = "UPDATE customers SET total=total+? WHERE login=?";
            $ps=$pdo->prepare($sql);
            $ps->execute(array($this->pricesale, $ruser));

            // вставляем данные в таблицу
            $ins = "INSERT INTO sales(customername, itemname, pricein, pricesale, datesale) VALUES (?,?,?,?,?)";
            $ps=$pdo->prepare($ins);
            $ps->execute(array($ruser, $this->itemname, $this->pricein, $this->pricesale, @date("Y/m/d H:i:s")));

            // удаление товара после оформления (изменить)
//            $del = "DELETE FROM items WHERE id=?";
//            $ps=$pdo->prepare($del);
//            $ps->execute(array($this->id));
            
            
            // отправка информации о заказе на почту
            require_once 'js/PHPmailer/PHPMailerAutoload.php';
            
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';

            // настройки SMTP (SMTP - почтовый протокол передачи данных)
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPDebug = 0;

            $mail->Host = 'ssl://smtp.gmail.com';
            $mail->Port = 465;
            $mail->Username = 'pivovarov.d23@gmail.com';
            $mail->Password = 'vfksi02.03.87';

            // от кого
            $mail->setFrom('pivovarov.d23@gmail.com', 'SHOP Gorodetskiy');

            // кому
            $mail->addAddress('pivovarov.d23@gmail.com', 'Администратору');

            // тема письма
            $mail->Subject = "Новый заказ на сайте SHOP Gorodetskiy";


            $mail->AddEmbeddedImage($this->imagepath, 'logo_2u');

            // Тело письма
//            $body = '<p><strong>Zdarova</strong></p>';
            $body = "<table cellspacing='0' cellpadding='0' border='0' width='600' style='background-color:red!important;'>
            <tr><td>".$this->itemname."</td><td><img src='cid:logo_2u' alt='php' style='height:90px!important;'></td></tr>
            </table>
            ";
            $mail->msgHTML($body);

            // приложение
            $mail->addAttachment(__DIR__.'/images/ninja.png');
            $mail->Send();
            
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}