<?php
	include ($_SERVER["DOCUMENT_ROOT"]."/part/dbusermenu.php");

  $query = $mysqli->query("SELECT * FROM feedback WHERE resource_id=" . $_SESSION['resourceid'] . " ORDER BY feedback_date DESC");
  while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
    $subquery = $mysqli->query("SELECT * FROM lst_status WHERE status_id=" . $row['status_id']);
    $subrow = $subquery->fetch_array(MYSQLI_ASSOC);
    
    $date = date_create($row["feedback_date"])->Format('d.m.Y');
      
  	$feedbackRow .= "<tr>
  		<td class='mdl-data-table__cell--non-numeric'>" . $row["feedback_id"] . "</td>
  		<td class='mdl-data-table__cell--non-numeric'>" . $date . "</td>
  		<td class='mdl-data-table__cell--non-numeric' style='white-space: pre-line;'>" . $row["feedback_desc"] . "</td>
  		<td class='mdl-data-table__cell--non-numeric' style='white-space: pre-line;'>" . $row["feedback_comment"] . "</td>
  		<td class='mdl-data-table__cell--non-numeric'>" . $subrow["status_name"] . "</td>
  		</tr>";
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
	<!--<div class="mdl-cell mdl-cell--4-col"></div>-->
		<div class="mdl-card mdl-cell mdl-cell--6-col mdl-shadow--2dp">
		<div class="mdl-card__title">
			<h2 class="mdl-card__title-text">Отставить отзыв или сообщить об ошибке</h2>
		</div>
		<div class="mdl-card__supporting-text">
			<form id="feedbackform" name="feedbackform" method="post" action="/php/handler.php">
				<input type="hidden" name="site" value="feedback.php">
				<ul class="demo-list-item mdl-list">
				  <li class="mdl-list__item">
 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 450px">
    						<input autocomplete="off" class="mdl-textfield__input" type="text" pattern="^[А-Яа-яЁё]+$" readonly value="<?php print_r($resource["resource_firstnameRus"] . " " . $resource["resource_lastnameRus"]); ?>">
    						<label class="mdl-textfield__label">Пользователь</label>
					  	</div>
					</li>
					<li class="mdl-list__item">
					  	<div class="mdl-textfield mdl-js-textfield" style="width: 450px">
    						<textarea class="mdl-textfield__input" type="text" rows= "10" name="feedbacktext" required></textarea>
    						<label class="mdl-textfield__label">Описание</label>
					  	</div>
				  </li>
				</ul>
			</form>
		</div>
		
		<div class="mdl-card__actions mdl-card--border">
			<button name="savebtn" form="feedbackform" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Отправить</button>
		</div>

		</div>
	</div>
	
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
  		<div class="mdl-card__title">
  			<h2 class="mdl-card__title-text">Список отзывов</h2>
  		</div>
  		<div class="mdl-card__supporting-text">
				<table class="mdl-data-table mdl-shadow--2dp">
					<thead>
  					<tr>
  						<th class="mdl-data-table__cell--non-numeric">ID</th>
  						<th class="mdl-data-table__cell--non-numeric">Дата</th>
  						<th class="mdl-data-table__cell--non-numeric">Описание</th>
  						<th class="mdl-data-table__cell--non-numeric">Комментарий</th>
  						<th class="mdl-data-table__cell--non-numeric">Статус</th>
  					</tr>
					</thead>
					<tbody>
					  <?php print($feedbackRow); ?>
					</tbody>
				</table>
  		</div>
		</div>
	</div>
	</main>
	
</div>

	<script defer src="http://code.getmdl.io/1.1.3/material.min.js"></script>
  <script src="/js/jquery-2.2.2.min.js"></script>
  <script src="/js/timesheet.js"></script>
  <script src="/js/js.js"></script>

</body>

	<?php
		$query->free(); /* очищаем результаты выборки */
		$mysqli->close(); /* закрываем подключение */
	?>
</html>