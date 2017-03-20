<?php include_once 'includes/app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php'; ?>
</head>
<body>

<div class="container-fluid">
    <?php
    if(isset($_POST["issue_id"])) {
        $issue_id = $_POST["issue_id"];
    if(isset($_POST["method"])) {
        $method = $_POST["method"];
    } else $method = 'add';
        if($method == 'edit') {
        $id = $_POST["id"];
        $select = $db->prepare("SELECT * FROM tasks WHERE id = :id");
            $select->execute(array(
            ":id" => $id
            ));
        if($select->rowCount() > 0) {
          $task = $select->fetch();
        } else {
        ?>
        <div class="alert alert-danger text-center">
            <h3>Task with <strong>ID <?php echo $_GET["id"]; ?></strong> not found.</h3>
        </div>
            <?php
        }
        }
    ?>
    <?php include 'includes/header.php'; ?>
    <div class="row">
        <div class="col-xs-12"><h2 class="text-center"><?php if($method == 'add') { ?> Add Task <?php } else if($method == 'edit') { echo $task['title']; } ?></h2></div>
    </div>
    <div class="margin-top"></div>

    <div class="row">
        <div class="col-xs-12">
            <h4><?php if($method == 'add') { echo 'Add Task'; ?><br /><br /><a href="<?php echo APP_ROOT; ?>issue/<?php echo $issue_id; ?>" class="btn btn-warning">Cancel</a><?php } else if($method == 'edit') { echo 'Edit Task'; ?><br /><br /><a href="<?php echo APP_ROOT; ?>issue/<?php echo $issue_id; ?>" class="btn btn-warning">Cancel Edit</a> <?php } ?></h4>
        </div>
    </div>
    <div class="margin-top"></div>
    <form method="post" id="tasksForm" enctype="multipart/form-data">
    <div class="row">
        <div class="col-xs-12"><input type="checkbox" name="done" <?php if(isset($task) && $task["done"] == "Y") { ?> checked <?php } ?> /> Mark as done</div>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-12">
            <label>Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Title"  value="<?php echo isset($task) ? $task["title"] : ''; ?>" />
        </div>
    </div>
        <br />
        <div class="row">
            <div class="col-xs-12">
                <label>Due Date</label>
                <input type="text" class="form-control" name="due_date" id="due_date" placeholder="Due date" value="<?php echo isset($task) ? $task["due_date"] : ''; ?>" />
            </div>
        </div>
    <br />
        <div class="row">
            <div class="col-xs-12">
                <label>Description</label>
                <textarea class="form-control" name="description" placeholder="Description" rows="4"><?php echo isset($task) ? $task["description"] : ''; ?></textarea>
            </div>
        </div>
        <br />
    <div class="row">
        <div class="col-xs-12">
            <div class="fileUpload btn btn-primary">
                <span>Attach File</span>
                <input id="uploadBtn" type="file" name="file" class="upload" />
            </div>
            <div id="uploadFile"><?php if(isset($task) && $task['filename'] != '') { $file = explode("_", $task['filename'], 2); echo $file[1]; ?> <?php } ?></div>
        </div>
    </div>
        <br />
        <div class="row">
            <div class="col-xs-12"><input type="checkbox" name="high_priority" <?php if(isset($task) && $task["priority"] == "Y") { ?> checked <?php } ?> /> High Priority</div>
        </div>
    <br />
    <div class="row">
        <div class="col-xs-5">
            <button type="button" id="submitBtn" class="btn btn-primary">Submit</button>
        </div>
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5 text-right">
           <?php if($method == 'edit') { ?> <button type="button" class="btn btn-danger" id="delete-forever">Delete forever</button><?php } ?>
        </div>
    </div>
        <input type="hidden" name="issue_id" id="issue_id" value="<?php echo $issue_id; ?>" />
        <input type="hidden" name="method" id="method" value="<?php echo $method; ?>" />
        <?php if($method == 'edit') { ?>
            <input type="hidden" name="id" id="task_id" value="<?php echo $id; ?>" />
        <?php } ?>
        <br />
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-success alert-hidden text-center">
                    <?php if($method == 'edit') { ?>
                        <h3>Task was updated successfully.</h3>
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>
    <?php } else { ?>
    <div class="alert alert-danger text-center">
        <h3>Issue ID is missing.</h3>
    </div>
    <?php } ?>
    <div class="margin-footer"></div>
    <?php include 'includes/footer.php'; ?>
</div>
<script>
    $(document).ready(function() {
        $("#uploadBtn").change(function() {
            var file = $(this).val().split("\\");
            var filename = file.pop();
            $("#uploadFile").text(filename);
        });
        $("#due_date").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true
        });

        $("#submitBtn").click(function() {
            if($.trim($("#title").val()) != '') {
                var form = $('#tasksForm')[0];
                var formData = new FormData(form);
                jQuery.ajax({
                    url: 'ajax/tasks.php',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data) {
                        $(".alert-hidden").show().fadeOut(4000);
                            window.location.href = 'issue/'+$("#issue_id").val();
                    }
                });
            } else alert("Please enter Task title.");
        });

        $("#delete-forever").click(function() {
            if(confirm("Are you sure you want to delete?")) {
                $.post("ajax/tasks.php", {method: 'delete', id: $("#task_id").val()}, function (data) {
                    window.location.href = 'issue/'+$("#issue_id").val();
                });
            }
        });

    });
</script>
</body>
</html>