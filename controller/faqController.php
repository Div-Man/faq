<?php

class FaqController {
	private $model = null;
	
	function __construct ($db) {
		include 'model/faq.php';
		$this->model = new Faq($db);
	}
	
	private function render($template = null, $params = null) {
		$fileTemplate = 'template/'.$template;
		if (is_file($fileTemplate)) {
			ob_start();
			if (count($params) > 0) {
				extract($params);
			}
			include $fileTemplate;
			return ob_get_clean();
		}
	}
	

	function getFormInterfaceAdmin($id) {
		$listAdmin = $this->model->getListAdmin();
		$cat = $this->model->getCategory();
		$countQuest = $this->model->getCategoryAndCountQuestion();
		$countShowQuest = $this->model->getCategoryAndCountShowQuestion();
		$countAnswerQuest = $this->model->getCategoryAndCountAnswerQuestion();
		$showQuest = $this->model->showQuestion($id);
		$questionNoAnswer = $this->model->showQuestionNoAnswer();
		echo $this->render('faq/interface-admin.php', [
														'listAdmin' => $listAdmin, 
														'cat' => $cat, 
														'countQuest' => $countQuest,
														'countShowQuest' => $countShowQuest,
														'countAnswerQuest' => $countAnswerQuest,
														'showQuest' => $showQuest,
														'questionNoAnswer' => $questionNoAnswer
													]);
	}
	
	
	function getDeleteUser($id){
		$del = $this->modelUser->deleteUser($id);
	}
	
	function getDeleteQuestion($delId) {
		$this->model->deleteQuestion($delId);
	}
	
	function getExit() {
		$this->modelUser->adminExit();
	}
	
	function getDeleteCategoryAndQuestion($id) {
		$del = $this->model->deleteCategoryAndQuestion($id);
	}
	
	function getHiddenQuestion($id) {
		$hidden = $this->model->hiddenQuestion($id);
	}
	
	function getShowQuestion($id) {
		$showQuest = $this->model->questionShow($id);
	}
	
	function getShowCategory() {
		$cat = $this->model->getCategory();
		$faq = $this->model->getFaq($_GET['cat']);
		echo $this->render('faq/index.php', ['cat' => $cat, 'faq' => $faq]);
	}
	
	function getAddQuestion($name, $email, $text, $cat) {
		if($cat == 0) {
			die('<p>Выберите категорию</p>');
		}
		if(!empty($name) && !empty($email) && !empty($text) && !empty($cat)) {
			$question = $this->model->addQuestion($name, $email, $text, $cat);
		}
		else {
			die('<p>Заполните все поля</p>');
		}
	}
	
	function getEditQuestion($questionId, $question) {
		if(empty($question)) {
			echo '<p>Введите вопрос</p>';
		}
		else {
			$this->model->editQuestion($questionId, $question);
		}
	}
	
	function getEditAnswer($questionId, $answer) {
		if(empty($answer)) {
			echo '<p>Введите ответ</p>';
		}
		else {
			$this->model->editAnswer($questionId, $answer);
		}
	}
	
	function getEditCategory($category, $id) {
		if($category == 0) {
			echo '<p>Выберите категорию</p>';
			return false;
		}
		else {
			$this->model->editCategory($category, $id);
		}
		
	}
	
	
}
