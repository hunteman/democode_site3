<h3>Catalog page</h3>
<form action="index.php?page=1" method="post">
    <div class="row" style="margin-right:10px;">
        <select class="float-right" name="catid" id="" onchange="getItemsCat(this.value)">
        <option value="0">Select category:</option>
            <?php
            $pdo=Tools::connect();
            $ps=$pdo->prepare("SELECT * FROM categories");
            $ps->execute();
            while ($row=$ps->fetch()){
                echo "<option value=".$row['id'].">".$row['category']."</option>";
            }
            ?>
        </select>
    </div>
    <?php
    echo "<div class='row' id='result'>";
    $items=Item::GetItems(); // получим массив товаров из таблицы items
    foreach ($items as $item) {
        $item->Draw();
    }
    echo "</div>";
    ?>
</form>

<script>
    function getItemsCat(cat) {
        if(cat==''){
            document.getElementById('result').innerHTML=';'
        }

        // создаем аякс объект
        if (window.XMLHttpRequest){
            ao=new XMLHttpRequest();
        } else {
            ao=new ActiveXObject('Microsoft.XMLHTTP');
        }

        ao.onreadystatechange=function () {
            if(ao.readyState==4 && ao.status==200){
                document.getElementById('result').innerHTML=ao.responseText;
            }
        };

        ao.open('post', 'pages/lists.php', true);
        ao.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ao.send("cat="+cat);
    }
    
    
    function createCookie(uname, id) {
        alert('Товар добавлен в корзину!');
        $.cookie(uname, id, {expires:7, path: '/'});
    }
</script>




