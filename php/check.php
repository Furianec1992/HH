<?php
header('Content-Type: text/html; charset=utf-8');
setlocale(LC_ALL,'ru_RU.65001','rus_RUS.65001','Russian_Russia.65001','russian');
session_start();
	if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} }
    if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }

    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $login = trim($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
    $password = trim($password);
    
//Подключаемся к базе данных.
    $suLogin='root';
    $suPassword='ivA3mppIH27HQySC';
    $mysqli = mysqli_connect("localhost", $suLogin, $suPassword, "ts");
    if (mysqli_connect_errno($mysqli)) {echo "<p>Не удалось подключиться к MySQL: " . mysqli_connect_error() . "</p>";}
    $mysqli->query("SET NAMES utf8");
    
//извлекаем из базы все данные о пользователе с введенным логином
 		$query = $mysqli->query("SELECT * FROM lst_user WHERE user_active = -1 and user_login='" . $login . "'");
		while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
			$userPassword = $row["user_password"];
			$userId = $row["user_id"];
			$resourceId = $row["resource_id"];
		}

    if (empty($userId)){
//если пользователя с введенным логином не существует
  		echo "<script>alert('Извините, такого пользователя не существует.');</script>"; 
  		echo "<script> location ='/index.php';</script>";
    	exit;
    } else {
//если существует, то сверяем пароли
      if ($userPassword == md5($password)) {
//если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
        $_SESSION['login'] = $login; 
      	$_SESSION['userid'] = $userId;//эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь
      	$_SESSION['resourceid'] = $resourceId;
      	$query = $mysqli->query("UPDATE lst_user SET user_logindate='" . Date("Y-m-d") . "' WHERE user_id=" . $userId);
      	if ($password == "123456") {
      	 // echo "<script>alert('Необходимо сменить стандартный пароль.');</script>"; 
          echo "<script> location ='/php/lk.php?menu_id=1';</script>";
      	} else {
      	  header("Location:/php/doc.php?menu_id=3");
      	}
      } else {
//если пароли не сошлись
      	$_SESSION['WrongPassword'];
      	echo '<script> alert("Извините, введённый вами пароль неверный."); </script>'; 
      	echo '<script> location ="/index.php";</script>';
      	exit;
      }
    }

  $query->free(); /* очищаем результаты выборки */
  $mysqli->close(); /* закрываем подключение */
?>
