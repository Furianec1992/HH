<?php
	session_start();
	if($_SESSION['userid']) {
	  header("Location: /php/doc.php");
	  exit;
	}

//	$user_agent = $_SERVER["HTTP_USER_AGENT"];
//  if (strpos($user_agent, "Firefox") !== false) $browser = "Firefox";
//  elseif (strpos($user_agent, "Opera") !== false) $browser = "Opera";
//  elseif (strpos($user_agent, "Chrome") !== false) $browser = "Chrome";
//  elseif (strpos($user_agent, "MSIE") !== false) {
//    $browser = "Internet Explorer";
//    header("Location: /notsupportedbrowser.php");
//	  exit;
//  }
//  elseif (strpos($user_agent, "Safari") !== false) $browser = "Safari";
//  else {
//   $browser = "Неизвестный";
//    header("Location: /notsupportedbrowser.php");
//	  exit;
//  }
  // echo "Ваш браузер: $browser";
  
?>

	<html>
	<head>
		<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/head.html"); ?>
    
    <style>
    .mdl-textfield {
			  width: 100%;
			}
		</style>
	</head>
	<body>
		<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		  <header class="mdl-layout__header">
		    <div class="mdl-layout__header-row">
		      <span class="mdl-layout-title">База данных "Табель"</span>
		    </div>
		  </header>

		  <main class="mdl-layout__content">
		    <div class="mdl-grid">
		    	<div class="mdl-cell mdl-cell--4-col mdl-cell--hide-tablet mdl-cell--hide-phone"></div>
		    	<div class="mdl-cell mdl-cell--4-col mdl-cell mdl-cell--4-col mdl-cell--12-col-tablet mdl-card mdl-shadow--2dp">
		    		<div class="mdl-card__title">
					    <h2 class="mdl-card__title-text">Добро пожаловать!</h2>
					  </div>
					  <div class="mdl-card__supporting-text">
							<form action="/php/check.php" method="POST" class="form" name="login" id="login">
								<ul class="demo-list-item mdl-list">
								  <li class="mdl-list__item">
				 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				    						<input class="mdl-textfield__input" type="text" name="login" id="login" required>
				    						<label class="mdl-textfield__label" >Логин</label>
									  	</div>
									</li>
									<li class="mdl-list__item">
									  	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				    						<input class="mdl-textfield__input" type="password" name="password" id="password" required>
				    						<label class="mdl-textfield__label" >Пароль</label>
									  	</div>
								  </li>
								 </lu>
							</form>
						</div>
						<div class="mdl-card__actions mdl-card--border">
					    <button id="loginbtn" type="submit" form="login" name="action" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Войти</button>
					  </div>
					</div>
		    </div>
		  </main>
		 
			<footer class="mdl-mini-footer mdl-cell--hide-tablet mdl-cell--hide-phone">
			  <div class="mdl-mini-footer__left-section">
			    <div class="mdl-logo">Сахалинские Нефтегазовые Технологии ООО</div>
			  </div>
			</footer>
		</div>
		
		<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/bodyscripts.php"); ?>
	</body>
	</html>