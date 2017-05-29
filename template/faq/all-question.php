<div class="list-question">
<?php
		foreach($questionNoAnswer as $questAll) {
		echo '<div class="admin-question">';
			echo '<table>'; 
				echo '<tr>';
					echo '<td>Дата создания</td>';
					echo '<td>Статус</td>';
					echo '<td colspan="2">Автор и его email</td>';
					echo '<td colspan="2">Действие</td>';
					echo '<td colspan="3">Редактировать</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>'.$questAll['data'].'</td>';
					if($questAll['status'] == 0) {
						echo '<td class="red">Ожидает ответа</td>';
					}
					if($questAll['status'] == 1) {
						echo '<td class="green">Опубликован</td>';
					}
					if($questAll['status'] == 2) {
						echo '<td class="blue">Скрыт</td>';
					}
					echo '<td>'.$questAll['user_name'].'</td>';
					echo '<td>'.$questAll['user_email'].'</td>';
					if($questAll['status'] == 2) {
						echo '<td><a href="?interface-admin=1&showQuestion='.$_GET['showQuestion'].'&show='.$questAll['id'].'">Опубликовать</a></td>';
					}
					if($questAll['status'] == 1) {
						echo '<td><a href="?interface-admin=1&showQuestion='.$_GET['showQuestion'].'&hidden='.$questAll['id'].'">Скрыть</a></td>';
					}
					if(empty($questAll['answer'])) {
						echo '<td><a href="">Ответить</a></td>';
					}
					echo '<td><a href="?interface-admin=1&showQuestion='.$_GET['showQuestion'].'&del-question='.$questAll['id'].'">Удалить</a></td>';
					echo '<td class="author"><a href="">автора</a></td>';
					echo '<td class="question"><a href="">текст вопроса</a></td>';
					echo '<td class="answer"><a href="">текст ответа</a></td>';
					echo '<td class="idQuestion"><input type="hidden" value="'.$questAll['id'].'"></td>';
				echo '</tr>';
			echo '</table>';
			echo '<form method="POST"><p>Переместить вопрос, в категорию ';
			echo '<input type="hidden" value="'.$questAll['id'].'" name="idquestion">';
			echo '<select name="editCategory">
					<option value="null">-</option>';
						foreach($cat as $name){
							echo '<option value="'.$name['id'].'">'.$name['name'].'</option>';
						}
				echo '</select> <input type="submit" name="move" value="Отправить"></p></form>';
			echo '<p><strong>Вопрос</strong></p>';
			echo '<div class="editQuestion">' . $questAll['name'] . '</div>';
			echo '<p><strong>Ответ</strong></p>';
			echo '<div>' . $questAll['answer'] . '</div>';
		echo '</div>';	
	}
?>
</div>

<div class="popup">
	<p>Редактирование автора</p>
	
	<form method="POST">
		<input class="inner" type="text" name="newName"><br><br>
		<input type="submit" value="Изменить" name="renameAuthor">
		<input class="question-id" type="hidden" value="" name="questionId">
		<input class="cancel" type="button" value="Отмена">
	</form>
</div>

<div class="popup2">
	<p>Редактирование вопроса</p>
	
	<form method="POST">
		<p><textarea class="inner2" rows="10" cols="45" name="newQuestion"></textarea></p>
		<input type="submit" value="Изменить" name="renameQuestion">
		<input class="question-id2" type="hidden" value="" name="questionId">
		<input class="cancel" type="button" value="Отмена">
	</form>
</div>

<div class="popup3">
	<p>Редактирование ответа</p>
	
	<form method="POST">
		<p><textarea class="inner3" rows="10" cols="45" name="newAnswer"></textarea></p>
		<input type="submit" value="Изменить" name="renameAnswer">
		<input class="question-id3" type="hidden" value="" name="questionId">
		<input class="cancel" type="button" value="Отмена">
	</form>
</div>

<script>
	var popup = document.querySelector('.popup');
	var popup2 = document.querySelector('.popup2');
	var popup3 = document.querySelector('.popup3');
	
	
	var inner = document.querySelector('.inner');
	var inner2 = document.querySelector('.inner2');
	var inner3 = document.querySelector('.inner3');
	var cancel = document.querySelectorAll('.cancel');
	
	var author = document.querySelectorAll('.author');
	var question = document.querySelectorAll('.question');
	var answer = document.querySelectorAll('.answer');
	
	var idQuestion = document.querySelectorAll('.idQuestion');
	
	var questionId = document.querySelector('.question-id');
	var questionId2 = document.querySelector('.question-id2');
	var questionId3 = document.querySelector('.question-id3');
	
	author.forEach(function(i, item) {
 		i.addEventListener('click', function() {
    	event.preventDefault();
		popup.style.display = 'block';
		questionId.value = idQuestion[item].firstChild.value;
		inner.value = i.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.textContent;
      });
    })
	

	question.forEach(function(i, item) {
 		i.addEventListener('click', function() {
    	event.preventDefault();
		popup2.style.display = 'block';
		questionId2.value = idQuestion[item].firstChild.value;
		console.log(inner2.value = i.parentElement.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.textContent);
      });
    })
	
	answer.forEach(function(i, item) {
 		i.addEventListener('click', function() {
    	event.preventDefault();
		popup3.style.display = 'block';
		questionId3.value = idQuestion[item].firstChild.value;
		inner3.value = i.parentElement.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.textContent;
      });
    })
	
	cancel.forEach(function(i, item) {
		i.addEventListener('click', function() {
			if(popup) {
				popup.style.display = 'none';
			}
			if(popup2) {
				popup2.style.display = 'none';
			}
			if(popup3) {
				popup3.style.display = 'none';
			}
		});
	})

</script>
