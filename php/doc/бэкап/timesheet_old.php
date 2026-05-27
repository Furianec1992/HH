<?php
	include ($_SERVER["DOCUMENT_ROOT"]."/part/dbusermenu.php");
	include ($_SERVER["DOCUMENT_ROOT"]."/part/function.php");
		
  $query = $mysqli->query("SELECT * FROM qry_doc_list WHERE doc_id=" . $_REQUEST['doc_id']);// выбор списка документов
  $doc = $query->fetch_array(MYSQLI_ASSOC);
  
  //если документ удален или не пренадлежит текущему сотруднику, то направить на список документов
  if ($doc['doc_del'] == 1 || $doc['resource_id'] != $_SESSION['resourceid']) {
    echo "<script> location ='/php/doc.php';</script>";
  }
  
// создание дат для дней недели в шапке
    $weekday = date_create($doc['doc_start']);
    for ($i = 1; $i < 8; $i++) {
      $day[$i] = $weekday->Format("d-m");
      $weekday->modify('+1 day');
    }

// заполнение списков
    $query = $mysqli->query("SELECT * FROM lst_activity WHERE viewinlist = -1");
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {$activity = $activity . "<option value=".$row["activity_id"].">" . $row["activity_id"] . " " . $row["activity_desc"] . "</option>";}
	
	$query = $mysqli->query("SELECT * FROM contract_project_names ORDER by project_nameEngShort");
	$project = ""; // Инициализация переменной
	while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	    $disabled = ($row['Hours'] < 1) ? " disabled" : "";
    
    	// Форматирование часов
	    $hours = $row['Hours'];
	    $formattedHours = (floor($hours) == $hours) ? (int)$hours : number_format($hours, 2, '.', '');
    
	    $project .= "<option value=\"" . $row['project_id'] . "\"" . $disabled . ">" 
        	     . $row['project_nameEngShort'] // Название проекта
	             . " [Остаток: $formattedHours ч.]" // Остаток часов сразу после названия
	             . " (Дог." . $row['contract_number'] . ")" // Номер договора после остатка
	             . "</option>";
	}
    
    $query = $mysqli->query("SELECT * FROM lst_hours_type WHERE viewinlist = 1 ORDER by sort");
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {$hourstype = $hourstype . "<option value=".$row["hours_type_id"] .">" . $row["hours_type_nameRusShort"] . " - " . $row["hours_type_nameRusLong"] .
    "</option>";}
    
    $query = $mysqli->query("SELECT * FROM doc WHERE doc_id=" . $_REQUEST['doc_id']);
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {$dateFinish = $row['doc_finish'];}

