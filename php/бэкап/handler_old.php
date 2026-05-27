<?php

  ini_set('display_errors',1);
	error_reporting(E_ALL ^E_NOTICE);
	
	session_start();
  if(!$_SESSION['userid']){header("Location: /php/logout.php");exit;}
    
	// подключение к БД
	$suLogin='root';
  $suPassword='ivA3mppIH27HQySC';
  $mysqli = mysqli_connect("localhost", $suLogin, $suPassword, "ts");
  if (mysqli_connect_errno($mysqli)) {echo "<p>Не удалось подключиться к MySQL: " . mysqli_connect_error() . "</p>";}
	$mysqli->query("SET NAMES utf8");
	
	include ($_SERVER["DOCUMENT_ROOT"]."/part/function.php");

// ------------------------------------------------ действия для doc_desc.php НАЧАЛО
		if ($_REQUEST['site']=="doc_desc.php"){
		if (isset($_REQUEST['dateFinish'])) {// проверка есть ли дата окончания в WE календаре
		  $query = $mysqli->query("SELECT  * FROM wedate WHERE wedate_date='" . $_REQUEST['dateFinish'] . "'");
  	  $rowscount = $query->num_rows;
  	  
  	  if (!$rowscount)  {
  	    echo "<script> alert('Дата окончания недели неверна.'); </script>"; 
        echo "<script> location ='/php/doc/doc_desc.php';</script>"; 
        exit;
  	  }
		}
		  
		if (isset($_REQUEST['opentsbtn'])) {
			header("Location: /php/doc/timesheet.php?doc_id=" . $_REQUEST['doc_id']);
			exit;
		}
		if (isset($_REQUEST['savebtn'])){
  		if ($_REQUEST['doc_id']) {
  		  //проверка закрытой недели
			  $query = $mysqli->query("SELECT * FROM timesheet WHERE doc_id=" . $_REQUEST['doc_id']);
	  	  while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	  	    if (wedate($row['timesheet_date']) < wedate($row['timesheet_dateCorr'])) {$wedate = wedate($row['timesheet_dateCorr']);} else {$wedate = wedate($row['timesheet_date']);}
	  	    $query = $mysqli->query("SELECT  wedate_closets FROM wedate WHERE wedate_date='" . $wedate . "'");
  	  	  $row = $query->fetch_array(MYSQLI_ASSOC);
    		  if ($row['wedate_closets'] == 1) {
    		    echo "<script> alert('Период закрыт, изменение не возможно.'); </script>"; 
            echo "<script> location ='/php/doc/doc_desc.php?doc_id=". $_REQUEST['doc_id'] ."';</script>";
          	exit;
    		  }
	  	  }

  			$query = $mysqli->query("UPDATE doc SET doc_upd=1, doc_start='" . $_REQUEST['dateBegin'] . "', doc_finish='" . $_REQUEST['dateFinish'] . "' 
  																WHERE doc_id=" . $_REQUEST['doc_id']);
  			echo "<script> alert('Документ сохранен.'); </script>"; 
      	echo "<script> location ='/php/doc/doc_desc.php?doc_id=". $_REQUEST['doc_id'] ."';</script>"; 
      	exit;
  		} else {
  		  //проверка дубликатов табеля
			  $query = $mysqli->query("SELECT * FROM doc WHERE 	doc_start='" . $_REQUEST['dateBegin'] . "' and resource_id=" . $_SESSION['resourceid'] . " and doc_type_id=8 and doc_del = 0");
	  	  while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
          echo "<script> location ='/php/doc/doc_desc.php?doc_id=". $row['doc_id'] ."';</script>";
        	exit;
	  	  }

  			$query = $mysqli->query("UPDATE doc SET doc_newcreate_id=0 WHERE doc_newcreate_id=" . $_SESSION['resourceid']);
  			$query = $mysqli->query("INSERT INTO doc(doc_start, doc_finish, doc_type_id, status_id, resource_id,	doc_newcreate_id) 
  															VALUES ('" . $_REQUEST['dateBegin'] . "','" . $_REQUEST['dateFinish'] . "'," . 8 . "," . 18 . "," . $_SESSION['resourceid'] . "," . $_SESSION['resourceid'] .")");
  			$query = $mysqli->query("SELECT doc_id FROM doc WHERE doc_newcreate_id=" . $_SESSION['resourceid']);
  			$row = $query->fetch_array(MYSQLI_ASSOC);
  			
  			header("Location: /php/doc/timesheet.php?doc_id=" . $row['doc_id']);
  			exit;
  		}
		}
		if (isset($_REQUEST['deletebtn'])){
			//проверка закрытой недели
			$query = $mysqli->query("SELECT * FROM timesheet WHERE doc_id=" . $_REQUEST['doc_id']);
	  	while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	  	  if (wedate($row['timesheet_date']) < wedate($row['timesheet_dateCorr'])) {$date = wedate($row['timesheet_dateCorr']);} else {$date = wedate($row['timesheet_date']);}
	  	  $query = $mysqli->query("SELECT  wedate_closets FROM wedate WHERE wedate_date='" . $date . "'");
  	    $row = $query->fetch_array(MYSQLI_ASSOC);
    	  if ($row['wedate_closets'] == 1) {
    	    echo "<script> alert('Период закрыт, удаление не возможно.'); </script>"; 
          echo "<script> location ='/php/doc/doc_desc.php?doc_id=". $_REQUEST['doc_id'] ."';</script>";
        	exit;
    	  } else {
    	    //ставим признак на удаление
    	    $query = $mysqli->query("UPDATE doc SET doc_del=1 WHERE doc_id=" . $_REQUEST['doc_id']);
            $query = $mysqli->query("UPDATE timesheet SET timesheet_del=1 WHERE doc_id=" . $_REQUEST['doc_id']);
	        echo "<script> alert('Табель удален.'); </script>";
  	 	    echo "<script> location ='/php/doc.php';</script>";
		      exit;
    	  }
	  	}
	  	//если нет часов в табеле вообще, ставим признак на удаление
	  	if(!$uery) {
	  	  $query = $mysqli->query("UPDATE doc SET doc_del=1 WHERE doc_id=" . $_REQUEST['doc_id']);
		  $query = $mysqli->query("UPDATE timesheet SET timesheet_del=1 WHERE doc_id=" . $_REQUEST['doc_id']);
	      echo "<script> alert('Табель удален.'); </script>";
  	 	  echo "<script> location ='/php/doc.php';</script>";
		    exit;
	  	}
		}
	}
