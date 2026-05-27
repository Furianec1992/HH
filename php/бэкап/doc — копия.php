  <?php
		include ($_SERVER["DOCUMENT_ROOT"]."/part/dbusermenu.php");
    
    // выбор списка документов
    $query = $mysqli->query("SELECT * FROM qry_doc_list WHERE doc_type_id=8 and doc_del=0 and resource_id=" . $_SESSION['resourceid'] . " ORDER BY doc_start DESC");
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
      $subquery = $mysqli->query("SELECT SUM(timesheet_hours) FROM timesheet WHERE timesheet_del=0 and doc_id=" . $row['doc_id']);
      $subrow = $subquery->fetch_array(MYSQLI_ASSOC);
      
      $datestart = date_create($row["doc_start"])->Format('d.m.Y');
      $datefinish = date_create($row["doc_finish"])->Format('d.m.Y');
      
    	$docsRow = $docsRow . "<tr><td class='mdl-data-table__cell--non-numeric'>" . $row["doc_id"] . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . $row["doc_type_name"] . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . $datestart . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . $datefinish . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . round($subrow['SUM(timesheet_hours)'], 2) . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . $row["status_name"] . "</td>
    		<td><a title='Редактировать документ'  href='/php/doc/doc_desc.php?doc_id=" . $row["doc_id"] ."&menu_id=" . $_REQUEST['menu_id'] . "' 
    		class='mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--raised mdl-js-ripple-effect'>
    		<i class='material-icons'>mode_edit</i></a></td></tr>";
    }
	?>

<html>
<head>
	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/head.php"); ?>
	
	<style>
	  #addtsbtn {
	    position: fixed;
	    display: block;
	    right: 0;
	    bottom: 0;
	    margin-right: 80px;
	    margin-bottom: 80px;
	    z-index: 900;
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
        <a class="mdl-navigation__link" href="logout.php">Выйти</a>
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

	<div class="mdl-cell  mdl-cell--12-col">
			<div class="mdl-card__title">
				<h2 class="mdl-card__title-text">Список еженедельных табелей</h2>
			</div>
			<div class="mdl-card__supporting-text">
				<table class="mdl-data-table mdl-shadow--2dp">
					<thead>
					<tr>
						<th class="mdl-data-table__cell--non-numeric">ID</th>
						<th class="mdl-data-table__cell--non-numeric">Тип документа</th>
						<th class="mdl-data-table__cell--non-numeric">Начало</th>
						<th class="mdl-data-table__cell--non-numeric">Окончание</th>
						<th class="mdl-data-table__cell--non-numeric">Часы</th>
						<th class="mdl-data-table__cell--non-numeric">Статус</th><th></th>
					</tr>
					</thead>
					<tbody>
					<?php print($docsRow); ?>
					</tbody>
				</table>
			</div>
	</div>
			<a id="addtsbtn" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent mdl-js-ripple-effect" href="/php/doc/doc_desc.php">Создать документ</a>
	</div>
	</main>
	
</div>
</body>

	<?php
		$query->free(); /* очищаем результаты выборки */
		$mysqli->close(); /* закрываем подключение */
	?>
</html>