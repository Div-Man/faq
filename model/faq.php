<?php

class Faq {
	private $db = null;
	
	function __construct ($db) {
		$this->db = $db;
	}
	
	function logon($l, $p) {
		
		$login = strip_tags(trim($l));
		$password = strip_tags(trim($p));
		
		
		$user = "SELECT `login`, `password` FROM users WHERE login = '" . $login ."' AND password = '". $password ."'";
		
		$resUser = $this->db->prepare($user);
		$resUser->execute();
		$resUser2 = $resUser->fetchAll();
		
		if(count($resUser2) === 0){
			echo 'Неверный логин или пароль';
		}
		
		else {
			$_SESSION['user'] = $login;
			header('Location: ?interface-admin=1');
		}
		
	}
	
	function getListAdmin() {
		$sql = "SELECT login, password, id FROM users";
		$allUser = $this->db->query($sql);
		$allUser->setFetchMode(PDO::FETCH_ASSOC);
		return $allUser->fetchAll();
	}
	
	function newAdmin($l, $p) {
		$login = strip_tags(trim($l));
		$password = strip_tags(trim($p));
		
		$userExists = "SELECT login FROM users WHERE login = '".$login."'";
		$queryUser = $this->db->query($userExists);
		$queryUser->setFetchMode(PDO::FETCH_ASSOC);
		
		if((count($queryUser->fetchAll()) > 0)) {
			echo 'Такой пользователь уже существует';
			die();
		}
		
		else {
			$newAdmin = "INSERT INTO users(login, password) VALUES('".$login."', '".$password."')";
			$newUserPrepare = $this->db->prepare($newAdmin);
			$newUserPrepare->execute();
			echo 'Администратор добавлен';
		}
	}
	
	function newPassword($newPass, $admin) {
		$sql = "UPDATE users SET `password` = '".$newPass."' WHERE login = '".$admin."'";
		$setPassword = $this->db->prepare($sql);
		$setPassword->execute();
		echo 'Пароль изменён';
		
	}
	
	function deleteUser($id) {
		$sql = "DELETE FROM users WHERE id = '".$id."'";
		$del = $this->db->prepare($sql);
		$del->execute();
		header('Location: ?interface-admin=1&list-admin=1');
	}
	
	function deleteQuestion($delId) {
		$sql = "DELETE FROM question WHERE id = '".$delId."'";
		$del = $this->db->prepare($sql);
		$del->execute();
	}
	
	function adminExit() {
		unset($_SESSION['user']);
		session_destroy();
	}
	
	function deleteCategoryAndQuestion($id) {
		$delCategory = "DELETE FROM category WHERE id = '".$id."'";
		$delQuestion = "DELETE FROM question WHERE cat_id = '".$id."'";
		$delCat = $this->db->prepare($delCategory);
		$delCat->execute();
		$delQuest = $this->db->prepare($delQuestion);
		$delQuest->execute();
		header('Location: ?interface-admin=1&list-category=1');
	}
	
	function showQuestion($id) {
		$sql = "SELECT question.name, category.name AS cat_name, 
			question.id,
			question.user_name,
			question.data, 
			question.status, 
			question.answer, 
			question.user_email  
			FROM question, category WHERE category.id = '".$id."'  AND question.cat_id = '".$id."' GROUP BY question.name";
			
		$getQuestion = $this->db->query($sql);
		$getQuestion->setFetchMode(PDO::FETCH_ASSOC);
		return $getQuestion->fetchAll();	
	}
	
	function showQuestionNoAnswer() {
		$sql = "SELECT question.name, category.name AS cat_name, 
									question.id, 
									question.user_name, 
									question.data, 
									question.status, 
									question.answer, 
									question.user_email FROM question, 
									category 
				WHERE question.answer = '' AND category.id = question.cat_id GROUP BY question.name ORDER BY question.data";
			
		$getQuestionNoAnswer = $this->db->query($sql);
		$getQuestionNoAnswer->setFetchMode(PDO::FETCH_ASSOC);
		return $getQuestionNoAnswer->fetchAll();	
	}
	
	function hiddenQuestion($id) {
		$sql = "UPDATE question SET `status` = 2 WHERE id = ".$id;
		$hidd = $this->db->prepare($sql);
		$hidd->execute();
	}
	
	function questionShow($id) {
		$sql = "UPDATE question SET `status` = 1 WHERE id = ".$id;
		$show = $this->db->prepare($sql);
		$show->execute();
	}
	
	function getCategory() {
		$allCategory = 'SELECT id, name FROM category';
		$result = $this->db->query($allCategory);
		$result->setFetchMode(PDO::FETCH_ASSOC);
		return $result->fetchAll();
	}
	
	function getCategoryAndCountQuestion() {
		$allCategory = 'SELECT category.name, category.id, count(question.name) AS count_question FROM category LEFT JOIN question ON category.id = question.cat_id GROUP BY category.name ORDER BY category.id';
		$result2 = $this->db->query($allCategory);
		$result2->setFetchMode(PDO::FETCH_ASSOC);
		return $result2->fetchAll();
	}
	
