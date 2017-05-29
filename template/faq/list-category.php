<div class="list-category">
<table>
	<tr>
		<td>Название категории</td>
		<td>Количество вопросов</td>
		<td>Опубликовано</td>
		<td>Без ответа</td>
		<td colspan="2">Действие</td>
	</tr>
	<?php
		$i = 0;
		
		foreach($countQuest as $key) {
			echo '<tr>';
				echo '<td>'.$key['name']. '</td>';
				echo '<td>'.$key['count_question']. '</td>';
				echo '<td>'.$countShowQuest[$i]['count_show']. '</td>';
				echo '<td>'.$countAnswerQuest[$i]['count_answer']. '</td>';
				echo '<td><a href="?interface-admin=1&list-category=1&delCat='.$key['id'].'">Удалить категорию и вопросы</a></td>';
				echo '<td><a href="?interface-admin=1&showQuestion='.$key['id'].'">Просмотреть</a></td>';
			echo '</tr>';
			$i++;
		}
	?>
<table>	
</div>

