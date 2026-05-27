  <?php
		include ($_SERVER["DOCUMENT_ROOT"]."/part/dbusermenu.php");
    
    // выбор списка документов
    $query = $mysqli->query("SELECT * FROM qry_doc_list WHERE doc_type_id=10 and doc_del=0 and resource_id=" . $_SESSION['resourceid'] . " ORDER BY doc_start DESC");
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
      if (!$row['contract_id']) { $row['contract_id'] = 0;}
      $subquery = $mysqli->query("SELECT * FROM lst_contract WHERE contract_id = " . $row['contract_id']);
      $subrow = $subquery->fetch_array(MYSQLI_ASSOC);
      
      $datestart = date_create($row["doc_start"])->Format('d.m.Y');
      $datefinish = date_create($row["doc_finish"])->Format('d.m.Y');
    
    	$docsRow = $docsRow . "<tr><td class='mdl-data-table__cell--non-numeric'>" . $row["doc_id"] . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . $row["doc_type_name"] . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . $datestart . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . $datefinish . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . $subrow["contract_number"] . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . $row["doc_approvedOT"] . "</td>
    		<td class='mdl-data-table__cell--non-numeric'>" . $row["status_name"] . "</td>
    		</tr>";
    }
	?>

<html>
<head>
	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/head.php"); ?>
	
	<style>

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
	<!--<div class="mdl-cell mdl-cell--3-col"></div>-->
	<div class="mdl-cell  mdl-cell--12-col">
			<div class="mdl-card__title">
				<h2 class="mdl-card__title-text">Список подтвержденных сверхурочных</h2>
			</div>
			<div class="mdl-card__supporting-text">
				<table class="mdl-data-table mdl-shadow--2dp">
					<thead>
					<tr>
						<th class="mdl-data-table__cell--non-numeric">ID</th>
						<th class="mdl-data-table__cell--non-numeric">Тип документа</th>
						<th class="mdl-data-table__cell--non-numeric">Начало</th>
						<th class="mdl-data-table__cell--non-numeric">Окончание</th>
						<th class="mdl-data-table__cell--non-numeric">Контракт</th>
						<th class="mdl-data-table__cell--non-numeric">Часы</th>
						<th class="mdl-data-table__cell--non-numeric">Статус</th>
					</tr>
					</thead>
					<tbody>
					<?php print($docsRow); ?>
					</tbody>
				</table>
			</div>
	</div>
			
	</div>
	</main>
	
</div>
</body>

	<?php
		$query->free(); /* очищаем результаты выборки */
		$mysqli->close(); /* закрываем подключение */
	?>
</html>