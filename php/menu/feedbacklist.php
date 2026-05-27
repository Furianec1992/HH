<?php
	include ($_SERVER["DOCUMENT_ROOT"]."/part/dbusermenu.php");

  $query = $mysqli->query("SELECT * FROM feedback ORDER BY feedback_date DESC");
  while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
    $resourcequery = $mysqli->query("SELECT * FROM lst_resource WHERE resource_id=" . $row['resource_id']);
    $resourcerow = $resourcequery->fetch_array(MYSQLI_ASSOC);
    
    $statusquery = $mysqli->query("SELECT * FROM lst_status WHERE status_id=" . $row['status_id']);
    $statusrow = $statusquery->fetch_array(MYSQLI_ASSOC);
    
    $date = date_create($row["feedback_date"])->Format('d.m.Y');

  	$feedbackRow .= "<tr>
  		<td class='mdl-data-table__cell--non-numeric'>" . $row["feedback_id"] . "</td>
  		<td class='mdl-data-table__cell--non-numeric'>" . $date . "</td>
  		<td class='mdl-data-table__cell--non-numeric' style='white-space: pre-line;'>" . $row["feedback_desc"] . "</td>
  		<td class='mdl-data-table__cell--non-numeric' style='white-space: pre-line;'>" . $row["feedback_comment"] . "</td>
  		<td class='mdl-data-table__cell--non-numeric'>" . $resourcerow["resource_email"] . "</td>
  		<td class='mdl-data-table__cell--non-numeric'>" . $statusrow["status_name"] . "</td>
  		<td>
  		  <a title='Редактировать документ'  href='/php/feedback/edit.php?feedback_id=" . $row["feedback_id"] ."&menu_id=" . $_REQUEST['menu_id'] . "' 
    		  class='mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--raised mdl-js-ripple-effect'>
    		  <i class='material-icons'>mode_edit</i>
    		</a>
    	</td>
  		</tr>";
  }
?>

<html>
<head>
	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/head.php"); ?>
	
		<style>
			td {
        
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
		<div class="mdl-card mdl-cell mdl-cell--12-col mdl-shadow--2dp">
  		<div class="mdl-card__title">
  			<h2 class="mdl-card__title-text">Список отзывов</h2>
  		</div>
  		<div class="mdl-card__supporting-text" style="width: 98%;">
				<table class="mdl-data-table mdl-shadow--2dp">
					<thead>
  					<tr>
  						<th class="mdl-data-table__cell--non-numeric">ID</th>
  						<th class="mdl-data-table__cell--non-numeric">Дата</th>
  						<th class="mdl-data-table__cell--non-numeric">Описание</th>
  						<th class="mdl-data-table__cell--non-numeric">Комментарий</th>
  						<th class="mdl-data-table__cell--non-numeric">Контакты</th>
  						<th class="mdl-data-table__cell--non-numeric">Статус</th>
  						<th class="mdl-data-table__cell--non-numeric"></th>
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