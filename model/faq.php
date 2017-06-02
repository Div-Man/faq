<?php

class Faq {
	private $db = null;
	function __construct ($db) {
		$this->db = $db;
	}

	function getListAdmin() {
		$sql = "SELECT login, password, id FROM users";
		$allUser = $this->db->query($sql);
		$allUser->setFetchMode(PDO::FETCH_ASSOC);
		return $allUser->fetchAll();
	}
	
	function deleteQuestion($delId) {
		$sql = "DELETE FROM question WHERE id = :id";
		$del = $this->db->prepare($sql);
		$del->bindValue(':id', $delId, PDO::PARAM_INT);
		$del->execute();
		header('Location: ?interface-admin=1&showQuestion='.$_GET['showQuestion']);
	}
	
	function deleteCategoryAndQuestion($id) {
		$delCategory = "DELETE FROM category WHERE id = :id";
		$delCat = $this->db->prepare($delCategory);
		$delCat->bindValue(':id', $id, PDO::PARAM_INT);
		
		$delQuestion = "DELETE FROM question WHERE cat_id = :id";
		$delQuest = $this->db->prepare($delQuestion);
		$delQuest->bindValue(':id', $id, PDO::PARAM_INT);
		
		$delCat->execute();
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
			FROM question INNER JOIN category WHERE category.id = '".$id."'  AND question.cat_id = '".$id."' GROUP BY question.name";
			
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
									question.user_email FROM question INNER JOIN 
									category 
				WHERE question.answer = '' AND category.id = question.cat_id GROUP BY question.name ORDER BY question.data";
			
		$getQuestionNoAnswer = $this->db->query($sql);
		$getQuestionNoAnswer->setFetchMode(PDO::FETCH_ASSOC);
		return $getQuestionNoAnswer->fetchAll();	
	}
	
	function hiddenQuestion($id) {
		$sql = "UPDATE question SET `status` = 2 WHERE id = :id";
		$hidd = $this->db->prepare($sql);
		$hidd->bindValue(':id', $id, PDO::PARAM_INT);
		$hidd->execute();
	}
	
	function questionShow($id) {
		$sql = "UPDATE question SET `status` = 1 WHERE id = :id";
		$show = $this->db->prepare($sql);
		$show->bindValue(':id', $id, PDO::PARAM_INT);
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
		$input_email = filter_var($email, FILTER_VALIDATE_EMAIL);
		if($input_email === false) {
			echo 'Введи правильно email';
			die();
		}
		
		$date = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO question (name, cat_id, user_name, data, status, answer, user_email) VALUES(
																			:name,
																			:cat,
																			:userName,
																			:date,
																			'0',
																			'". null ."',
																			:email
																			)";
		
		$sth = $this->db->prepare($sql);
		$sth->bindValue(':name', $text, PDO::PARAM_STR);
		$sth->bindValue(':cat', $cat, PDO::PARAM_INT);
		$sth->bindValue(':userName', $name, PDO::PARAM_STR);
		$sth->bindValue(':date', $date, PDO::PARAM_INT);
		$sth->bindValue(':email', $input_email, PDO::PARAM_STR);
		$sth->execute();
		die ('<p>Вопрос появится, после того, как на него ответит администратор</p>');															
	}
	
	function editQuestion($questionId, $question) {
		$sql = "UPDATE question SET `name` = :setQuestion WHERE id = :idQuest";
		$setQuestion = $this->db->prepare($sql);
		$setQuestion->bindValue(':idQuest', $questionId, PDO::PARAM_INT);
		$setQuestion->bindValue(':setQuestion', $question, PDO::PARAM_STR);
		$setQuestion->execute();
		if($_GET['showQuestion']) {
			header('Location: ?interface-admin=1&showQuestion='. $_GET['showQuestion']);
		}
		if($_GET['all-question']) {
			header('Location: ?interface-admin=1&all-question=1');
		}
	}
	
	function editAnswer($questionId, $answer) {
		$sql = "UPDATE question SET `answer` = :setAnswer WHERE id = :idQuest";
		$sql2 = "UPDATE question SET `status` = '1' WHERE id = :idQuest";
			
		$setAnswer = $this->db->prepare($sql);
		$setAnswer->bindValue(':idQuest', $questionId, PDO::PARAM_INT);
		$setAnswer->bindValue(':setAnswer', $answer, PDO::PARAM_INT);
		$setAnswer->execute();
			
		$setStatus = $this->db->prepare($sql2);
		$setStatus->bindValue(':idQuest', $questionId, PDO::PARAM_INT);
		$setStatus->execute();
			
		if($_GET['showQuestion']) {
			header('Location: ?interface-admin=1&showQuestion='. $_GET['showQuestion']);
		}
		if($_GET['all-question']) {
			header('Location: ?interface-admin=1&all-question=1');
		}
	}
	
	function editCategory($category, $id) {
		$sql = "UPDATE question SET `cat_id` = :category WHERE id = :id";
		$setCategory = $this->db->prepare($sql);
		$setCategory->bindValue(':category', $category, PDO::PARAM_INT);
		$setCategory->bindValue(':id', $id, PDO::PARAM_INT);
		$setCategory->execute();
		header('Location: ?interface-admin=1&showQuestion='. $_GET['showQuestion']);
	}
}
