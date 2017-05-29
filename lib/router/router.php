<?php

include 'controller/faqController.php';

$faq = new faqController($db);


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if(!empty($_GET['del-id'])) {
		$faq->getDeleteUser($_GET['del-id']);
	}
	
	if(!empty($_GET['del-question'])) {
		$faq->getDeleteQuestion($_GET['del-question']);
	}
	
	if(!empty($_GET['exit'])) {
		$faq->getExit();
	}
	
	if(!empty($_GET['interface-admin']) && !empty($_GET['list-category']) &&  !empty($_GET['delCat'])) {
		$faq->getDeleteCategoryAndQuestion($_GET['delCat']);
	}
	
	if(!empty($_GET['showQuestion']) &&  !empty($_GET['hidden'])) {
		$faq->getHiddenQuestion($_GET['hidden']);
	}
	
	if(!empty($_GET['showQuestion']) &&  !empty($_GET['show'])) {
		$faq->getShowQuestion($_GET['show']);
	}
	
	if(!empty($_SESSION['user']) && !empty($_GET['interface-admin'])) {
		$faq->getFormInterfaceAdmin($id = $_GET['showQuestion']);
		die();
	}
	if(!empty($_GET['admin'])) {
		if(!empty($_SESSION['user'])){
			header('Location: ?interface-admin=1');
		}
		if((int)$_GET['admin'] === 1) {
			$faq->getFormLogon();
		}
	}
		
	if(!empty($_SESSION['user']) || empty($_SESSION['user'])) {
		$faq->getShowCategory();
	}
	
	
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($_POST['addQuestion']) {
		if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['text'])) {
			$faq->getAddQuestion($_POST['name'], $_POST['email'], $_POST['text'], $_POST['category']);
		}
		else {
			echo 'Заполните все поля';
		}
	}
	
	if($_POST['auth']) {
		if(!empty($_POST['login']) && !empty($_POST['password'])) {
			$faq->getLogon($_POST['login'], $_POST['password']);
		}
		else {
			echo 'Заполните все поля';
		}
	}
	
	if($_POST['createAdmin']) {
		if(!empty($_POST['newAdmin']) && !empty($_POST['pass'])) {
			$faq->setNewAdmin($_POST['newAdmin'], $_POST['pass']);
		}
		else {
			echo 'Заполните все поля';
		}
	}
	
	if($_POST['newPass']){
		$faq->getUpdatePass($_POST['new-password'], $_POST['name-admin']);
	}
	
	if($_POST['createCategory']) {
		if(!empty($_POST['title'])) {
			$category = $_POST['title'];
			if(iconv_strlen($category) > 0) {
				$faq->getNewCategory($category);
				echo '<p>Категория ' . $category . ' создана</p>';
				echo '<p><a href="'.header('Location: ?interface-admin=1&list-category=1').'">Перейти в список категорий</a></p>';
			}
		}
		
		else {
			echo '<p>Введите название категории</p>';
		}
	}
	
	if($_POST['renameAuthor']) {
		$faq->getNewName($_POST['questionId'], $_POST['newName']);
	}
	
	if($_POST['renameQuestion']) {
		$faq->getEditQuestion($_POST['questionId'], $_POST['newQuestion']);
	}
	
	if($_POST['renameAnswer']) {
		$faq->getEditAnswer($_POST['questionId'], $_POST['newAnswer']);
	}
	
	if($_POST['move']) {
		$faq->getEditCategory($_POST['editCategory'], $_POST['idquestion']);
	}
}


