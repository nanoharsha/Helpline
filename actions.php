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
        $select = $db->prepare("SELECT * FROM actions WHERE id = :id");
        $select->execute(array(
            ":id" => $id
        ));
        if($select->rowCount() > 0) {
            $action = $select->fetch();
        } else {
            ?>
            <div class="alert alert-danger text-center">
                <h3>Action with <strong>ID <?php echo $_GET["id"]; ?></strong> not found.</h3>
            </div>
            <?php
        }
    }
    ?>
    <?php include 'includes/header.php'; ?>
    <div class="row">
        <div class="col-xs-12"><h2 class="text-center"><?php if($method == 'add') { ?> Add Action <?php } else if($method == 'edit') { echo $action['title']; } ?></h2></div>
    </div>
    <div class="margin-top"></div>
    <div class="row">
        <div class="col-xs-12">
            <h4><?php if($method == 'add') { echo 'Add Action'; ?><br /><br /><a href="<?php echo APP_ROOT; ?>issue/<?php echo $issue_id; ?>" class="btn btn-warning">Cancel</a><?php } else if($method == 'edit') { echo 'Edit Action'; ?><br /><br /><a href="<?php echo APP_ROOT; ?>issue/<?php echo $issue_id; ?>" class="btn btn-warning">Cancel Edit</a><?php } ?></h4>
        </div>
    </div>
    <div class="margin-top"></div>
    <form method="post" id="actionsForm" enctype="multipart/form-data">
    <div class="row">
        <div class="col-xs-12">
            <label>Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="<?php echo isset($action) ? $action["title"] : ''; ?>" />
        </div>
    </div>
        <br />
        <div class="row">
            <div class="col-xs-12">
                <label>Description</label>
                <textarea class="form-control" name="description" placeholder="Description" rows="4"><?php echo isset($action) ? $action["description"] : ''; ?></textarea>
            </div>
        </div>
    <br />
    <div class="row">
        <div class="col-xs-12">
            <div class="fileUpload btn btn-primary">
                <span>Attach File</span>
                <input id="uploadBtn" type="file" name="file" class="upload" />
            </div>
            <div id="uploadFile"><?php if(isset($action) && $action['filename'] != '') { $file = explode("_", $action['filename'], 2); echo $file[1]; ?> <?php } ?></div>
        </div>
    </div>
        <br />
   <div class="row">
       <div class="col-xs-12">
           <input type="checkbox" name="high_priority"  <?php if(isset($action) && $action["priority"] == "Y") { ?> checked <?php } ?> /> High Priority
       </div>
   </div>
    <br />
    <div class="row">
        <div class="col-xs-5">
            <button type="button"  id="submitBtn" class="btn btn-primary">Submit</button>
        </div>
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5 text-right">
            <button type="button" class="btn btn-danger" id="delete-forever">Delete forever</button>
        </div>
    </div>
        <input type="hidden" name="issue_id" id="issue_id" value="<?php echo $issue_id; ?>" />
        <input type="hidden" name="method" id="method" value="<?php echo $method; ?>" />
        <?php if($method == 'edit') { ?>
            <input type="hidden" name="id" id="action_id" value="<?php echo $id; ?>" />
        <?php } ?>
        <br />
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-success alert-hidden text-center">
                    <?php if($method == 'edit') { ?>
                        <h3>Action was updated successfully.</h3>
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

        $("#submitBtn").click(function() {
            if($.trim($("#title").val()) != '') {
                var form = $('#actionsForm')[0];
                var formData = new FormData(form);
                jQuery.ajax({
                    url: 'ajax/actions.php',
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
            } else alert("Please enter Action title.");
        });

        $("#delete-forever").click(function() {
            if(confirm("Are you sure you want to delete?")) {
                $.post("ajax/actions.php", {method: 'delete', id: $("#action_id").val()}, function (data) {
                    window.location.href = 'issue/'+$("#issue_id").val();
                });
            }
        });

    });
</script>
</body>
</html>