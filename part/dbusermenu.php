<?php
	session_start();
  if(!$_SESSION['userid']){header("Location: /php/logout.php");exit;}
  
  $stopsite = 0;//1 - сайт остановлен, 0 - сайт работает
  
	// подключение к БД
	$suLogin='root';
  $suPassword='ivA3mppIH27HQySC';
  $mysqli = mysqli_connect("localhost", $suLogin, $suPassword, "ts");
  if (mysqli_connect_errno($mysqli)) {echo "<p>Не удалось подключиться к MySQL: " . mysqli_connect_error() . "</p>";}
	$mysqli->query("SET NAMES utf8");
	
	// выбор ФИО
	$query = $mysqli->query("SELECT * FROM lst_resource WHERE resource_id=" . $_SESSION['resourceid']);
  $resource = $query->fetch_array(MYSQLI_ASSOC);
  $resourceName = $resource["resource_lastnameRus"] . " " . $resource["resource_firstnameRus"];

  // выбор User
  $query = $mysqli->query("SELECT * FROM lst_user WHERE user_id=" . $_SESSION['userid']);
	$user = $query->fetch_array(MYSQLI_ASSOC);
	
	// остановка сайта
	if ($user['usergroup_id'] < 999 && $stopsite) {
	  echo "<script> location ='/stop.php';</script>";
	}

	//установка темы
	$query = $mysqli->query("SELECT * FROM lst_theme WHERE theme_id=" . $user['theme_id']);
	$theme = $query->fetch_array(MYSQLI_ASSOC);
	
	// выбор Menu
	if ($_REQUEST['menu_id']) {$_SESSION['menu_id'] = $_REQUEST['menu_id'];}
	$query = $mysqli->query("SELECT * FROM lst_menu ORDER BY menu_sort");
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		if ($row['usergroup_id'] <= $user['usergroup_id']){
		  if ($row['menu_id'] == $_SESSION['menu_id']) {$menuisactive = "is-active"; $_SESSION['menu_id'] = $row['menu_id'];} else {$menuisactive = "";}
		  if ($row['menu_blank'] == 1) {$menutarget = "_blank";} else {$menutarget = "";}
			$menu = $menu . "<a class='mdl-navigation__link " . $menuisactive . "' href='" . $row['menu_href'] . "?menu_id=" . $row['menu_id']  . "' target='" . $menutarget . "'>". $row['menu_name'] ."</a>";
		}
	}
	
	// обязательный возврат в профиль при пароле 123456
	if ($user['user_password'] == "e10adc3949ba59abbe56e057f20f883e" && !isset($_REQUEST['noredirect'])) {
	  echo "<script>alert('Необходимо сменить стандартный пароль.');</script>"; 
    echo "<script> location ='/php/lk.php?noredirect=1&menu_id=1';</script>";
	}
	
	// обязательный возврат в профиль для заполнения рабочего телефона и email
	if (!$resource["resource_phone"] && !isset($_REQUEST['noredirect'])) {
	  echo "<script>alert('Укажите номер рабочего телефона.');</script>"; 
    echo "<script> location ='/php/lk.php?noredirect=1&menu_id=1';</script>";
	} else if (!$resource["resource_email"] && !isset($_REQUEST['noredirect'])) {
	  echo "<script>alert('Укажите адрес электронной почты.');</script>"; 
    echo "<script> location ='/php/lk.php?noredirect=1&menu_id=1';</script>";
	}
?>