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
      $day[$i] = $weekday->Format("M-d");
      $weekday->modify('+1 day');
    }
    
    $query = $mysqli->query("SELECT * FROM wedate");// выбор списка документов
		while ($row = $query->fetch_array(MYSQLI_ASSOC)) {// облокировка при закрытой дате
    	if ($doc['doc_finish'] <= $row['wedate_date'] && $doc['doc_finish'] >= $row['wedate_date']-7 && $row['wedate_closets']) {
    		$atrrDelDisabled = "disabled";
    		break;
    	}
    };
    
    // выбор ФИО
    $query = $mysqli->query("SELECT * FROM lst_resource WHERE resource_id=" . $_SESSION['resourceid']);
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {$resourceName = $row["resource_lastnameEng"] . " " . $row["resource_firstnameEng"];}
    $query = $mysqli->query("SELECT * FROM doc WHERE doc_id=" . $_REQUEST['doc_id']);
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {$dateFinish = $row['doc_finish'];}
    
    // выбор даты корректировки
    $query = $mysqli->query("SELECT max(timesheet_dateCorr), max(timesheet_date) FROM timesheet WHERE 	timesheet_del = 0 and doc_id=" . $_REQUEST['doc_id']);
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
      if ($row['max(timesheet_dateCorr)'] > $row['max(timesheet_date)']) {
        $TSDateCorr = $row['max(timesheet_dateCorr)'];
      } else {
        $TSDateCorr = "No corrections";
      }
    }
    
