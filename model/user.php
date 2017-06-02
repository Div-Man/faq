<?php
class User {
	private $db = null;
	function __construct ($db) {
		$this->db = $db;
	}
	
	function logon($l, $p) {
		$user = "SELECT `login`, `password` FROM users WHERE login = :login AND password = :password";
		$resUser = $this->db->prepare($user);
		$resUser->bindValue(':login', trim($l), PDO::PARAM_STR);
		$resUser->bindValue(':password', trim($p), PDO::PARAM_STR);
		
		$resUser->execute();
		$resUser2 = $resUser->fetchAll();
		
		if(count($resUser2) === 0){
			die('Неверный логин или пароль');
		}
		else {
			$_SESSION['user'] = $l;
			header('Location: ?interface-admin=1');
		}
	}
	
	function newAdmin($login, $password) {
		$userExists = "SELECT login FROM users WHERE login = '".$login."'";
		$queryUser = $this->db->query($userExists);
		$queryUser->setFetchMode(PDO::FETCH_ASSOC);
		
		if((count($queryUser->fetchAll()) > 0)) {
			echo 'Такой пользователь уже существует';
			die();
		}
		else {
			$newAdmin = "INSERT INTO users(login, password) VALUES(:login, :password)";
			$newUserPrepare = $this->db->prepare($newAdmin);
			$newUserPrepare->bindValue(':login', trim($login), PDO::PARAM_STR);
			$newUserPrepare->bindValue(':password', trim($password), PDO::PARAM_STR);
			$newUserPrepare->execute();
			echo 'Администратор добавлен';
		}
	}
	
	function newCategory($name) {
		$sql = "INSERT INTO category(name) VALUES(:title)";
		$newCat = $this->db->prepare($sql);
		$newCat->bindValue(':title', $name, PDO::PARAM_STR);
		$newCat->execute();
		header('Location: ?interface-admin=1&list-category=1');
	}
	
	function newPassword($newPass, $admin) {
		$sql = "UPDATE users SET `password` = :newPass WHERE login = :admin";
		$setPassword = $this->db->prepare($sql);
		$setPassword->bindValue(':newPass', trim($newPass), PDO::PARAM_STR);
		$setPassword->bindValue(':admin', trim($admin), PDO::PARAM_STR);
		$setPassword->execute();
		echo 'Пароль изменён';
	}
	
	function deleteUser($id) {
		$sql = "DELETE FROM users WHERE id = :id";
		$del = $this->db->prepare($sql);
		$del->bindValue(':id', $id, PDO::PARAM_INT);
		$del->execute();
		header('Location: ?interface-admin=1&list-admin=1');
	}
	
	function adminExit() {
		unset($_SESSION['user']);
		session_destroy();
	}
	
	function newName($questionId, $name) {
		$sth = $this->db->prepare("UPDATE question SET `user_name` = :setRename WHERE id = :idQuest");
		$sth->bindValue(':setRename', $name, PDO::PARAM_STR);
		$sth->bindValue(':idQuest', $questionId, PDO::PARAM_INT);
		$sth->execute();
		if($_GET['showQuestion']) {
			header('Location: ?interface-admin=1&showQuestion='. $_GET['showQuestion']);
		}
		if($_GET['all-question']) {
			header('Location: ?interface-admin=1&all-question=1');
		}
	}
}
