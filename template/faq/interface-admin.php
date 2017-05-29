<?php
	if(!empty($_SESSION['user'])) {
		echo '<p>Добро пожаловать ' .$_SESSION['user'].' <a href="?exit=1">Выход</a></p>';
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		 <link rel="stylesheet" href="style-admin.css">
		<style>
		</style>
	</head>
	<body>
		<div class="content clearfix">
			<div class="action-menu">
				<ul>
					<li><a href="?interface-admin=1&create-admin=1">Создать администратора</a></li>
					<li><a href="?interface-admin=1&list-admin=1">Список администраторов</a></li>
					<?php
						if(!empty($_GET['list-category'])) {
							echo '<li>Список тем</li>';
						}
						else {
							echo '<li><a href="?interface-admin=1&list-category=1">Список тем</a></li>';
						}
					?>
					
					<li><a href="?interface-admin=1&new-category=1">Создать новую категорию</a></li>
					<li><a href="?interface-admin=1&all-question=1">Вопросы без ответов</a></li>
				</ul>
			</div>
			
			<?php 
				if(!empty($_GET['create-admin'])) {
					require_once 'create-admin.php';
				}
				
				if(!empty($_GET['list-admin'])) {
					require_once 'list-admin.php';
				}
				
				if(!empty($_GET['update-pass'])) {
					require_once 'update-pass.php';
				}
				
				if(!empty($_GET['list-category'])) {
					require_once 'list-category.php';
				}
				if(!empty($_GET['new-category'])) {
					require_once 'new-category.php';
				}
				if(!empty($_GET['showQuestion'])) {
					require_once 'show-question.php';
				}
				if(!empty($_GET['all-question'])) {
					require_once 'all-question.php';
				}
			?>
			
		</div>
	</body>
</html>	