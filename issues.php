<?php include_once 'includes/app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php'; ?>
</head>
<body>

<div class="container-fluid">
    <?php
    if(isset($_POST["method"])) {
        $method = $_POST["method"];
    } else $method = 'add';

    if($method == 'edit') {
        $id = $_POST["id"];
        $select = $db->prepare("SELECT * FROM issues WHERE id = :id");
        $select->execute(array(
           ":id" => $id
        ));
        if($select->rowCount() > 0) {
            $issue = $select->fetch();
        } else {
            ?>
    <div class="alert alert-danger text-center">
        <h3>Issue with <strong>ID <?php echo $_GET["id"]; ?></strong> not found.</h3>
    </div>
    <?php
        }
    }
    ?>
    <?php include 'includes/header.php'; ?>
    <div class="row">
        <div class="col-xs-12"><h2 class="text-center"><?php if($method == 'add') { ?> Add Issue <?php } else if($method == 'edit') { echo $issue["title"]; } ?></h2></div>
    </div>
    <div class="margin-top"></div>
    <div class="row">
        <div class="col-xs-12">
            <h4>
            <?php
            if($method == 'add') { echo 'Add Issue'; ?><br /><br /><a href="<?php echo APP_ROOT; ?>" class="btn btn-warning">Cancel</a><?php } else if($method == 'edit') { echo 'Edit Issue'; ?><br /><br /><a href="<?php echo APP_ROOT; ?>issue/<?php echo $id; ?>" class="btn btn-warning">Cancel Edit</a><?php } ?>
            </h4>
        </div>
    </div>
    <div class="margin-top"></div>
    <form method="post" id="issuesForm" enctype="multipart/form-data">
    <div class="row">
        <div class="col-xs-12">
            <label>Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Issue Title" value="<?php echo isset($issue) ? $issue["title"] : ''; ?>" />
        </div>
    </div>
        <br />
        <div class="row">
            <div class="col-xs-12">
                <label>Type</label>
                <?php
                $select_issue_types = $db->query("SELECT * FROM issue_types");
                $issue_types = $select_issue_types->fetchAll();
                ?>
                <select name="type" class="form-control">
                    <?php if(count($issue_types) > 0) { ?>
                    <?php foreach($issue_types as $issue_type) { ?>
                    <option value="<?php echo $issue_type["id"]; ?>:<?php echo $issue_type["issue_type"]; ?>" <?php if(isset($issue) && ($issue["issue_type"] == $issue_type["issue_type"] || $issue["issue_type_id"] == $issue_type["id"])) { ?> selected <?php } ?>><?php echo $issue_type["issue_type"]; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
               </div>
        </div>
    <br />
        <div class="row">
            <div class="col-xs-12">
                <label>Date</label>
                <input type="text" name="date" id="date" class="form-control" placeholder="Date" value="<?php echo isset($issue) ? $issue["date"] : ''; ?>" />
            </div>
        </div>
    <br />
        <div class="row">
            <div class="col-xs-12">
                <label>Contact Person</label>
                <input type="text" class="form-control" name="contact_person" placeholder="Contact person" value="<?php echo isset($issue) ? $issue["contact_person"] : ''; ?>" />
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-xs-12">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo isset($issue) ? $issue["phone"] : ''; ?>" />
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-xs-12">
                <label>Address</label>
                <input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo isset($issue) ? $issue["address"] : ''; ?>" />
            </div>
        </div>
<br />
        <div class="row">
            <div class="col-xs-12">
                <label>Description</label>
                <textarea class="form-control" placeholder="Description" name="description" rows="4"><?php echo isset($issue) ? $issue["description"] : ''; ?></textarea>
            </div>
        </div>
        <br />
    <div class="row">
        <div class="col-xs-12">
            <div class="fileUpload btn btn-primary">
                <span>Attach File</span>
                <input id="uploadBtn" type="file" name="file" class="upload" />
            </div>
            <div id="uploadFile"><?php if(isset($issue) && $issue['filename'] != '') { $file = explode("_", $issue['filename'], 2); echo $file[1]; ?> <?php } ?></div>
        </div>
    </div>
    <br />
        <div class="row">
            <div class="col-xs-12"><input type="checkbox" name="high_priority" <?php if(isset($issue) && $issue["priority"] == "Y") { ?> checked <?php } ?> /> High Priority</div>
        </div>
<br />
    <div class="row">
        <div class="col-xs-5">
            <button type="button" id="submitBtn" class="btn btn-primary">Submit</button>
        </div>
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5 text-right">
           <?php if($method == 'edit') { ?>  <button type="button" id="delete-forever" class="btn btn-danger">Delete forever</button> <?php } ?>
        </div>
    </div>
        <br />
        <div class="row">
            <div class="col-xs-12">
              <div class="alert alert-success alert-hidden text-center">
                  <?php if($method == 'add') { ?>
                  <h3>Issue was added successfully.</h3>
                  <?php } else if($method == 'edit') { ?>
                  <h3>Issue was updated successfully.</h3>
                  <?php } ?>
              </div>
            </div>
        </div>
        <input type="hidden" name="method" id="method" value="<?php echo $method; ?>" />
        <?php if($method == 'edit') { ?>
        <input type="hidden" name="id" id="issue_id" value="<?php echo $id; ?>" />
        <?php } ?>
    </form>
    <div class="margin-footer"></div>
    <?php include 'includes/footer.php'; ?>
</div>
<script>
    $(document).ready(function() {
        $("#date").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true
        });
        $("#uploadBtn").change(function() {
            var file = $(this).val().split("\\");
            var filename = file.pop();
            $("#uploadFile").text(filename);
        });
        $("#submitBtn").click(function() {
            if($.trim($("#title").val()) != '') {
                var form = $('#issuesForm')[0];
                var formData = new FormData(form);
                jQuery.ajax({
                    url: 'ajax/issues.php',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data) {
                        $(".alert-hidden").show().fadeOut(4000);
                            window.location.href = 'index.php';
                    }
                });
            } else alert("Please enter Issue title.");
        });
        $("#delete-forever").click(function() {
            if(confirm("Are you sure you want to delete?")) {
                $.post("ajax/issues.php", {method: 'delete', id: $("#issue_id").val()}, function (data) {
                    window.location.href = 'index.php';
                });
            }
        });
    });
</script>
</body>
</html>