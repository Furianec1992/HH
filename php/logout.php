<?php
	session_start();//открытие сессии
	unset($_SESSION['userid']);//закрытие сессии по логину
	session_destroy();//удаление сессии
	header("Location: /index.php");//Перенаправление на эту страницу 
?>