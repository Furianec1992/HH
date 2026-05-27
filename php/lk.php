<?php
	include ($_SERVER["DOCUMENT_ROOT"]."/part/dbusermenu.php");
	
	$query = $mysqli->query("SELECT * FROM lst_theme");
  while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
    if ($user['theme_id'] == $row['theme_id']) {
      $lstthemesel = "selected";
    } else {
      $lstthemesel = "";
    }
    $lsttheme .= "<option " . $lstthemesel . " value=".$row["theme_id"] .">" . $row["theme_name"] . "</option>";
  }
  
  $query2 = $mysqli->query("SELECT * FROM lst_resource WHERE resource_id=" . $_SESSION['resourceid']);
  while ($row2 = $query2->fetch_array(MYSQLI_ASSOC)) {
    if ($row2['resource_showPhoneCell'] == -1) {
      $showcellphonesel = "checked";
    } else {
      $showcellphonesel = "";
    }
  }
?>

<html>
<head>
	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/head.php"); ?>
	
		<style>
			li {
    		padding-top: 0px !important;
    		padding-bottom: 0px !important;
			}
			.mdl-textfield {
			  width: 100%;
			}
		</style>
</head>

<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
	 <header class="mdl-layout__header">
	   <div class="mdl-layout__header-row">
	    <span class="mdl-layout-title">Пользователь: <?php print_r($resourceName) ?></span>
	    <div class="mdl-layout-spacer"></div>
      <nav class="mdl-navigation mdl-layout--large-screen-only">
        <a class="mdl-navigation__link" href="/php/logout.php">Выйти</a>
      </nav>
     </div>
    </header>
    <div class="mdl-layout__drawer">
		    <span class="mdl-layout-title">Меню</span>
		    <nav class="mdl-navigation">
		      <?php print_r($menu) ?>
		    </nav>
	  </div>
	
	<main class="mdl-layout__content">
	<div class="mdl-grid">
	<!--<div class="mdl-cell mdl-cell--2-col"></div>-->
		<div class="mdl-card mdl-cell mdl-cell--5-col mdl-shadow--2dp" style="height: 570;width: 550px;">
		<div class="mdl-card__title">
			<h2 class="mdl-card__title-text">Данные о сотруднике</h2>
		</div>
		<div class="mdl-card__supporting-text">
			<form id="resourcedata" name="resourcedata" method="post" action="/php/handler.php">
				<input type="hidden" name="site" value="lk.php">
				<ul class="demo-list-item mdl-list">
				  <li class="mdl-list__item">
 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input autocomplete="off" class="mdl-textfield__input" type="text" name="FirstNameRus" id="FirstNameRus" pattern="^[А-Яа-яЁё\s]+$" required value="<?php print_r($resource["resource_firstnameRus"]); ?>" style="width: 250px;">
    						<label class="mdl-textfield__label">Имя на русском языке</label>
    						<span class="mdl-textfield__error">Допускаются только русские буквы.</span>
					  	</div>
					
					  	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input autocomplete="off" class="mdl-textfield__input" type="text" name="LastNameRus" id="LastNameRus" pattern="^[А-Яа-яЁё\s]+$" required value="<?php print_r($resource["resource_lastnameRus"]); ?>" style="width: 250px;">
    						<label class="mdl-textfield__label">Фамилия на русском языке</label>
    						<span class="mdl-textfield__error">Допускаются только русские буквы.</span>
					  	</div>
				  </li>
				  <li class="mdl-list__item">
 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input autocomplete="off" class="mdl-textfield__input" type="text" name="FirstNameEng" id="FirstNameEng" pattern="^[a-zA-Z\s]+$" required value="<?php print_r($resource["resource_firstnameEng"])?>" style="width: 250px;">
    						<label class="mdl-textfield__label">Имя на английском языке</label>
    						<span class="mdl-textfield__error">Допускаются только латинские буквы.</span>
					  	</div>
					
					  	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input autocomplete="off" class="mdl-textfield__input" type="text" name="LastNameEng" id="LastNameEng" pattern="^[a-zA-Z\s]+$" required value="<?php print_r($resource["resource_lastnameEng"]); ?>" style="width: 250px;">
    						<label class="mdl-textfield__label">Фамилия на английском языке</label>
    						<span class="mdl-textfield__error">Допускаются только латинские буквы.</span>
					  	</div>
				  </li>
				  <li class="mdl-list__item">
					  	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input autocomplete="off" class="mdl-textfield__input" type="email" name="Email" id="Email" required value="<?php print($resource["resource_email"]); ?>" placeholder="user@server.ru">
    						<label class="mdl-textfield__label">Email</label>
    						<span class="mdl-textfield__error">Введите в формате user@server.ru</span>
					  	</div>
				  </li>
				  <li class="mdl-list__item">
					  	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input autocomplete="off" class="mdl-textfield__input" type="text" name="Phone" id="Phone" pattern="7-[0-9]{4}-[0-9]{6}" required value="<?php print($resource["resource_phone"]); ?>" placeholder="7-1234-567890">
    						<label class="mdl-textfield__label">Рабочий телефон</label>
    						<span class="mdl-textfield__error">Введите в формате 7-0000-000000.</span>
					  	</div>
				  </li>
				  <li class="mdl-list__item">
					  	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input autocomplete="off" class="mdl-textfield__input" type="text" name="CellPhone" id="CellPhone" pattern="7-[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?php print($resource["resource_phonecell"]); ?>" placeholder="7-123-456-7890">
    						<label class="mdl-textfield__label">Сотовый телефон</label>
    						<span class="mdl-textfield__error">Введите в формате 7-000-000-0000.</span>
					  	</div>
				  </li>
				  <li class="mdl-list__item">
					  <span class="mdl-list__item-primary-content">
              <span>Показывать сот.телефон в справочнике</span>
            </span>
            <span class="mdl-list__item-secondary-action">
              <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="showcellphone">
                <input type="checkbox" name="showcellphone" id="showcellphone" class="mdl-checkbox__input" <?php print($showcellphonesel); ?>/>
              </label>
            </span>
				  </li>
				</ul>
			</form>
		</div>
		
		<div class="mdl-card__actions mdl-card--border">
			<button id="saveresourcebtn" name="saveresourcebtn" value="true" form="resourcedata" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect">
			  Сохранить личные данные
			</button>
		</div>
  
		</div>
		
		</div>
		
		<div class="mdl-grid">
		
		<div class="mdl-card mdl-cell mdl-cell--5-col mdl-shadow--2dp" style="height: 400; width: 550px;">
		<div class="mdl-card__title">
			<h2 class="mdl-card__title-text">Данные о пользователе</h2>
		</div>
		<div class="mdl-card__supporting-text">
			<form id="userdata" name="useddata" method="post" action="/php/handler.php" onsubmit="return validate_form()">
				<input type="hidden" name="site" value="lk.php">
				<ul class="demo-list-item mdl-list">
				  <li class="mdl-list__item">
 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input autocomplete="off" class="mdl-textfield__input" type="text" autocomplete="off" name="login" id="login" readonly value="<?php print($user["user_login"]); ?>">
    						<label class="mdl-textfield__label">Логин</label>
					  	</div>
					</li>
				  <li class="mdl-list__item">
 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input autocomplete="off" class="mdl-textfield__input" type="password" autocomplete="off" name="passwordnew1" id="passwordnew1">
    						<label class="mdl-textfield__label">Новый пароль</label>
					  	</div>
					</li>
					<li class="mdl-list__item">
					  	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input autocomplete="off" class="mdl-textfield__input" type="password" autocomplete="off" name="passwordnew2" id="passwordnew2">
    						<label class="mdl-textfield__label">Подтверждение пароля</label>
					  	</div>
				  </li>
				  <li class="mdl-list__item">
      			<select class="mdl-textfield__input" name="theme_id" style="width: 100%;">
    				  <?php print($lsttheme); ?>
  		      </select>
		      </li>
				</ul>			
			</form>
		</div>	
		<div class="mdl-card__actions mdl-card--border">
			<button id="saveuserbtn" title="Изменить пароль" name="saveuserbtn" type="submit" value="true" form="userdata" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect">
			  Изменить пароль
			</button>
		</div>
		</div>
	</div>
	</main>
	
</div>

	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/bodyscripts.php"); ?>

	<script>
		function validate_form(){
			var p1 = document.forms['userdata']['passwordnew1'].value;
			var p2 = document.forms['userdata']['passwordnew2'].value;
			
			if (p1 != p2){
				alert("Новый пароль не совпадает с проверкой.");
				return false;
			} 
		}
	</script>

</body>

	<?php
		$query->free(); /* очищаем результаты выборки */
		$mysqli->close(); /* закрываем подключение */
	?>
</html>