<?php
	include ($_SERVER["DOCUMENT_ROOT"]."/part/dbusermenu.php");
	include ($_SERVER["DOCUMENT_ROOT"]."/part/function.php");
    
  if (isset($_REQUEST['feedback_id']) && $_REQUEST['feedback_id']) {
    $query = $mysqli->query("SELECT * FROM feedback WHERE feedback_id=" . $_REQUEST['feedback_id']);
    $feedback = $query->fetch_array(MYSQLI_ASSOC);
    
    $query = $mysqli->query("SELECT * FROM lst_status");
  	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
  	  if ($row['status_id'] == $feedback['status_id']) {$sel = "selected";} else {$sel = "";}
  	  $statuslst .= "<option " . $sel . " value=" . $row["status_id"] . ">" . $row["status_name"] . "</option>";
  	}
  	
  	$query = $mysqli->query("SELECT * FROM lst_resource WHERE resource_id=" . $feedback['resource_id']);
  	$resource = $query->fetch_array(MYSQLI_ASSOC);
  }

?>

<html>
<head>
	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/head.php"); ?>

    <style>
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
	        <a class="mdl-navigation__link" href="/php/menu/feedbacklist.php">Назад</a>
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
			<div class="mdl-card mdl-cell mdl-cell--5-col mdl-shadow--2dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Описание Отзыва</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<form>
						<ul class="demo-list-item mdl-list">
						  <li class="mdl-list__item">
		 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    						<input class="mdl-textfield__input" value="<?php print($feedback["feedback_id"]); ?>" readonly>
		    						<label class="mdl-textfield__label">ID</label>
							  	</div>
						  </li>
						  <li class="mdl-list__item">
		 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    						<input class="mdl-textfield__input" type="date" value="<?php print($feedback["feedback_date"]); ?>" readonly>
		    						<label class="mdl-textfield__label">Дата</label>
							  	</div>
						  </li>
						  <li class="mdl-list__item">
		 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    						<textarea class="mdl-textfield__input" type="text" rows= "5" readonly><?php print($feedback["feedback_desc"]); ?></textarea>
		    						<label class="mdl-textfield__label">Описание</label>
							  	</div>
						  </li>
						  <li class="mdl-list__item">
		 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    						<input class="mdl-textfield__input" value="<?php print($resource["resource_lastnameRus"] ." ". $resource["resource_firstnameRus"]) ?>" readonly>
		    						<label class="mdl-textfield__label">Пользователь</label>
							  	</div>
						  </li>
						  <li class="mdl-list__item">
		 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    						<input class="mdl-textfield__input" value="<?php print($resource["resource_email"]); ?>" readonly>
		    						<label class="mdl-textfield__label">Email</label>
							  	</div>
						  </li>
          </form>
        </div>
      </div>

      <div class="mdl-card mdl-cell mdl-cell--5-col mdl-shadow--2dp">
        <div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Ответ</h2>
				</div>
				<div class="mdl-card__supporting-text">
				  <form enctype="multipart/form-data" id="feedbacklist_editform" method="post" action="/php/handler.php">
            <input type="hidden" name="feedback_id" value="<?php print($_REQUEST['feedback_id']); ?>">
						<input type="hidden" name="site" value="feedbacklist_edit.php">
						  <li class="mdl-list__item">
		 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    						<textarea class="mdl-textfield__input" type="text" name="feedback_comment" rows= "25" ><?php print($feedback["feedback_comment"]); ?></textarea>
		    						<label class="mdl-textfield__label">Комментарий</label>
							  	</div>
						  </li>
						  <li class="mdl-list__item">
		 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		 							  <select class="mdl-textfield__input" name="status_id" style="width: 100%;">
		    						  <?php print($statuslst); ?>
		    						</select>
		    						<label class="mdl-textfield__label">Статус</label>
							  	</div>
						  </li>
						
					</form>
				</div>
				<div class="mdl-card__actions mdl-card--border">
					<button name="savebtn" form="feedbacklist_editform" type="submit" <?php print($atrrSaveDisabled); ?> class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect">Сохранить</button>
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