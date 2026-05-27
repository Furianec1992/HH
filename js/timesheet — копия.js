
function AddRow() {
	for( var r = document.getElementsByClassName('mainrows'), row=0; row < r.length; row++) {}
		var nowdate = new Date();
		var nowday = nowdate.getDate();if (nowday < 10) {nowday = "0" + nowday;}
		var nowmonth = nowdate.getMonth() + 1;if (nowmonth < 10) {nowmonth = "0" + nowmonth;}
		var dateCorr = (nowdate.getFullYear() + "-" + nowmonth + "-" + nowday);
		
		if (row < 10) {
		  rowClass = row;
		  row = "0" + row;
		} else {
		  rowClass = row; 
		  row = row;
		}
	
		//<input type='hidden'><input>  добавлено для перехода стралочками по новым полям для равного общего кол-ва input и td 
   	$(document).ready(function () {
   		$("<tr class='mainrows' style='width: 57px'><td>" + rowClass + "</td> \
        	<td style='width: 663px'><select required style='white-space: normal;' class='mdl-textfield__input' name=" + row + "-project_id><option selected='selected' disabled></option>" + project + " </select></td> \
        	<input type='hidden'><input> \
          <td style='width: 105px'><select required class='mdl-textfield__input' name=" + row + "-activity_id><option selected='selected' disabled></option>" + activity + "</select></td> \
          <input type='hidden'><input> \
          <td style='width: 100px'><select required class='mdl-textfield__input' name=" + row + "-hourstype_id><option selected='selected' disabled></option>" + hourstype + "</select></td> \
          <input type='hidden'><input> \
					<td style='width: 69px'><input autocomplete='off' class='mdl-textfield__input r" + rowClass + " c6' lang='en' step='0.1' pattern='-?[0-9]*(\.[0-9]+)?' name=" + row + "-hrs6 onfocus='this.select (); fW ()' onblur='fW (); fS ()'></td> \
					<td style='width: 69px'><input autocomplete='off' class='mdl-textfield__input r" + rowClass + " c7' lang='en' step='0.1' pattern='-?[0-9]*(\.[0-9]+)?' name=" + row + "-hrs7 onfocus='this.select (); fW ()' onblur='fW (); fS ()'></td> \
					<td style='width: 69px'><input autocomplete='off' class='mdl-textfield__input r" + rowClass + " c1' lang='en' step='0.1' pattern='-?[0-9]*(\.[0-9]+)?' name=" + row + "-hrs1 onfocus='this.select (); fW ()' onblur='fW (); fS ()'></td> \
					<td style='width: 69px'><input autocomplete='off' class='mdl-textfield__input r" + rowClass + " c2' lang='en' step='0.1' pattern='-?[0-9]*(\.[0-9]+)?' name=" + row + "-hrs2 onfocus='this.select (); fW ()' onblur='fW (); fS ()'></td> \
					<td style='width: 69px'><input autocomplete='off' class='mdl-textfield__input r" + rowClass + " c3' lang='en' step='0.1' pattern='-?[0-9]*(\.[0-9]+)?' name=" + row + "-hrs3 onfocus='this.select (); fW ()' onblur='fW (); fS ()'></td> \
					<td style='width: 69px'><input autocomplete='off' class='mdl-textfield__input r" + rowClass + " c4' lang='en' step='0.1' pattern='-?[0-9]*(\.[0-9]+)?' name=" + row + "-hrs4 onfocus='this.select (); fW ()' onblur='fW (); fS ()'></td> \
					<td style='width: 69px'><input autocomplete='off' class='mdl-textfield__input r" + rowClass + " c5' lang='en' step='0.1' pattern='-?[0-9]*(\.[0-9]+)?' name=" + row + "-hrs5 onfocus='this.select (); fW ()' onblur='fW (); fS ()'></td> \
          <td style='width: 77px;'><input style='font-weight: bolder;' type='text' id='totalr" + rowClass + "' readonly class='mdl-textfield__input '></td> \
          <td style='width: 144px'><input class='mdl-textfield__input' type='text' disabled value=" + dateCorr + "></td> \
          </tr>").insertAfter($(".mainrows:last")); 
    });
}

function fW() { //для целых чисел (в т.ч. < 0) 
  var rows = document.getElementsByTagName('tr');
  var grandSum = 0;

  for (var row = 1; row <= rows.length - 2; row++) {
    for (var cell = document.getElementsByClassName("r" + row), curCell = 0, rowSumm = 0; curCell < cell.length; curCell++) {
      rowSumm += +cell[curCell].value; 
    }
    grandSum += rowSumm;
    document.getElementById('totalr' + row).value = rowSumm.toFixed(1);
  }
  
  for (var col = 1; col <= 7; col++) {
    for (var cell = document.getElementsByClassName("c" + col), curCell = 0, colSumm = 0; curCell < cell.length; curCell++) {
      colSumm += +cell[curCell].value;
    }
    document.getElementById('totalc' + col).value = colSumm.toFixed(1);
  }
  
  document.getElementById('totalctotal').value = grandSum.toFixed(1);

	TIM = setTimeout(fW, 10);
}

function fS() {
	clearTimeout(TIM);
}