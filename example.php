<?php
include 'Calendar.php';
$calendar = new Calendar(date("Y-m-d"));
$msg = '';
$msgType = '';
if (isset($_SESSION['userMsg'])) {
    $msg = $_SESSION['userMsg'];
    $msgType = $_SESSION['msgType'];
    $_SESSION['userMsg'] = '';
}

$connection = mysqli_connect("localhost", "root", "");
mysqli_set_charset($connection, 'utf8');
mysqli_select_db($connection, "mayfinalcal");
$articlesSql = "SELECT * FROM `events` ";
$articlesResulet = mysqli_query($connection, $articlesSql);
while ($articlesRow = mysqli_fetch_array($articlesResulet)) {

    $id = $articlesRow['id'];
    $txt = $articlesRow['txt'];
    $date = $articlesRow['date'];
    $days = $articlesRow['days'];
    $color = $articlesRow['color'];
    $calendar->add_event($id, $txt, $date, $days, $color);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Event Calendar</title>

    <script src="js/vendor/bootstrap.bundle.min.js"></script>
    <script src="js/vendor/jquery-3.6.0.slim.min.js"></script>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/calendar.css" rel="stylesheet" type="text/css">

</head>

<body>
    <nav class="navtop" style="display: block;">
        <div>
            <h1>Event Calender</h1>
        </div>
    </nav>

    <div></div>
    <div id="response" class="alert alert-<?= $msgType ?> alert-dismissible fade show mt-3" role="alert"
        style="display: none;">
        <strong id="msgText"><?= $msg ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>


    <div class="content home">
        <?= $calendar ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="AddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Event</h5>
                    <button type="button" id="closeAddModal" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name">Event</label>
                            <input type="text" id="addText" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="name">Date</label>
                            <input type="date" id="addDate" class="form-control addDate">

                        </div>

                        <div class="mb-3">
                            <label for="name">No. of Days</label>
                            <input type="number" id="addDays" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="name">Color</label>
                            <select id="addColor" class="form-control">
                                <option value="red">Red</option>
                                <option value="green">Green</option>
                                <option value="blue">Blue</option>
                                <option value="yellow">Yellow</option>
                            </select>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                        <button type="button" class="btn btn-primary addEvent">Add
                            Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <script src="js/script.js"></script>
    </footer>
</body>

</html>