// заполнение часов в табеле!!!!!!!!!!!!!
    $query = $mysqli->query("SELECT * FROM qry_timesheet WHERE timesheet_del=0 and doc_id=" . $_REQUEST['doc_id'] . " ORDER BY contract_id, project_nameEngShort, activity_id, hours_type_id, timesheet_dateCorr, timesheet_date");
    $arrayrows = mysqli_num_rows($query);
    
    //заполнение
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
      //артибуты для предыдущего значения
      $atrrHrsDisabledPRE = $atrrHrsDisabled;
      
      //определение закрыт ли опер.период и указания на редактирование
      if (wedate($row['timesheet_date']) < wedate($row['timesheet_dateCorr'])) {$wedate = wedate($row['timesheet_dateCorr']);} else {$wedate = wedate($row['timesheet_date']);}
      $subquery = $mysqli->query("SELECT * FROM wedate WHERE wedate_date='" . $wedate . "'");
      while ($wedate = $subquery->fetch_array(MYSQLI_ASSOC)){
        if ($wedate['wedate_closets']) {$atrrHrsDisabled = "disabled";} else {$atrrHrsDisabled = "";}
      }
      if (!$subquery->num_rows) {$atrrHrsDisabled = "disabled";};

    	$arraycurr++;
    	
    	switch ($hrscurr) {
        case 6: $hrscurr = 7; break;
        case 7: $hrscurr = 1; break;
        case 1: $hrscurr = 2; break;
        case 2: $hrscurr = 3; break;
        case 3: $hrscurr = 4; break;
        case 4: $hrscurr = 5; break;
        case 5: $hrscurr = 6; break;
        default: $hrscurr = 6;
      }

    	if ($row["timesheet_dateCorr"] == 0) {$row["timesheet_dateCorr"] = '';}
    	$hrsDescIndex_new = $row["contract_number"] . $row["project_nameEngShort"] . $row["activity_id"] . $row["hours_type_nameRusShort"]. $row["timesheet_dateCorr"];
    	
    	//добавление пустых ячеек после последнего значения для пред.значения
    	if ($hrsDescIndex != $hrsDescIndex_new && !empty($hrsDescIndex)) {
    		for ($trnull = $daytofinish_new; $trnull > 0 ; $trnull--) {
	    		$hrsDescHTML = $hrsDescHTML . "<td><input autocomplete='off' name=" . $rowcurr . "-hrs" . $hrscurr . "  step='0.1' pattern='-?[0-9]*(.[0-9]+)?' onfocus='this.select (); fW ()'
	    		onblur='fW (); fS ()' class='P1 mdl-textfield__input r" . $rowcurrClass . " c" . $hrscurr . "' " . $atrrHrsDisabledPRE . "></input></td>";
	    		
  	    	switch ($hrscurr) {
            case 6: $hrscurr = 7; break;
            case 7: $hrscurr = 1; break;
            case 1: $hrscurr = 2; break;
            case 2: $hrscurr = 3; break;
            case 3: $hrscurr = 4; break;
            case 4: $hrscurr = 5; break;
            case 5: $hrscurr = 6; break;
            default: $hrscurr = 6;
          }
    		}
	    	
	    	$hrsDescHTML = $hrsDescHTML . "<td><input style='font-weight: bolder;' class='mdl-textfield__input' type='text' id='totalr" . $rowcurrClass . "' disabled value=" . round($sumhrsсurr,1) . "	></td>
	    	  <td><input class='P5 mdl-textfield__input' type='text' disabled value=" . $dateCorrPRE . "></td></tr>";
    	}

    	if ($hrsDescIndex != $hrsDescIndex_new || empty($hrsDescIndex)) {
    		$rowcurr++;
    		if ($rowcurr < 10) {
    		  $rowcurrClass = $rowcurr;
    		  $rowcurr = "0" . $rowcurr;
    		} else {
    		  $rowcurrClass = $rowcurr;
    		  $rowcurr = $rowcurr;
    		}
    		
    		$hrsDescHTML = $hrsDescHTML . "<tr class='mainrows'><td> " . $r=$r+1 . "</td><td style='white-space: normal;' class='mdl-data-table__cell--non-numeric'>" .
    		  mb_substr($row["project_nameEngShort"],0,200) . " (Дог." . $row["contract_number"] . ")" . "</td>
    		  <input type='hidden' name=" . $rowcurr . "-project_id value=" . $row["project_id"] . ">
    		  <td class='mdl-data-table__cell--non-numeric'>" . $row["activity_id"] . "</td>
    		  <input type='hidden' name=" . $rowcurr . "-activity_id value=" . $row["activity_id"] . ">
    		  <td class='mdl-data-table__cell--non-numeric'>" . $row["hours_type_nameRusShort"] . "</td>
    		  <input type='hidden' name=" . $rowcurr . "-hourstype_id value=" . $row["hours_type_id"] . ">";
    		$daytofinish = 7;
    		$sumhrsсurr = 0;
    	}

	    //добавление пустых ячеек перед первым значением
	    $daytofinish_new = (strtotime($dateFinish)-strtotime($row['timesheet_date']))/86400;
	    for ($trnull = $daytofinish-$daytofinish_new-1; $trnull > 0 ; $trnull--) {
	    	$hrsDescHTML = $hrsDescHTML . "<td><input autocomplete='off' name=" . $rowcurr . "-hrs" . $hrscurr . " step='0.1' pattern='-?[0-9]*(.[0-9]+)?'
	    	class='P0 mdl-textfield__input r" . $rowcurrClass . " c" . $hrscurr . "' onfocus='this.select (); fW ()' onblur='fW (); fS ()' " . $atrrHrsDisabled . "></input></td>";
	    	  
	    	switch ($hrscurr) {
          case 6: $hrscurr = 7; break;
          case 7: $hrscurr = 1; break;
          case 1: $hrscurr = 2; break;
          case 2: $hrscurr = 3; break;
          case 3: $hrscurr = 4; break;
          case 4: $hrscurr = 5; break;
          case 5: $hrscurr = 6; break;
          default: $hrscurr = 6;
        }
	    }
	    
			$hrsDescHTML = $hrsDescHTML . "<td><input type='hidden' name='" . $rowcurr . "-id" . $hrscurr . "' value=" . $row["timesheet_id"] . " " . $atrrHrsDisabled ."></input><input autocomplete='off'
			  name=" . $rowcurr . "-hrs" . $hrscurr . "  step='0.1' pattern='-?[0-9]*(.[0-9]+)?' onfocus='this.select (); fW ()' onblur='fW (); fS ()' id='" . $row["timesheet_id"] .
			  "' value='" . round($row["timesheet_hours"],1) . "' class='P3 mdl-textfield__input r" . $rowcurrClass . " c" . $hrscurr . "' " . $atrrHrsDisabled . "></input></td>";
			
			$sumhrs = $sumhrs + round($row["timesheet_hours"],1);
			$sumhrsсurr = $sumhrsсurr + round($row["timesheet_hours"],1);
			
			// сумма по колонкам
			switch ($hrscurr) {
        case 6: $cellSumm6 += round($row["timesheet_hours"],1); break;
        case 7: $cellSumm7 += round($row["timesheet_hours"],1); break;
        case 1: $cellSumm1 += round($row["timesheet_hours"],1); break;
        case 2: $cellSumm2 += round($row["timesheet_hours"],1); break;
        case 3: $cellSumm3 += round($row["timesheet_hours"],1); break;
        case 4: $cellSumm4 += round($row["timesheet_hours"],1); break;
        case 5: $cellSumm5 += round($row["timesheet_hours"],1); break;
      }
			$cellTotalSumm += round($row["timesheet_hours"],1);
	    
			$hrsDescIndex = $hrsDescIndex_new;
			$daytofinish = $daytofinish_new;

			//добавление пустых ячеек после последнего значения
			if ($arraycurr == $arrayrows) {
	    	for ($trnull = $daytofinish_new; $trnull > 0 ; $trnull--) {
		    	switch ($hrscurr) {
            case 6: $hrscurr = 7; break;
            case 7: $hrscurr = 1; break;
            case 1: $hrscurr = 2; break;
            case 2: $hrscurr = 3; break;
            case 3: $hrscurr = 4; break;
            case 4: $hrscurr = 5; break;
            case 5: $hrscurr = 6; break;
            default: $hrscurr = 6;
          }
          
		    	$hrsDescHTML = $hrsDescHTML . "<td><input autocomplete='off' name=" . $rowcurr . "-hrs" . $hrscurr . "  step='0.1' pattern='-?[0-9]*(.[0-9]+)?' class='P4 mdl-textfield__input r"
		    	. $rowcurrClass . " c" . $hrscurr . "' onfocus='this.select (); fW ()' onblur='fW (); fS ()' " . $atrrHrsDisabled . "></input></td>";
		    }
		    $hrsDescHTML = $hrsDescHTML . "<td><input style='font-weight: bolder;' class='mdl-textfield__input' type='text' id='totalr" . $rowcurrClass . "' disabled value=" . round($sumhrsсurr,1) . "></td>
		    <td><input class='P5 mdl-textfield__input' type='text' disabled value=" . $row['timesheet_dateCorr'] . "></td></tr>";
			}
			//артибуты для предыдущего значения
		  $dateCorrPRE = $row['timesheet_dateCorr'];
    }
