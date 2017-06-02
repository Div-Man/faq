<?php

include 'controller/faqController.php';
include 'controller/userController.php';

$faq = new faqController($db);
$user = new UserController($db);


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
		$user->formLogon();
	}
	
	
	if(!empty($_SESSION['user']) || empty($_SESSION['user'])) {
		$faq->getShowCategory();
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($_POST['addQuestion']) {
		$faq->getAddQuestion($_POST['name'], $_POST['email'], $_POST['text'], $_POST['category']);
	}
	
	if($_POST['auth']) {
		$user->getLogon($_POST['login'], $_POST['password']);
	}
	
	if($_POST['createAdmin']) {
		$user->setNewAdmin($_POST['newAdmin'], $_POST['pass']);
	}
	
	if($_POST['newPass']){
		$user->getUpdatePass($_POST['new-password'], $_POST['name-admin']);
	}
	
	if($_POST['createCategory']) {
		$user->getNewCategory($_POST['title']);
	}
	
	if($_POST['renameAuthor']) {
		$user->getNewName($_POST['questionId'], $_POST['newName']);
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
