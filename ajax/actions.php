<?php
if(!empty($_POST)) {
    require_once '../includes/db.php';

    if(isset($_POST["high_priority"])) $high_priority = "Y"; else $high_priority = "N";

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
                $insert = $db->prepare("INSERT INTO actions(issue_id, title, description, priority) VALUES(:issue_id, :title, :description, :priority)");
                $insert->execute(array(
                    ":issue_id" => $_POST["issue_id"],
                    ":title" => $_POST["title"],
                    ":description" => $_POST["description"],
                    ":priority" => $high_priority,
                ));

                $id = $db->lastInsertId();

                if($file) {
                    if(move_uploaded_file($filepath, "../uploads/".$id."_".$filename)) {
                        $sql = "UPDATE actions SET filename = :name, filetype = :type WHERE id = :id";
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
                $update = $db->prepare("UPDATE actions SET title = :title, description = :description, priority = :priority WHERE id = :id");
                $update->execute(array(
                    ":title" => $_POST["title"],
                    ":description" => $_POST["description"],
                    ":priority" => $high_priority,
                    ":id" => $_POST["id"],
                ));

                if($file) {
                    if(move_uploaded_file($filepath, "../uploads/".$id."_".$filename)) {
                        $sql = "UPDATE actions SET filename = :name, filetype = :type WHERE id = :id";
                        $update = $db->prepare($sql);
                        $update->execute(array(
                            ":name" => $id."_".$filename,
                            ":type" => $filetype,
                            ":id" => $_POST["id"]
                        ));
                    }
                }
                break;

            case "delete":
                $delete = $db->prepare("DELETE FROM actions WHERE id = :id");
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
