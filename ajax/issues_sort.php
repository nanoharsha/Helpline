<?php
require_once '../includes/db.php';
if(isset($_POST['sort'])) {
$data = '';
    switch($_POST['sort']) {
        case 'sort_asc':
            $select = $db->query("SELECT * FROM issues ORDER BY title ASC");
            $issues = $select->fetchAll();
            break;

        case 'sort_desc':
            $select = $db->query("SELECT * FROM issues ORDER BY title DESC");
            $issues = $select->fetchAll();
            break;

        default:
            $issues = array();
            break;
    }

    if(count($issues) > 0) {
        foreach($issues as $issue) {
            $data .= '<a href="issue/' . $issue["id"] . '"';
            if($issue["priority"] == 'Y') { $data .= ' class="high-priority" ';  }
            $data .= '>
                <div class="row issue-row" id="' . $issue["id"] . '">
                    <div class="col-xs-6">
                        <div class="issue-title">' . $issue["title"] . '</div>
                        <div class="issue-date">' . $issue["date"] . '</div>
                    </div>
                    <div class="col-xs-6">
                        <div class="issue-type">' . $issue["issue_type"] . '</div>
                        <div class="issue-location">' . $issue["address"] . '</div>
                    </div>
                </div>
            </a>';
        }
    }
    echo $data;
}