?>

<html>
<head>
	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/head.php"); ?>
	
    <script>
      var activity = '<?php echo $activity; ?>';
      var contract = '<?php echo $contract; ?>';
      var project = '<?php echo $project; ?>';
      var hourstype = '<?php echo $hourstype; ?>';
    </script>
    
    <style>
			.mdl-textfield__input {
				font-size: small;
			}
			.mdl-data-table td {
    		text-align: right;
			}
			.P3:disabled, .P0:disabled, .P1:disabled {
			  color: red;
			}
		</style>
    
</head>

<body>
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		 <header class="mdl-layout__header">
		   <div class="mdl-layout__header-row">
		    <span class="mdl-layout-title">Пользователь: <?php print_r($resourceName) ?></span>
		    <div class="mdl-layout-spacer"></div>
	      <nav class="mdl-navigation mdl-layout--large-screen-only">
	        <a class="mdl-navigation__link" href="/php/doc/doc_desc.php?doc_id=<?php print($_GET['doc_id']) ?>">Назад</a>
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

	<div class="mdl-card mdl-cell mdl-cell--12-col">
		<div class="mdl-card__title">
			<h2 class="mdl-card__title-text">Табель учета рабочего времени</h2>
		</div>
		<div class="mdl-card__supporting-text">
			<form id="timesheetForm" method="post" action="/php/handler.php" >
				<input type="hidden" name="site" value="timesheet.php">
				<input type="hidden" name="00-doc_id" value="<?php print($_REQUEST['doc_id']); ?>">
				<input type="hidden" name="00-dateFinish" value="<?php print($dateFinish); ?>">
				<table class="mdl-data-table mdl-shadow--2dp" id="table">
					 <thead>
	          <tr class="mainrows">
							<th class="mdl-data-table__cell--non-numeric" style="width: 57px;"></th>
							<th class="mdl-data-table__cell--non-numeric" style="width: 663px !important; vertical-align: middle; text-align: center;">СВР / Стадия / Услуга / Проект / Контракт</th>
							<th class="mdl-data-table__cell--non-numeric" style="width: 105px; vertical-align: middle; text-align: center;">Активити</th>
							<th class="mdl-data-table__cell--non-numeric" style="width: 100px; vertical-align: middle; text-align: center;">Тип</th>
							<th style="width: 69px; text-align: center;">Сб.<br /><?php print($day[1]); ?></th>
							<th style="width: 69px; text-align: center;">Вс.<br /><?php print($day[2]); ?></th>
							<th style="width: 69px; text-align: center;">Пн.<br /><?php print($day[3]); ?></th>
							<th style="width: 69px; text-align: center;">Вт.<br /><?php print($day[4]); ?></th>
							<th style="width: 69px; text-align: center;">Ср.<br /><?php print($day[5]); ?></th>
							<th style="width: 69px; text-align: center;">Чт.<br /><?php print($day[6]); ?></th>
							<th style="width: 69px; text-align: center;">Пт.<br /><?php print($day[7]); ?></th>
							<th style="width: 77px; vertical-align: middle; text-align: center;">Итого</th>
							<th style="width: 144px; vertical-align: middle; text-align: center;">Дата заполнения</th>
	          </tr>
	        </thead>
					<tbody id="tbody">
					<?php echo($hrsDescHTML); ?>
					
					  <tr>
              <td class="mdl-data-table__cell--non-numeric" style="width: 57px;"></td>
							<td style="font-weight: bolder;" class="mdl-data-table__cell--non-numeric" style="width: 663px;">ИТОГО</td>
							<td class="mdl-data-table__cell--non-numeric" style="width: 105px;"></td>
							<td class="mdl-data-table__cell--non-numeric" style="width: 100px;"></td>
							<td style="width: 69px;">
							  <input style="font-weight: bolder;" class="P5 mdl-textfield__input " id="totalc6" disabled value="<?php print($cellSumm6); ?>">
							</td>
							<td style="width: 69px;">
							  <input style="font-weight: bolder;" class="P5 mdl-textfield__input " id="totalc7" disabled value="<?php print($cellSumm7); ?>">
							</td>
							<td style="width: 69px;">
							  <input style="font-weight: bolder;" class="P5 mdl-textfield__input " id="totalc1" disabled value="<?php print($cellSumm1); ?>">
							</td>
							<td style="width: 69px;">
							  <input style="font-weight: bolder;" class="P5 mdl-textfield__input " id="totalc2" disabled value="<?php print($cellSumm2); ?>">
							</td>
							<td style="width: 69px;">
							  <input style="font-weight: bolder;" class="P5 mdl-textfield__input " id="totalc3" disabled value="<?php print($cellSumm3); ?>">
							</td>
							<td style="width: 69px;">
							  <input style="font-weight: bolder;" class="P5 mdl-textfield__input " id="totalc4" disabled value="<?php print($cellSumm4); ?>">
							</td>
							<td style="width: 69px;">
							  <input style="font-weight: bolder;" class="P5 mdl-textfield__input " id="totalc5" disabled value="<?php print($cellSumm5); ?>">
							</td>
							<td style="width: 77px;">
							  <input style="font-weight: bolder;" class="mdl-textfield__input" id="totalctotal" disabled value="<?php print($cellTotalSumm); ?>">
							</td>
							<td style='width: 144px;'>
							  <input class="P5 mdl-textfield__input" type="hidden">
							</td>
	          </tr>
					</tbody>
				</table>
			</form>
			</div>
			
			<div class="mdl-card__actions mdl-card--border">
				<button name="savebtn" title="Сохранить" form="timesheetForm" type="submit"  class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect">Сохранить</button>
				<a href="/php/doc/timesheet_print.php?doc_id=<?php print($_REQUEST['doc_id']) ?>" target="_blank" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Печать</a>
				<a onclick="AddRow()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Добавить</a>
				<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="https://docs.google.com/document/d/1VwqRvFJiViTBxfM7OHb-g1sWQIu869S7SOoHv7yrnBQ/edit#heading=h.oe4x4t3ankk6" target="_blank"
				  >Как заполнять?</a>
			</div>
			</div>
	</div>
	</main>
	
</div></div>

	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/bodyscripts.php"); ?>

<script>
  $(document)
    .on('keydown', 'table input', function(e) {
      var td;
      //e.text(e.text().replace(/,/,'.'));
  // alert($(this).text);
      switch (e.keyCode) {
        case 39: // right
          td = $(this).parent('td').next();
          break;
        
        case 37: // left
          td = $(this).parent('td').prev();
          break;
          
        case 40: // down
          var i = $(this).parent().index() + 1;
          td = $(this).closest('tr').next().find('td:nth-child(' + i + ')');
          break;
          
        case 38: // up
          var i = $(this).parent().index() + 1;
          td = $(this).closest('tr').prev().find('td:nth-child(' + i + ')');
          break;
      }
      td.find('input').focus();
    })
    .on('keyup', 'table input', function(e) {
      this.value = this.value.replace(/,/,'.');
    });
</script>

</body>
	<?php
		$query->free(); /* очищаем результаты выборки */
		$mysqli->close(); /* закрываем подключение */
	?>
</html>