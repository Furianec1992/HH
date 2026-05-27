function AjaxRequest(button, form_id, url) {
	
	var field = $("form").find("input,select").filter('[required]');//поля обязательные 
	var error = 0;
  for(var i = 0; i < field.length; i++){ // если поле присутствует в списке обязательных
    if(!$(field[i]).val()){// если в поле пустое
      // $(field[i]).css('border', '1px double #d50000');// устанавливаем рамку красного цвета
      error = 1;
	  } else {
			// $(field[i]).css('border-bottom','1px solid rgba(0,0,0,.12)');// устанавливаем рамку обычного цвета
    }                  
  }

 if (error === 0) {
	 jQuery.ajax({
		url:       url, //Адрес подгружаемой страницы
  	type:      "POST", //Тип запроса
    dataType:  "json", //Тип данных
  	data:      jQuery("#"+form_id).serialize() + '&' + button.name + '=' + button.value + '&handler=AJAX', 
  	success: function(response) { //Если все нормально
  		if(response.text){alert(response.text);}
  		if(response.href){location.href=response.href;}
  		if(response.reload){location.reload();}
  	},
  	error: function(response) { //Если ошибка
  		alert('ERROR AJAX в js.js');
		}
	 });
	} else if (error === 1){
		alert("Не заполнены обязательные поля.");
	}
}

