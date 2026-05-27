<?php
	include ($_SERVER["DOCUMENT_ROOT"]."/part/dbusermenu.php");
	
		$query = $mysqli->query("SELECT * FROM wedate ORDER by wedate_date");
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
    	$checked = "";
    	if ($row['wedate_closets'] == 1) {$checked = "checked";}
    	$wedate = $wedate . "<li class='mdl-list__item'><span class='mdl-list__item-primary-content'>" . $row['wedate_date'] . "</span><span class='mdl-list__item-secondary-action'><label class='mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect' for='list-checkbox-". $row['wedate_id'] . "'><input type='checkbox' id='list-checkbox-". $row['wedate_id'] . "' name='wedateid". $row['wedate_id'] . "' class='mdl-checkbox__input' ". $checked ."/></label></span></li>";
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
			.demo-list-control {
			  width: 300px;
			}
			.demo-list-radio {
			  display: inline;
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
		<div class="mdl-card mdl-cell mdl-cell--5-col mdl-shadow--2dp">
			<div class="mdl-card__title">
				<h2 class="mdl-card__title-text">Список операционных дней</h2>
			</div>
			<div class="mdl-card__supporting-text">
				<form id="operdniform" method="POST" action="/php/handler.php">
					<input type="hidden" name="site" value="operdni.php">
					<ul class="demo-list-control mdl-list">
						<?php print($wedate);?>
					</ul>
				</form>
				
			</div>	
			<div class="mdl-card__actions mdl-card--border">
				<button name="savebtn" type="submit" form="operdniform" type="submit" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Сохранить</button>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--5-col mdl-shadow--2dp">
			<div class="mdl-card__title">
				<h2 class="mdl-card__title-text">Добавить операционный день в список</h2>
			</div>
			<div class="mdl-card__supporting-text">
				<form id="addoperdniform" method="POST" action="/php/handler.php">
					<input type="hidden" name="site" value="operdni.php">
					<ul class="demo-list-item mdl-list">
					  <li class="mdl-list__item">
	 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	    						<input autocomplete="off" class="mdl-textfield__input" type="date" autocomplete="off" name="datestart" placeholder="">
	    						<label class="mdl-textfield__label">Дата окончания недели</label> 
						  	</div>
						</li>
					</ul>
				</form>
			</div>	
			<div class="mdl-card__actions mdl-card--border">
				<button name="addbtn" type="submit" form="addoperdniform" type="submit" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Сохранить</button>
			</div>
		</div>
	</div>
	</main>
	
</div>

	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/bodyscripts.php"); ?>
</body>

	<?php
		$query->free(); /* очищаем результаты выборки */
		$mysqli->close(); /* закрываем подключение */
	?>
</html>