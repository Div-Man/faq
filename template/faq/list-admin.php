<div class="list-admin">
	<p>Список администраторов</p>
	<ul>
		<?php
			foreach($listAdmin as $admin) {
				echo '<li>'.$admin['login'].'
					<a href="?interface-admin=1&list-admin=1&update-pass='.$admin['login'].'">Изменить пароль</a>
					<a href="?interface-admin=1&del-id='.$admin['id'].'">Удалить</a>';
				echo '</li>';
			}
		?>
	</ul>
</div>