	function getCategoryAndCountShowQuestion() {
		$allCategory = 'SELECT category.name, count(question.status) AS count_show FROM category LEFT JOIN question ON category.id = question.cat_id  AND question.status = 1 GROUP BY category.name ORDER BY category.id';
		$result2 = $this->db->query($allCategory);
		$result2->setFetchMode(PDO::FETCH_ASSOC);
		return $result2->fetchAll();
	}
	
	function getCategoryAndCountAnswerQuestion() {
		$allCategory = 'SELECT category.name, count(question.answer) AS count_answer FROM category LEFT JOIN question ON category.id = question.cat_id  AND question.answer = "" GROUP BY category.name ORDER BY category.id';
		$result2 = $this->db->query($allCategory);
		$result2->setFetchMode(PDO::FETCH_ASSOC);
		return $result2->fetchAll();
	}
	
	
	function getFaq($cat = null) {
		if(!$cat) {
			$allFaq = 'SELECT * FROM question';
		}
		else {
			$allFaq = 'SELECT * FROM question WHERE cat_id =' .$cat;
		}
		
		$resultFaq = $this->db->query($allFaq);
		$resultFaq->setFetchMode(PDO::FETCH_ASSOC);
		return $resultFaq->fetchAll();
	}
	
	function addQuestion($name, $email, $text, $cat) {
		
		$dataQuestion = [];
		
		$input_name = trim(htmlspecialchars(strip_tags($name)));
		$dataQuestion[]=$input_name;
		
		$input_email = filter_var($email, FILTER_VALIDATE_EMAIL);
		if($input_email === false) {
			echo 'Введи правильно email';
			die();
		}
		$dataQuestion[]=$input_email;
		
		$input_text = trim(htmlspecialchars(strip_tags($text)));
		$dataQuestion[]=$input_text;
		
		if((int)$cat > 0) {
			$input_cat = (int)$cat;
			$dataQuestion[] = $input_cat;
		}
		
		else {
			echo 'Выберите категорию';
		}
	
		if(count($dataQuestion) === 4) {
			$date = date("Y-m-d H:i:s");
			$sql = "INSERT INTO question (name, cat_id, user_name, data, status, answer, user_email) VALUES(
																			'".$dataQuestion[2]."',
																			'".$dataQuestion[3]."',
																			'".$dataQuestion[0]."',
																			'".$date."',
																			'0',
																			'". null ."',
																			'".$dataQuestion[1]."'
																			)";
			$newQuestion = $this->db->prepare($sql);
			$newQuestion->execute();
			echo 'Вопрос появится, после того, как на него ответит администратор';															
		}
	}
	
	function newCategory($name) {
		$title = trim(htmlspecialchars(strip_tags($name)));
		$sql = "INSERT INTO category(name) VALUES('".$title."')";
		$newCat = $this->db->prepare($sql);
		$newCat->execute();
	}
	
	function newName($questionId, $name) {
		if(empty($name)) {
			echo '<p>Введите имя</p>';
		}
		else {
			$idQuest = trim(htmlspecialchars(strip_tags($questionId)));
			$setRename = trim(htmlspecialchars(strip_tags($name)));
		
			$sql = "UPDATE question SET `user_name` = '".$setRename."' WHERE id = '".$idQuest."'";
			$setName = $this->db->prepare($sql);
			$setName->execute();
			header('Location: ?interface-admin=1&showQuestion='. $_GET['showQuestion']);
		}
	}
	
	function editQuestion($questionId, $question) {
		if(empty($question)) {
			echo '<p>Введите вопрос</p>';
		}
		else {
			$idQuest = trim(htmlspecialchars(strip_tags($questionId)));
			$setQuestion = trim(htmlspecialchars(strip_tags($question)));
		
			$sql = "UPDATE question SET `name` = '".$setQuestion."' WHERE id = '".$idQuest."'";
			
			$setQuestion = $this->db->prepare($sql);
			$setQuestion->execute();
			header('Location: ?interface-admin=1&showQuestion='. $_GET['showQuestion']);
		}
	}
	
	function editAnswer($questionId, $answer) {
		if(empty($answer)) {
			echo '<p>Введите ответ</p>';
		}
		else {
			$idQuest = trim(htmlspecialchars(strip_tags($questionId)));
			$setAnswer = trim(htmlspecialchars(strip_tags($answer)));
		
			$sql = "UPDATE question SET `answer` = '".$setAnswer."' WHERE id = '".$idQuest."'";
			$sql2 = "UPDATE question SET `status` = '1' WHERE id = '".$idQuest."'";
			
			
			$setAnswer = $this->db->prepare($sql);
			$setAnswer->execute();
			
			$setStatus = $this->db->prepare($sql2);
			$setStatus->execute();
			
			header('Location: ?interface-admin=1&showQuestion='. $_GET['showQuestion']);
		}
	}
	
	function editCategory($category, $id) {
		if($category == 0) {
			echo '<p>Выберите категорию</p>';
			return false;
		}
		
		$sql = "UPDATE question SET `cat_id` = '".$category."' WHERE id = '".$id."'";
		$setCategory = $this->db->prepare($sql);
		$setCategory->execute();
		header('Location: ?interface-admin=1&showQuestion='. $_GET['showQuestion']);
	}
}