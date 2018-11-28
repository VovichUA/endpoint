<?php
/**
 * Created by PhpStorm.
 * User: vovichua
 * Date: 28.11.18
 * Time: 14:55
 */

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Главная</title>
</head>
<body>
    <form method="post" action="handler.php">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Выбрать город</label>
                <select class="custom-select mr-sm-2" id="city" name="city">
                    <option selected>od</option>
                    <option value="vn">vn</option>
                    <option value="kv">kh</option>
                    <option value="dp">dp</option>
                    <option value="kp">kp</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Телефон в формате (+380)XXXXXXXXX</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Телефон в формате (+380)XXXXXXXXX" pattern="/^\(\+380\)\d{9}$/i" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="validationCustom03">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="e-mail" name="e-mail" aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <button class="btn btn-primary" type="submit">Отправить</button>
        </div>
    </form>

</body>
</html>
