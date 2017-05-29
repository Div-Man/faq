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
	
	function getFormLogon() {
		echo $this->render('faq/auth.php');
	}
	
	function getLogon($login, $password) {
		$logon = $this->model->logon($login, $password);
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
	
	
	function setNewAdmin($login, $password) {
		$newAdmin = $this->model->newAdmin($login, $password);
	}
	
	function getUpdatePass($newPass, $admin) {
		$pass = $this->model->newPassword($newPass, $admin);
	}
	
	function getDeleteUser($id){
		$del = $this->model->deleteUser($id);
	}
	
	function getDeleteQuestion($delId) {
		$this->model->deleteQuestion($delId);
	}
	
	function getExit() {
		$this->model->adminExit();
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
		$question = $this->model->addQuestion($name, $email, $text, $cat);
	}
	
	function getNewCategory($title){
		$new = $this->model->newCategory($title);
	}
	
	function getNewName($questionId, $name) {
		$this->model->NewName($questionId, $name);
	}
	
	function getEditQuestion($questionId, $question) {
		$this->model->editQuestion($questionId, $question);
	}
	
	function getEditAnswer($questionId, $answer) {
		$this->model->editAnswer($questionId, $answer);
	}
	
	function getEditCategory($category, $id) {
		$this->model->editCategory($category, $id);
	}
	
	
}