// ------------------------------------------------ действия для doc_desc.php КОНЕЦ
  
// ------------------------------------------------ действия для timesheet.php НАЧАЛО  
  if ($_REQUEST['site'] == "timesheet.php"){
		if (isset($_REQUEST['savebtn'])) {
	  	foreach($_REQUEST as $key=>$value){
	  		$key = substr($key,3);

	  		if ($key == "doc_id") { $doc_id = $value; } 
	  		if ($key == "dateFinish") { $dateFinish = $value; } 
	  		if ($key == "contract_id") { $contract_id = $value; }
	  		if ($key == "project_id") { $project_id = $value; }
	  		if ($key == "activity_id") { $activity_id = $value; }
	  		if ($key == "hourstype_id") { $hourstype_id = $value; }
	  		$dateCorr = Date("Y-m-d");
	  		if ($key == "project_id_id") { $project_id = $value; }
	  		if ($key == "id6" || $key == "id7" || $key == "id1" || $key == "id2" || $key == "id3" || $key == "id4" || $key == "id5") {
	  		  $hrs_id = $value; 
	  		  $nextkeyUPDATE = true;
	  		}

				if ($key=="hrs6" || $key=="hrs7" || $key=="hrs1" || $key=="hrs2" || $key=="hrs3" || $key=="hrs4" || $key=="hrs5") {
				  if(!$value) {$value = 0;}
				  
		 			if ($key=="hrs6" && $value){$date=date("Y-m-d",strtotime($dateFinish)-6*60*60*24);}
			 		if ($key=="hrs7" && $value){$date=date("Y-m-d",strtotime($dateFinish)-5*60*60*24);}
		  		if ($key=="hrs1" && $value){$date=date("Y-m-d",strtotime($dateFinish)-4*60*60*24);}
			 		if ($key=="hrs2" && $value){$date=date("Y-m-d",strtotime($dateFinish)-3*60*60*24);}
		  		if ($key=="hrs3" && $value){$date=date("Y-m-d",strtotime($dateFinish)-2*60*60*24);}
		  		if ($key=="hrs4" && $value){$date=date("Y-m-d",strtotime($dateFinish)-1*60*60*24);}
		  		if ($key=="hrs5" && $value){$date=date("Y-m-d",strtotime($dateFinish));}
		  		
		      //проверка закрытой недели
		      $query = $mysqli->query("SELECT * FROM timesheet WHERE timesheet_id='" . $hrs_id . "'");
	  		  $row = $query->fetch_array(MYSQLI_ASSOC);
	  		  
	  		  $wedate = wedate($date);
	  		  $query = $mysqli->query("SELECT wedate_closets FROM wedate WHERE wedate_date='" . $wedate . "'");
	  		  $rowweclose = $query->fetch_array(MYSQLI_ASSOC);
	  		   echo($rowweclose['wedate_closets']*1); 
	  		  
	  		  if (wedate($row['timesheet_date']) < wedate($row['timesheet_dateCorr'])) {$wedate = wedate($row['timesheet_dateCorr']);} else {$wedate = wedate($row['timesheet_date']);}
	  		  $query = $mysqli->query("SELECT wedate_closets FROM wedate WHERE wedate_date='" . $wedate . "'");
	  		  $row = $query->fetch_array(MYSQLI_ASSOC);
	  		  
	  		  //если тек.дата(дата корр.) раньше чем текущий WE и неделя закрыта, тогда дата корр. ставится как WE+1 день
	  		  $querydateCorr = $mysqli->query("SELECT * FROM wedate WHERE wedate_date>='" . wedate($dateCorr) . "'");
	  		  while($rowdateCorr = $querydateCorr->fetch_array(MYSQLI_ASSOC)) {
            
	  		    if ($rowdateCorr['wedate_closets']) {
	  		      //echo("good QRY - " . strtotime($rowdateCorr['wedate_date']) . "... "); 
	  		      $dateCorr = date("Y-m-d", strtotime($rowdateCorr['wedate_date'])+1*60*60*24);
	  		    } else {
	  		      break;
	  		    }
	  		  }
	  		  
	  		  if ($row['wedate_closets'] == 0) {
    	  		if ($nextkeyUPDATE) {
    	  		  $query = $mysqli->query("SELECT * FROM timesheet WHERE timesheet_id=" . $hrs_id);
    	  		  $row = $query->fetch_array(MYSQLI_ASSOC);
    	  		  if ($row['timesheet_hours'] != $value && $value != 0) {
      	  		  $query = $mysqli->query("UPDATE timesheet SET timesheet_upd=1, timesheet_hours=" . $value . " WHERE timesheet_id=" . $hrs_id);
      	  		  //if ($query) {echo " UPDATE GOOD hrs=" . $value . " ";} else {echo " UPDATE NOT GOOD ";}
      	  		  
      	  		  //устанавливается признак обновления на все часы по документу, для последующего обнуления даты проверки при синхронизации
      	  		  $query = $mysqli->query("UPDATE timesheet SET timesheet_upd=1 WHERE doc_id=" . $row['doc_id']);
    	  		  }
    	  		  if ($value == 0) {// если 0 то ставим признак на удаление
      	  		  $query = $mysqli->query("UPDATE timesheet SET timesheet_del=1 WHERE timesheet_id=" . $hrs_id);
    	  		  }
    	  		} else {
    	  		  $query = $mysqli->query("INSERT INTO timesheet (timesheet_date, project_id, hours_type_id, timesheet_hours, activity_id, timesheet_dateCorr, doc_id, weclose) 
    	  		                           VALUES ('" . $date . "', '" . $project_id . "', '" . $hourstype_id . "', '" . $value . "', '" .$activity_id . "', '" . $dateCorr . "', '" . $doc_id . "', '" . $rowweclose['wedate_closets']*1 . "')");
    	  		  //if ($query) {echo " INSERT GOOD hrs=" . $value . " ";} else {echo " INSERT NOT GOOD ";}
    				}
	  		  }
	  		  $query = $mysqli->query("DELETE FROM timesheet WHERE timesheet_hours = 0 and timesheet_upd = 0 and timesheet_del = 0 and doc_id=" . $doc_id);
	  		 // if($query){alert("good del");}
  				$nextkeyUPDATE = false;
	  	  }
		  }
		echo "<script> alert('Данные успешно сохранены.'); </script>";
	  echo "<script> location ='/php/doc/timesheet.php?doc_id=". $_REQUEST['00-doc_id'] . "';</script>";
		exit;
	}
		if (isset($_REQUEST['deleterowbtn'])) {
			foreach($_REQUEST as $key=>$value){
				if (substr($key,0,2) == "id") {
					$query = $mysqli->query("DELETE FROM timesheet WHERE timesheet_id=" . $value);
				}
			}
			$result['reload']=true;
			echo(json_encode($result));
			exit;
		}
  }
// ------------------------------------------------ действия для timesheet.php КОНЕЦ  

// ------------------------------------------------ действия для lk.php НАЧАЛО
	if ($_REQUEST['site']=="lk.php"){
		if ($_REQUEST['saveresourcebtn']){
		  if (isset($_REQUEST['showcellphone'])) {
		    $showcellphone = -1;
		  } else {
		    $showcellphone = 0;
		  }
			$query = $mysqli->query("UPDATE lst_resource SET resource_lastnameRus='" . $_REQUEST['LastNameRus'] . "', resource_firstnameRus='" . $_REQUEST['FirstNameRus'] . "', resource_lastnameEng='" . 
			  $_REQUEST['LastNameEng'] . "',  resource_firstnameEng='" . $_REQUEST['FirstNameEng'] . "',  resource_email='" . $_REQUEST['Email'] . "', resource_phone='" . $_REQUEST['Phone'] . 
			  "',  resource_phonecell='" . $_REQUEST['CellPhone'] . "',  resource_showPhoneCell='" . $showcellphone . "',  resource_upd=1 WHERE resource_id='" . $_SESSION['resourceid'] . "'");

			echo "<script>alert('Данные успешно сохранены.');</script>"; 
    	echo "<script> location ='/php/lk.php';</script>";
			exit;
		}
		if ($_REQUEST['saveuserbtn']){
		  $query = $mysqli->query("UPDATE lst_user SET theme_id=" . $_REQUEST['theme_id'] . " WHERE user_id='" . $_SESSION['userid'] . "'");

			if (!$_REQUEST['passwordnew1']) {
				echo "<script> alert('Данные не изменены. Новый пароль пустой. Но цветовая тема поменялась:)'); </script>"; 
	    	echo "<script> location ='/php/lk.php'; </script>";
				exit;
			} else {
				$query = $mysqli->query("UPDATE lst_user SET user_password='" . md5($_REQUEST['passwordnew1']) . "', theme_id=" . $_REQUEST['theme_id'] . " WHERE user_id='" . $_SESSION['userid'] . "'");
				echo "<script>alert('Данные успешно сохранены.');</script>"; 
	    	echo "<script> location ='/php/lk.php';</script>";
				exit;
			}
		}
	}
// ------------------------------------------------ действия для lk.php КОНЕЦ

// ------------------------------------------------ действия для operdni.php НАЧАЛО
	if ($_REQUEST['site']=="operdni.php"){
		if (isset($_REQUEST['savebtn'])){
			$query = $mysqli->query("UPDATE wedate SET wedate_closets=0");
			foreach($_REQUEST as $key=>$value){
				if (substr($key,0,8) == "wedateid"){
					$key=substr($key,8);
					$query = $mysqli->query("UPDATE wedate SET wedate_closets=1 WHERE wedate_id='" . $key . "'");
				}
			}
			echo "<script>alert('Данные сохранены.');</script>"; 
    	echo "<script> location ='/php/menu/operdni.php';</script>";
			exit;
		}
		if (isset($_REQUEST['addbtn']) && $_REQUEST['datestart']){
			$query = $mysqli->query("INSERT INTO wedate (wedate_date) VALUES ('". $_REQUEST['datestart'] ."')");

			echo "<script> alert('Неделя добавлена.'); </script>"; 
    	echo "<script> location ='/php/menu/operdni.php'; </script>";
			exit;
		}
	}
// ------------------------------------------------ действия для operdni.php КОНЕЦ

// ------------------------------------------------ действия для feedback.php НАЧАЛО
	if ($_REQUEST['site']=="feedback.php"){
		if (isset($_REQUEST['savebtn'])){
			$query = $mysqli->query("INSERT INTO feedback (feedback_date, resource_id, feedback_desc) VALUES ('" . Date("Y-m-d") . "'," . $_SESSION['resourceid'] . ",'" . $_REQUEST['feedbacktext'] . "')");
			echo "<script>alert('Спасибо за отзыв!.');</script>"; 
    	echo "<script> location ='/php/feedback.php';</script>";
			exit;
		}
	}
// ------------------------------------------------ действия для feedback.php КОНЕЦ

// ------------------------------------------------ действия для feedbacklist_edit.php НАЧАЛО
	if ($_REQUEST['site']=="feedbacklist_edit.php"){
		if (isset($_REQUEST['savebtn'])){
			$query = $mysqli->query("UPDATE feedback SET feedback_comment='" . $_REQUEST['feedback_comment'] . "', status_id='" . $_REQUEST['status_id'] . "' WHERE feedback_id=" . $_REQUEST['feedback_id']);
			
			if ($query) {
			  echo "<script>alert('Изменения сохранены.');</script>";
			} else {
			  echo "<script>alert('Ошибка при сохранении.');</script>";
			}
			 
    	echo "<script> location ='/php/feedback/edit.php?feedback_id=" . $_REQUEST['feedback_id'] . "';</script>";
			exit;
		}
	}
// ------------------------------------------------ действия для feedback.php КОНЕЦ
  
  $query->free(); /* очищаем результаты выборки */
	$mysqli->close(); /* закрываем подключение */
?>