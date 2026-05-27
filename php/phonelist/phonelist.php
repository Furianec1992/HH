<?php
	include ($_SERVER["DOCUMENT_ROOT"]."/part/dbusermenu.php");

  $query = $mysqli->query("SELECT * FROM lst_resource WHERE resource_active = -1 and resource_hideinphonelist = 0 ORDER BY resource_lastnameRus ASC ");
  while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
    if ($row["resource_showPhoneCell"] == -1) {
      $resource_showPhoneCell = $row["resource_phonecell"];
    } else {
      $resource_showPhoneCell = "";
    }
  	$phonelistRow .= "<tr>
  		<td class='mdl-data-table__cell--non-numeric'>" . $row["resource_lastnameRus"] . " " . $row["resource_firstnameRus"] . "</td>
  		<td class='mdl-data-table__cell--non-numeric'>" . $row["resource_email"] . "</td>
  		<td class='mdl-data-table__cell--non-numeric'>" . $row["resource_phone"] . "</td>
  		<td class='mdl-data-table__cell--non-numeric'>" . $resource_showPhoneCell . "</td>
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
		<div class="mdl-cell mdl-cell--12-col">
  		<div class="mdl-card__title">
  			<h2 class="mdl-card__title-text">Справочник телефонов</h2>
  		</div>
  		<div class="mdl-card__supporting-text">
				<table class="mdl-data-table mdl-shadow--2dp">
					<thead>
  					<tr>
  						<th class="mdl-data-table__cell--non-numeric">Сотрудник</th>
  						<th class="mdl-data-table__cell--non-numeric">Email</th>
  						<th class="mdl-data-table__cell--non-numeric">Рабочий телефона</th>
  						<th class="mdl-data-table__cell--non-numeric">Сотовый телефона</th>
  					</tr>
					</thead>
					<tbody>
					  <?php print($phonelistRow); ?>
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