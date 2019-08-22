<h3>Registration Form</h3>

<?php
if(!isset($_POST['regbtn'])) {
    ?>
<form action="index.php?page=3" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="login">Login:</label>
        <input type="text" class="form-control" name="login">
    </div>
    <div class="form-group">
        <label for="pass1">Pass:</label>
        <input type="password" class="form-control" name="pass1">
    </div>
    <div class="form-group">
        <label for="pass2">Confirm pass:</label>
        <input type="password" class="form-control" name="pass2">
    </div>
    <div class="form-group">
        <label for="imagepath">Select image:</label><br>
        <input type="file" name="imagepath">
    </div>
    <button type="submit" class="btn btn-primary" name="regbtn">Register</button>
</form>

<?php
} else {
    // обработка добавленного изображения
    if(is_uploaded_file($_FILES['imagepath']['tmp_name'])) {
        $path = "images/".$_FILES['imagepath']['name'];
        move_uploaded_file($_FILES['imagepath']['tmp_name'], $path);
    }

    // регистрация пользователя
    if(Tools::register($_POST['login'],$_POST['pass1'],$path)) {
        echo '<h3 style="color:green;">New User Added!</h3>';
    }
}