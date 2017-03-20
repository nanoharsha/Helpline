<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require_once '../includes/db.php';

if(isset($_POST["high_priority"])) $high_priority = "Y"; else $high_priority = "N";

if(isset($_POST["type"])) {
    $issue_array = explode(":", $_POST["type"]);
    $issue_type_id = $issue_array[0];
    $issue_type = $issue_array[1];
} else {
    $issue_type_id = "0";
    $issue_type = '';
}

if(isset($_FILES["file"]) && $_FILES["file"]["name"] != "" && $_FILES["file"]["error"] == 0) {
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
            $insert = $db->prepare("INSERT INTO issues(title, issue_type_id, issue_type, description, contact_person, phone, address, priority, date) VALUES(:title, :issue_type_id, :issue_type, :description, :contact_person, :phone, :address, :priority, :date)");
            $insert->execute(array(
                ":title" => $_POST["title"],
                ":issue_type_id" => $issue_type_id,
                ":issue_type" => $issue_type,
                ":description" => $_POST["description"],
                ":contact_person" => $_POST["contact_person"],
                ":phone" => $_POST["phone"],
                ":address" => $_POST["address"],
                ":priority" => $high_priority,
                ":date" => $_POST["date"]
            ));

            $id = $db->lastInsertId();

            if($file) {
                if(move_uploaded_file($filepath, "../uploads/".$id."_".$filename)) {
                    $sql = "UPDATE issues SET filename = :name, filetype = :type WHERE id = :id";
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
            $update = $db->prepare("UPDATE issues SET title = :title, issue_type_id = :issue_type_id, issue_type = :issue_type, description = :description, contact_person = :contact_person, phone = :phone, address = :address, priority = :priority, date = :date WHERE id = :id");
            $update->execute(array(
                ":title" => $_POST["title"],
                ":issue_type_id" => $issue_type_id,
                ":issue_type" => $issue_type,
                ":description" => $_POST["description"],
                ":contact_person" => $_POST["contact_person"],
                ":phone" => $_POST["phone"],
                ":address" => $_POST["address"],
                ":priority" => $high_priority,
                ":date" => $_POST["date"],
                ":id" => $_POST["id"]
            ));

            if($file) {
                if(move_uploaded_file($filepath, "../uploads/".$id."_".$filename)) {
                    $update_file = $db->prepare("UPDATE issues SET filename = :name, filetype = :type WHERE id = :id");
                    $update_file->execute(array(
                        ":name" => $filename,
                        ":type" => $filetype,
                        ":id" => $_POST["id"]
                    ));
                }
            }
            break;

        case "delete":
            $delete = $db->prepare("DELETE FROM issues WHERE id = :id");
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
