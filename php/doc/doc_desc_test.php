<?php
		include ($_SERVER["DOCUMENT_ROOT"]."/part/dbusermenu.php");
		include ($_SERVER["DOCUMENT_ROOT"]."/part/function.php");
    
    if (isset($_REQUEST['doc_id'])) {
    	$query = $mysqli->query("SELECT * FROM qry_doc_list WHERE doc_type_id=8 and doc_id=" . $_REQUEST['doc_id'] );// выбор списка документов
    	$doc = $query->fetch_array(MYSQLI_ASSOC);
    	
    	//если документ удален или не пренадлежит текущему сотруднику, то направить на список документов
    	if ($doc['doc_del'] == 1 || $doc['resource_id'] != $_SESSION['resourceid']) {
    	  echo "<script> location ='/php/doc.php';</script>";
    	}
    	
    	$query = $mysqli->query("SELECT SUM(timesheet_hours) FROM timesheet WHERE timesheet_del=0 and doc_id=" . $_REQUEST['doc_id']);// выбор количества часов
    	$rowHrs = $query->fetch_array(MYSQLI_ASSOC);
    	if ($rowHrs['SUM(timesheet_hours)']) {
        $atrrSaveDisabled = "disabled"; $atrrDateReadonly = "readonly disabled"; 
      }
        
    	//блокировка удаления и сохранения при закрытом WEDATE
  		$query = $mysqli->query("SELECT * FROM timesheet WHERE doc_id=" . $_REQUEST['doc_id']);
  	  while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
  	    if (wedate($row['timesheet_date']) < wedate($row['timesheet_dateCorr'])) {$wedate = wedate($row['timesheet_dateCorr']);} else {$wedate = wedate($row['timesheet_date']);}
  	    $query = $mysqli->query("SELECT  wedate_closets FROM wedate WHERE wedate_date='" . $wedate . "'");
    	  $row = $query->fetch_array(MYSQLI_ASSOC);
        if ($row['wedate_closets'] == 1) {
          $atrrDateReadonly = "readonly disabled"; $atrrDelDisabled = "disabled"; $atrrSaveDisabled = "disabled";
        }
  	  }
    }
    if (!$doc["doc_type_name"]) {$doc["doc_type_name"]="Еженедельный табель";}

    // облокировка при новом документе
    if (!$_REQUEST['doc_id']) {
			$atrrDisabled = "disabled"; $atrrTSDisabled = "disabled"; $atrrDelDisabled = "disabled"; $hidden = "hidden";
		}   
?>

<html>
<head>
	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/head.php"); ?>

    <style>
			/*.demo-list-item {*/
			/*	 width: 600px;*/
			/*}*/
			.mdl-textfield {
			  width: 100%;
			}

			/*.mdl-textfield__label {*/
			/*  width: 500px; !important*/
			/*}*/
		</style>
</head>

<body>
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
		 <header class="mdl-layout__header">
		   <div class="mdl-layout__header-row">
		    <span class="mdl-layout-title">Пользователь: <?php print_r($resourceName) ?></span>
		    <div class="mdl-layout-spacer"></div>
	      <nav class="mdl-navigation mdl-layout--large-screen-only">
	        <a class="mdl-navigation__link" href="/php/doc.php">Назад</a>
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
			<div class="mdl-card mdl-shadow--2dp" style="width: 500px; max-width: 100%;">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Описание документа</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<form enctype="multipart/form-data"  id="doc_desc" name="docdescform" method="get" action="/php/handler.php">
						<input type="hidden" name="doc_id" value="<?php print($_GET['doc_id']); ?>">
						<input type="hidden" name="site" value="doc_desc.php">

						<ul class="demo-list-item mdl-list">
						  <li class="mdl-list__item">
		 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    						<input class="mdl-textfield__input" type="text" readonly placeholder="" name="docname" value="<?php print($doc["doc_type_name"]); ?>">
		    						<label class="mdl-textfield__label" for="docname">Документ (id: <?php print($_GET['doc_id']); ?>)</label>
							  	</div>
						  </li>
						  <li class="mdl-list__item">
		 							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    						<input class="mdl-textfield__input" type="date" name="dateBegin" id="dateBegin" required placeholder="" value="<?php print($doc["doc_start"]); ?>" <?php print($atrrDateReadonly); ?>>
		    						<label class="mdl-textfield__label" style="white-space: pre-wrap;">Дата начала недели (формат для Firefox ГГГГ-ММ-ДД)</label>
							  	</div>
						  </li>
							<li class="mdl-list__item">
						  	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    					<input class="mdl-textfield__input" type="date" name="dateFinish" id="dateFinish" required placeholder="" value="<?php print($doc["doc_finish"]); ?>" <?php print($atrrDateReadonly); ?>>
		    					<label class="mdl-textfield__label" style="white-space: pre-wrap;">Дата окончания недели (формат для Firefox ГГГГ-ММ-ДД)</label>
							  </div>
						  </li>
						  <li class="mdl-list__item">
		 						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    					<input type="<?php print($hidden)?>" class="mdl-textfield__input" readonly value="<?php print($doc["status_name"]); ?>">
		    					<label class="mdl-textfield__label">Статус</label>
							  </div>
						  </li>
						  <li class="mdl-list__item">
		 						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    					<input type="<?php print($hidden)?>" class="mdl-textfield__input" readonly value="<?php print($rowHrs['SUM(timesheet_hours)']); ?>">
		    					<label class="mdl-textfield__label">Количество часов в табеле</label>
		    					<input type="hidden" name="timesheet_hours" value="<?php print($rowHrs['SUM(timesheet_hours)']); ?>">
							  </div>
						  </li>	
						  <li class="mdl-list__item">
		 						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		    					<input type="<?php print($hidden)?>" class="mdl-textfield__input" readonly value="<?php print($doc["doc_comment"]); ?>">
		    					<label class="mdl-textfield__label">Замечания</label>
							  </div>
						  </li>
					</form>
				</div>
				
				<div class="mdl-card__actions mdl-card--border">
					<button name="savebtn" form="doc_desc" type="submit" <?php print($atrrSaveDisabled); ?> class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect">Сохранить</button>
					<button title="Просмотр часов" name="opentsbtn" form="doc_desc" type="submit" <?php print($atrrTSDisabled); ?> class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Просмотр часов</button>
					<button name="deletebtn" form="doc_desc" type="submit" <?php print($atrrDelDisabled); ?> class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Удалить</button>
				</div>
			</div>
   	</div>
	</main>

</div>

	<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/bodyscripts.php"); ?>
	
	<script> 
		$("#dateBegin")
			.change(function(){
				var d = new Date($("#dateBegin").val());
				var date_new = new Date(d.setDate(d.getDate()+6));
				var day_new = date_new.getDate();if (day_new < 10) {day_new = "0" + day_new;}
				var month_new = date_new.getMonth()+1;if (month_new < 10) {var month_new = "0" + month_new;}
				$("#dateFinish").val(d.getFullYear() + "-" + month_new + "-" + day_new);
			});
	
		$("#dateFinish")
			.change(function(){
				var d = new Date($("#dateFinish").val());
				var date_new = new Date(d.setDate(d.getDate()-6));
				var day_new = date_new.getDate();if (day_new < 10) {day_new = "0" + day_new;}
				var month_new = date_new.getMonth()+1;if (month_new < 10) {var month_new = "0" + month_new;}
				$("#dateBegin").val(d.getFullYear() + "-" + month_new + "-" + day_new);
			});
	</script>

</body>
	<?php
		$query->free(); /* очищаем результаты выборки */
		$mysqli->close(); /* закрываем подключение */
	?>
</html>