// заполнение часов в табеле
		
		//подключение к БД
    $query = $mysqli->query("SELECT * FROM qry_timesheet_print WHERE timesheet_del=0 and doc_id=" . $_REQUEST['doc_id'] . " ORDER BY contract_id, project_nameEngShort, activity_id, hours_type_id, timesheet_date");
    $arrayrows = mysqli_num_rows($query);
    //заполнение
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {

    	$arraycurr++;
    	$hrsDescIndex_new = $row["contract_number"] . $row["project_nameEngShort"] . $row["activity_id"] . $row["hours_type_nameEngShort"];
    	
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
    	
    	//добавление пустых ячеек после последнего значения
    	if ($hrsDescIndex != $hrsDescIndex_new && !empty($hrsDescIndex)) {
    		for ($trnull = $daytofinish_new; $trnull > 0 ; $trnull--) {
	    		$hrsDescHTML = $hrsDescHTML . "<td></td>";
	    		
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
	    	$hrsDescHTML = $hrsDescHTML . "<td style='width: 34px; font-weight: bolder;' type='text' id='totalr" . $rowcurr . "' readonly value=" . $sumhrsсurr . "	>" . round($sumhrsсurr,1) . "</td></tr>";
    	}

    	if ($hrsDescIndex != $hrsDescIndex_new || empty($hrsDescIndex)) {
    		$hrsDescHTML = $hrsDescHTML . "<tr><td> " . $r=$r+1 . "</td><td style='width: 500px;' class='mdl-data-table__cell--non-numeric'>" . $row["contract_number"] . " - " . 
    		  substr($row["project_nameEngShort"],0,60) . "</td><td style='width: 101px;' class='mdl-data-table__cell--non-numeric'>" . $row["activity_id"] . 
    		  "</td><td  style='width: 96px;' class='mdl-data-table__cell--non-numeric'>" . $row["hours_type_nameEngShort"] . "</td>";
    		$daytofinish = 7;
    		$sumhrsсurr = 0;
    		$rowcurr++;
    	}

	    //добавление пустых ячеек перед первым значением
	    $daytofinish_new = (strtotime($dateFinish)-strtotime($row['timesheet_date']))/86400;
	    for ($trnull = $daytofinish-$daytofinish_new-1; $trnull > 0 ; $trnull--) {
	    	$hrsDescHTML = $hrsDescHTML . "<td></td>";
	    	
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
	    
			$hrsDescHTML = $hrsDescHTML . "<td id='" . $row["timesheet_id"] . "' style='width: 62px;' readonly value='" . $row["sum(`timesheet`.`timesheet_hours`)"] . "' class=r" . $rowcurr . ">" . 
			  round($row["sum(`timesheet`.`timesheet_hours`)"],1) . "</td>"; 
			
			$sumhrs = $sumhrs + round($row["sum(`timesheet`.`timesheet_hours`)"],1);
			$sumhrsсurr = $sumhrsсurr + round($row["sum(`timesheet`.`timesheet_hours`)"],1);
	    
	    switch ($hrscurr) {
        case 6: $cellSumm6 += round($row["sum(`timesheet`.`timesheet_hours`)"],1); break; 
        case 7: $cellSumm7 += round($row["sum(`timesheet`.`timesheet_hours`)"],1); break;
        case 1: $cellSumm1 += round($row["sum(`timesheet`.`timesheet_hours`)"],1); break;
        case 2: $cellSumm2 += round($row["sum(`timesheet`.`timesheet_hours`)"],1); break;
        case 3: $cellSumm3 += round($row["sum(`timesheet`.`timesheet_hours`)"],1); break;
        case 4: $cellSumm4 += round($row["sum(`timesheet`.`timesheet_hours`)"],1); break;
        case 5: $cellSumm5 += round($row["sum(`timesheet`.`timesheet_hours`)"],1); break;
      }
			$cellTotalSumm += round($row["sum(`timesheet`.`timesheet_hours`)"],1);
	    
	    
			$hrsDescIndex = $hrsDescIndex_new;
			$daytofinish = $daytofinish_new;

			//добавление пустых ячеек после последнего последнего значения
			if ($arraycurr == $arrayrows) {
	    	for ($trnull = $daytofinish_new; $trnull > 0 ; $trnull--) {
		    	$hrsDescHTML = $hrsDescHTML . "<td></td>";
    		  
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
		    $hrsDescHTML = $hrsDescHTML . "<td style='width: 34px; font-weight: bolder;' type='text' id='totalr" . $rowcurr . "' readonly value=" . $sumhrsсurr . ">" . round($sumhrsсurr,1) . "</td></tr>";
			}
    }
	?>

<html>
<head>
	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/head.html"); ?>
  <link rel="stylesheet" href="/css/print.css" /> <!--media="print" -->
  
  <style>
    td {
      border-bottom: none !important;
    }
  </style>
  
</head>

<body>
	<main class="mdl-layout__content">
	<div class="mdl-grid mdl-cell mdl-cell--12-col" style="max-width: 1550px;">
		<div class="mdl-cell mdl-cell--12-col" style="text-align: right; font-size: 15;">LATEST CORR. DATE: <?php print($TSDateCorr); ?></div>
		<div class="mdl-cell mdl-cell--12-col" style="text-align: right; font-size: 15;">WEEK ENDING DATE: <?php print($dateFinish); ?> </div>
  	<div class="mdl-cell mdl-cell--3-col" style="font-size: 15;">SNGT TIMESHEET</div>
 		<div class="mdl-cell" style=" font-size: 20;">EMPLOYEE NAME: <?php print($resourceName); ?> </div>
 		

	<div class="mdl-card mdl-cell mdl-cell--12-col">
			<form id="timesheetForm">
				<input type="hidden" name="site" value="timesheet.php">
				<input type="hidden" name="00-doc_id" value="<?php print($_REQUEST['doc_id']); ?>">
				<input type="hidden" name="00-dateFinish" value="<?php print($dateFinish); ?>">
				<table class="mdl-data-table mdl-shadow--2dp" id="table">
					 <thead>
	          <tr>
							<th class="mdl-data-table__cell--non-numeric"></th>
							<th class="mdl-data-table__cell--non-numeric">Contract - Project</th>
							<th class="mdl-data-table__cell--non-numeric">Activity</th>
							<th class="mdl-data-table__cell--non-numeric">Type</th>
							<th><?php print($day[1]); ?></th>
							<th><?php print($day[2]); ?></th>
							<th><?php print($day[3]); ?></th>
							<th><?php print($day[4]); ?></th>
							<th><?php print($day[5]); ?></th>
							<th><?php print($day[6]); ?></th>
							<th><?php print($day[7]); ?></th>
							<th>Total</th>
	          </tr>
	        </thead>
					<tbody id="tbody">
					<?php echo($hrsDescHTML); ?>
					
					<tr>
              <td class="mdl-data-table__cell--non-numeric" style="widtd: 57px;"></td>
							<td class="mdl-data-table__cell--non-numeric" style="width: 34px; font-weight: bolder;">Grand Total</td>
							<td class="mdl-data-table__cell--non-numeric" style="widtd: 105px;"></td>
							<td class="mdl-data-table__cell--non-numeric" style="widtd: 100px;"></td>
							<td style="width: 69px; font-weight: bolder;"><?php print($cellSumm6); ?></td>
							<td style="width: 69px; font-weight: bolder;"><?php print($cellSumm7); ?></td>
							<td style="width: 69px; font-weight: bolder;"><?php print($cellSumm1); ?></td>
							<td style="width: 69px; font-weight: bolder;"><?php print($cellSumm2); ?></td>
							<td style="width: 69px; font-weight: bolder;"><?php print($cellSumm3); ?></td>
							<td style="width: 69px; font-weight: bolder;"><?php print($cellSumm4); ?></td>
							<td style="width: 69px; font-weight: bolder;"><?php print($cellSumm5); ?></td>
							<td style="width: 34px; font-weight: bolder;" id="totalctotal" readonly><?php print($cellTotalSumm); ?></td>
	          </tr>
					</tbody>
				</table>
				
			</form>
		</div>
	</div>
	
		<ul class="demo-list-item mdl-list">
			<li class="mdl-list__item">
				<span class="mdl-list__item-primary-content" style="width: 560px;"><b>Employee Signature : ____________________    Date: _________________</b></span>
				<span class="mdl-list__item-primary-content"><b>Approval Signature: ____________________    Date: _________________</b></span>
			</li>
		</lu>
	</main>

</div></div>	

<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/bodyscripts.php"); ?>

  <script>
  	window.print();
  </script>
</body>

	<?php
	  $query->free(); /* очищаем результаты выборки */
		$mysqli->close(); /* закрываем подключение */
	?> 
</html>