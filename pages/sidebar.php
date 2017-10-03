				<div class="mdl-grid mdl-grid--no-spacing">
					<div class="mdl-components mdl-cell mdl-cell--12-col">
						<aside class="aside mdl-components__nav">
							<div class="option">
								<div class="option-icon">
									<div class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect" id='contacts' onclick="location.href='./showpage.php'">
										<i class="material-icons">contacts</i>
									</div>
								</div>
								<div class='mdl-tooltip mdl-tooltip--right' for='contacts'>Контакты</div>
							</div>
							<div class="option">
								<div class="option-icon">
									<div class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect" id="restore" onclick="location.href='./logpage.php'">
										<i class="material-icons">restore</i>
									</div>
								</div>
								<div class='mdl-tooltip mdl-tooltip--right' for='restore'>Действия</div>
							</div>
							<div class="option">
								<div class="option-icon">
									<div class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect" id="supervisor_account" onclick="location.href='./users.php'">
										<i class="material-icons">supervisor_account</i>
									</div>
								</div>
								<div class='mdl-tooltip mdl-tooltip--right' for='supervisor_account'>Администраторы</div>
							</div>
							<div class="option">
								<div class="option-icon">
									<div class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect" id="exit_to_app" onclick="logout()">
										<i class="material-icons">exit_to_app</i>
									</div>
								</div>
								<div class='mdl-tooltip mdl-tooltip--right' for='exit_to_app'>Выйти</div>
							</div>
						</aside>
						<main id="buttonsContainer" class="mdl-components__pages">