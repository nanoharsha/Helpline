<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
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
            <form method="post" action="issues.php" method="post">
                <button type="submit" class="btn btn-primary btn-sm">Add Issue</button>
                <input type="hidden" name="method" value="add" />
            </form>
        </div>
    </div>
    <div class="margin-top"></div>
    <div id="issues-container">
    <!-- LIST ISSUES -->
    <?php
    $select = $db->query("SELECT * FROM issues");
    $issues = $select->fetchAll();
    if(count($issues) > 0) {
        foreach($issues as $issue) {?>
    <a href="issue/<?php echo $issue["id"]; ?>" <?php if($issue["priority"] == 'Y') { ?> class="high-priority" <?php } ?>>
    <div class="row issue-row" id="<?php echo $issue["id"]; ?>">
        <div class="col-xs-6">
            <div class="issue-title"><?php echo $issue["title"]; ?></div>
            <div class="issue-date"><?php echo $issue["date"]; ?></div>
        </div>
        <div class="col-xs-6">
            <div class="issue-type"><?php echo $issue["issue_type"]; ?></div>
            <div class="issue-location"><?php echo $issue["address"]; ?></div>
        </div>
    </div>
    </a>
            <?php
        }
    }
    ?>
    <!-- END OF LIST ISSUES -->
    </div>
    <div class="margin-top"></div>
    <div class="row" id="create-report">
        <div class="col-xs-12 text-right">
            <button class="btn btn-success btn-sm">Create Report</button>
        </div>
    </div>
    <div class="margin-footer"></div>
    <?php include 'includes/footer.php'; ?>
    <div id="loadingDiv"></div>
</div>
<script>
    $(document).ready(function() {
        $('#search').autocomplete({
            source: "ajax/issues_json.php",
            delay: 500,
            select: function (event, ui) {
                event.preventDefault();
                this.value = ui.item.label;
                window.location.href = 'issue/'+ui.item.value;
            }
        });

        $("#sort_asc, #sort_desc").click(function() {
            $(".sort-icon").hide();
            $(this).parent().find(".sort-icon").show();
            $("#loadingDiv").addClass("loadingDiv");
            $.post("ajax/issues_sort.php", { sort:$(this).attr("id") }, function(data) {
                $("#issues-container").html(data);
                setTimeout(function() {
                    $("#loadingDiv").removeClass("loadingDiv");
                }, 300);
            });
        });

    });
</script>
</body>
</html>