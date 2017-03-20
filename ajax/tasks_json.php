<?php
require_once '../includes/db.php';
$select = $db->prepare("SELECT id, issue_id, title FROM tasks WHERE title LIKE :title");
$select->execute(array(":title"=>'%'.$_GET["term"].'%'));
$tasks = $select->fetchAll();
$tasks_array = array();
if(count($tasks) > 0) {
    $i = 0;
    foreach($tasks as $task) {
        $tasks_array[$i]['label'] = $task['title'];
        $tasks_array[$i]['value'] = $task['id'].':'.$task['issue_id'];
        $i++;
    }
    echo json_encode($tasks_array);
}
else echo json_encode($tasks_array);
