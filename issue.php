<?php include_once 'includes/app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php'; ?>
</head>
<body>

<div class="container-fluid">
    <?php include 'includes/header.php'; ?>
    <?php
    $select = $db->prepare("SELECT * FROM issues WHERE id = :id");
    $select->execute(array(
       ":id" => $_GET["id"]
    ));
    if($select->rowCount() > 0) {
        $issue = $select->fetch();
    ?>
    <div class="row">
        <div class="col-xs-8"><h2 class="text-center"><?php echo $issue["title"]; ?></h2></div>
        <div class="col-xs-4 text-center">
            <form method="post" action="../issues.php">
                <button type="submit" class="btn btn-success" style="position: relative; top: 20px;">Edit Issue</button>
                <input type="hidden" name="method" value="edit" />
                <input type="hidden" name="id" value="<?php echo $issue["id"]; ?>" />
            </form>
        </div>
    </div>
        <br />
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-bordered">
                    <tr>
                        <th>Type</th>
                        <th>Contact</th>
                        <th>Phone</th>
                        <th>Address</th>
                    </tr>
                    <tr>
                        <td><?php echo $issue["issue_type"]; ?></td>
                        <td><?php echo $issue["contact_person"]; ?></td>
                        <td><?php echo $issue["phone"]; ?></td>
                        <td><?php echo $issue["address"]; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?php echo $issue["description"]; ?>
            </div>
        </div>
    <div class="margin-top"></div>
        <hr/>
    <div class="row">
        <div class="col-xs-12">
            <form method="post" action="<?php echo APP_ROOT; ?>tasks.php">
                <button class="btn btn-primary btn-sm">Add Task</button>
                <input type="hidden" name="method" value="add" />
                <input type="hidden" name="issue_id" value="<?php echo $issue["id"]; ?>" />
            </form>
        </div>
    </div>
<br />
        <?php
        $select_tasks = $db->prepare("SELECT * FROM tasks WHERE issue_id = :issue_id");
        $select_tasks->execute(array(":issue_id" => $issue["id"]));
        if($select_tasks->rowCount() > 0) {
            $tasks = $select_tasks->fetchAll();
            foreach($tasks as $task) {
                ?>
            <div class="row">
                <div class="col-xs-4">
                    <div class="task-title"><?php echo $task["title"]; ?></div>
                    <div class="task-description"><?php echo $task["description"]; ?></div>
                </div>
                <div class="col-xs-3"><?php echo $task["due_date"]; ?></div>
                <div class="col-xs-1">
                    <?php if($task["filename"] != '') { ?>
                        <a href="../uploads/<?php echo $task["filename"]; ?>" target="_blank">DL</a>
                    <?php } ?>
                </div>
                <div class="col-xs-4 text-center">
                    <form method="post" action="../tasks.php">
                        <input type="hidden" name="method" value="edit" />
                        <input type="hidden" name="id" value="<?php echo $task["id"]; ?>" />
                        <input type="hidden" name="issue_id" value="<?php echo $issue["id"]; ?>" />
                        <button type="submit" class="btn btn-info btn-sm">Edit Task</button>
                    </form>
                </div>
            </div><br />
                <?php
            }
        }
        ?>
    <div class="margin-top"></div>
        <hr/>
    <div class="row">
        <div class="col-xs-12">
            <form method="post" action="../actions.php">
                <input type="hidden" name="method" value="add" />
                <input type="hidden" name="issue_id" value="<?php echo $issue["id"]; ?>" />
                <button type="submit" class="btn btn-primary btn-sm">Add Action</button>
            </form>
        </div>
    </div>
        <br />
        <?php
        $select_actions = $db->prepare("SELECT * FROM actions WHERE issue_id = :issue_id");
        $select_actions->execute(array(":issue_id" => $issue["id"]));
        if($select_actions->rowCount() > 0) {
            $actions = $select_actions->fetchAll();
            foreach($actions as $action) { ?>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="action-title"><?php if($action["task_done"] == 1) { ?><span class="done-task">DONE: </span><?php } ?><?php echo $action["title"]; ?></div>
                        <div class="action-description"><?php echo $action["description"]; ?></div>
                    </div>
                    <div class="col-xs-3"></div>
                    <div class="col-xs-1">
                        <?php if($action["filename"] != '') { ?>
                            <a href="../uploads/<?php echo $action["filename"]; ?>" target="_blank">DL</a>
                        <?php } ?>
                    </div>
                    <div class="col-xs-4 text-center">
                        <form method="post" action="../actions.php">
                            <input type="hidden" name="method" value="edit" />
                            <input type="hidden" name="id" value="<?php echo $action["id"]; ?>" />
                            <input type="hidden" name="issue_id" value="<?php echo $issue["id"]; ?>" />
                            <button type="submit" class="btn btn-info btn-sm">Edit Action</button>
                        </form>
                    </div>
                </div>
                <br />
            <?php }
        }
        ?>
<?php } else { ?>
    <div class="alert alert-danger text-center">
        <h3>Issue with <strong>ID <?php echo $_GET["id"]; ?></strong> not found.</h3>
    </div>
    <?php } ?>
    <div class="margin-footer"></div>
    <?php include 'includes/footer.php'; ?>
</div>
</body>
</html>

