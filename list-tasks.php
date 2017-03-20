<?php include_once 'includes/app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php'; ?>
</head>
<body>

<div class="container-fluid" id="page-container">
    <?php include 'includes/header.php'; ?>
    <div class="row">
        <div class="col-xs-2"><button type="button" class="btn btn-default">Filter</button> </div>
        <div class="col-xs-2">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Sort
                    <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#" id="sort_asc"><span class="glyphicon glyphicon-ok sort-icon" aria-hidden="true"></span> ASC</a></li>
                    <li><a href="#" id="sort_desc"><span class="glyphicon glyphicon-ok sort-icon" aria-hidden="true"></span> DESC</a></li>
                </ul>
            </div>
        </div>
        <div class="col-xs-8">
            <div style="position: relative;">
                <i class="glyphicon glyphicon-search" style="position: absolute; top: 10px; left: 10px;" ></i>
                <input type="text" class="form-control" id="search" placeholder="search" />
            </div>
        </div>
    </div>
    <div class="margin-top"></div>
    <div class="row">
        <div class="col-xs-12">
            <input type="checkbox" name="show_my_tasks" /> Show only my tasks
        </div>
    </div>
    <br />
    <div id="tasks-container">
    <?php
    $select = $db->query("SELECT tasks.title, tasks.due_date as due_date, tasks.description, tasks.filename, issues.title as issue_title, issues.issue_type as issue_type FROM tasks
JOIN issues ON tasks.issue_id = issues.id");
    $tasks = $select->fetchAll();
    if(count($tasks) > 0) {
        foreach($tasks as $task) { ?>
            <div class="row">
                <div class="col-xs-4"><?php echo $task["issue_type"]; ?></div>
                <div class="col-xs-4"><?php echo $task["issue_title"]; ?></div>
                <div class="col-xs-4"></div>
            </div>
            <div class="row">
                <div class="col-xs-4"><?php echo $task["title"]; ?></div>
                <div class="col-xs-4"><?php echo $task["due_date"]; ?></div>
                <div class="col-xs-4"><?php if($task["filename"] != '') { ?><a href="uploads/<?php echo $task["filename"]; ?>" target="_blank">DL</a><?php } ?></div>
            </div>
            <div class="row">
                <div class="col-xs-12"><?php echo $task["description"]; ?></div>
            </div>
            <br />
    <?php
        }
    }
    ?>
    </div>
    <div class="margin-footer"></div>
    <?php include 'includes/footer.php'; ?>
    <div id="loadingDiv"></div>
</div>
<script>
    $(document).ready(function() {
        $('#search').autocomplete({
            source: "ajax/tasks_json.php",
            delay: 500,
            select: function (event, ui) {
                event.preventDefault();
                this.value = ui.item.label;
                var task_value = ui.item.value.split(":");
                var form = $('<form action="tasks.php" method="post">' +
                    '<input type="hidden" name="method" value="edit" />' +
                    '<input type="hidden" name="issue_id" value="' + task_value[1] + '"/>' +
                    '<input type="hidden" name="id" value="' + task_value[0] + '" />' +
                    '</form>');
                $('body').append(form);
                form.submit();
            }
        });

        $("#sort_asc, #sort_desc").click(function() {
            $(".sort-icon").hide();
            $(this).parent().find(".sort-icon").show();
            $("#loadingDiv").addClass("loadingDiv");
            $.post("ajax/tasks_sort.php", { sort:$(this).attr("id") }, function(data) {
                $("#tasks-container").html(data);
                setTimeout(function() {
                    $("#loadingDiv").removeClass("loadingDiv");
                }, 300);
            });
        });

    });
</script>
</body>
</html>