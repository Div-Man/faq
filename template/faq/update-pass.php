

<form method="POST">
	<p>Изменение пароля у пользователя <?php echo $_GET['update-pass'];?></p>
	<input type="hidden" name="name-admin" value="<?php echo $_GET['update-pass']?>">
	<input type="password" name="new-password">
	<input type="submit" name="newPass" value="Применить">
</form>


	