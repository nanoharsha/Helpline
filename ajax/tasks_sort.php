<?php
require_once '../includes/db.php';
if(isset($_POST['sort'])) {
    $data = '';
    switch($_POST['sort']) {
        case 'sort_asc':
            $select = $db->query("SELECT tasks.title, tasks.due_date as due_date, tasks.description, tasks.filename, issues.title as issue_title, issues.issue_type as issue_type FROM tasks
JOIN issues ON tasks.issue_id = issues.id ORDER BY tasks.title ASC");
            $tasks = $select->fetchAll();
            break;

        case 'sort_desc':
            $select = $db->query("SELECT tasks.title, tasks.due_date as due_date, tasks.description, tasks.filename, issues.title as issue_title, issues.issue_type as issue_type FROM tasks
JOIN issues ON tasks.issue_id = issues.id ORDER BY tasks.title DESC");
            $tasks = $select->fetchAll();
            break;

        default:
            $tasks = array();
            break;
    }

    if(count($tasks) > 0) {
        foreach($tasks as $task) {
            $data .= '
              <div class="row">
                <div class="col-xs-4">' . $task["issue_type"]. '</div>
                <div class="col-xs-4">' . $task["issue_title"] . '</div>
                <div class="col-xs-4"></div>
            </div>
            <div class="row">
                <div class="col-xs-4">' . $task["title"] . '</div>
                <div class="col-xs-4">' .  $task["due_date"] . '</div>
                <div class="col-xs-4">';
            if($task["filename"] != '') { $data .= '<a href="uploads/' . $task["filename"] . '" target="_blank">DL</a>'; }
            $data .= '</div>
            </div>
            <div class="row">
                <div class="col-xs-12">' . $task["description"] . '</div>
            </div>
            <br />
            ';
        }
    }
    echo $data;
}
