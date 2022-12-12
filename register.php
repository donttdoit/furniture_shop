<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>База данных - Магазин мебели</title>
</head>

<body>
    <form name="auth" method="POST" action="bd.php">
        <label>Логин: <input type="text" name="name"></label>
        <label>Пароль: <input type="password" name="password"></label>

        <input type="submit" name="sign_in" value="Вход">
        <input type="submit" name="sign_up" value="Регистрация">
    </form>
</body>

</html>