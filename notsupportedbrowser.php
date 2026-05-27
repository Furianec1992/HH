	<html>
	<head>
		<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/head.html"); ?>
	</head>
	<body>
		<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		  <header class="mdl-layout__header">
		    <div class="mdl-layout__header-row">
		      <span class="mdl-layout-title">База данных "Табель"</span>
		    </div>
		  </header>

		  <main class="mdl-layout__content">
		    <div class="mdl-grid">
		    	<div class="mdl-cell mdl-cell--4-col"></div>
		    	<div class="mdl-cell mdl-cell--4-col mdl-cell mdl-cell--4-col mdl-card mdl-shadow--2dp">
		    		<div class="mdl-card__title">
					    <h2 class="mdl-card__title-text">Ваш броузер не поддерживается!</h2>
					  </div>
					  <div class="mdl-card__supporting-text">
							<form action="/php/check.php" method="POST" class="form" name="login" id="login">
								<ul class="demo-list-item mdl-list">
								  <li class="mdl-list__item">
                    <p>
                      На текущий момент поддерживаются только Google Chrome и Mozilla Firefox
                      <br>
                      Скачать последние версии можно с сайтов разработчиков по ссылкам:
                      <br>
                      <a href="https://www.google.ru/chrome/browser/desktop/">Google Chrome</a> или <a href="https://www.mozilla.org/ru/firefox/new/">Mozilla Firefox</a>
                    </p>
								  </li>
								 </lu>
							</form>
						</div>
					</div>
		    </div>
		  </main>
		 
			<footer class="mdl-mini-footer">
			  <div class="mdl-mini-footer__left-section">
			    <div class="mdl-logo">Сахалинские Нефтегазовые Технологии ООО</div>
			  </div>
			</footer>
		</div>
		
		<?php include ($_SERVER["DOCUMENT_ROOT"]."/part/bodyscripts.php"); ?>
	</body>
	</html>