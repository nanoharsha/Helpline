<?php
require_once '../includes/db.php';
$select = $db->prepare("SELECT id, title FROM issues WHERE title LIKE :title");
$select->execute(array(":title"=>'%'.$_GET["term"].'%'));

$issues = $select->fetchAll();
$issues_array = array();
$issues_json_string = '[';
if(count($issues) > 0) {
    $i = 0;
    foreach($issues as $issue) {
        $issues_array[$i]['label'] = $issue['title'];
        $issues_array[$i]['value'] = $issue['id'];
        $i++;
    }
    echo json_encode($issues_array);
}
else echo json_encode($issues_array);
