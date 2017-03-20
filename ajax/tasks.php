<?php
if(!empty($_POST)) {
    require_once '../includes/db.php';

    if(isset($_POST["high_priority"])) $high_priority = "Y"; else $high_priority = "N";
    if(isset($_POST["done"])) $done = "Y"; else $done = "N";

    if($_FILES["file"]["name"] != "" && $_FILES["file"]["error"] == 0) {
        $file = true;
        $filename = $_FILES["file"]["name"];
        $filetype = $_FILES["file"]["type"];
        $filepath = $_FILES["file"]["tmp_name"];
    } else {
        $file = false;
    }

    try {
        switch($_POST["method"]) {
            case "add":
                $insert = $db->prepare("INSERT INTO tasks(issue_id, title, done, priority, description, due_date) VALUES(:issue_id, :title, :done, :priority, :description, :due_date)");
                $insert->execute(array(
                    ":issue_id" => $_POST["issue_id"],
                    ":title" => $_POST["title"],
                    ":done" => $done,
                    ":priority" => $high_priority,
                    ":description" => $_POST["description"],
                    ":due_date" => $_POST["due_date"]
                ));

                $id = $db->lastInsertId();

                if($file) {
                    if(move_uploaded_file($filepath, "../uploads/".$id."_".$filename)) {
                        $sql = "UPDATE tasks SET filename = :name, filetype = :type WHERE id = :id";
                        $update = $db->prepare($sql);
                        $update->execute(array(
                            ":name" => $id."_".$filename,
                            ":type" => $filetype,
                            ":id" => $id
                        ));
                    }
                }
                break;

            case "edit":
                if($done == 'N') {
                    $update = $db->prepare("UPDATE tasks SET title = :title, done = :done, priority = :priority, description = :description, due_date = :due_date WHERE id = :id");
                    $update->execute(array(
                        ":title" => $_POST["title"],
                        ":done" => $done,
                        ":priority" => $high_priority,
                        ":description" => $_POST["description"],
                        ":due_date" => $_POST["due_date"],
                        ":id" => $_POST["id"],
                    ));

                    if ($file) {
                        if (move_uploaded_file($filepath, "../uploads/" . $id . "_" . $filename)) {
                            $sql = "UPDATE tasks SET filename = :name, filetype = :type WHERE id = :id";
                            $update = $db->prepare($sql);
                            $update->execute(array(
                                ":name" => $id . "_" . $filename,
                                ":type" => $filetype,
                                ":id" => $_POST["id"]
                            ));
                        }
                    }
                } else if($done == 'Y') {
                   $select = $db->prepare("SELECT * FROM tasks WHERE id = :id");
                   $select->execute(array(":id"=>$_POST["id"]));
                   $task = $select->fetch();

                    $delete = $db->prepare("DELETE FROM tasks WHERE id = :id");
                    $delete->execute(array("id"=>$_POST["id"]));

                    $insert_action = $db->prepare("INSERT INTO actions(issue_id, title, description, priority, filename, filetype, task_done) VALUES(:issue_id, :title, :description, :priority, :filename, :filetype, :task_done)");
                    $insert_action->execute(array(
                        ":issue_id" => $task["issue_id"],
                        ":title" => $task["title"],
                        ":description" => $task["description"],
                        ":priority" => $high_priority,
                        ":filename" => $task["filename"],
                        ":filetype" => $task["filetype"],
                        ":task_done" => "1"
                    ));

                    $action_id = $db->lastInsertId();

                    if ($file) {
                        if (move_uploaded_file($filepath, "../uploads/" . $id . "_" . $filename)) {
                            $sql = "UPDATE actions SET filename = :name, filetype = :type WHERE id = :id";
                            $update = $db->prepare($sql);
                            $update->execute(array(
                                ":name" => $id . "_" . $filename,
                                ":type" => $filetype,
                                ":id" => $action_id
                            ));
                        }
                    }
                }
                break;

            case "delete":
                $delete = $db->prepare("DELETE FROM tasks WHERE id = :id");
                $delete->execute(array(":id" => $_POST["id"]));
                break;

            default:
                break;
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
}
