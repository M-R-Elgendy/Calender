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
$allEventsSql = "SELECT * FROM `events` ";
$allEventsResulet = mysqli_query($connection, $allEventsSql);
while ($allEventsRow = mysqli_fetch_array($allEventsResulet)) {

    $id = $allEventsRow['id'];
    $txt = $allEventsRow['txt'];
    $date = $allEventsRow['date'];
    $days = $allEventsRow['days'];
    $color = $allEventsRow['color'];
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
    <script src="js/notify.js"></script>

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/calendar.css" rel="stylesheet" type="text/css">

    <?php

    $toDay = date("Y-m-d");
    $toDayEventSql = "SELECT `txt`, `days`, `date` FROM events WHERE date = '$toDay' OR (date < DATE_ADD('$toDay' , INTERVAL 2 DAY) AND date > DATE_ADD('$toDay' , INTERVAL -2 DAY))";
    $toDayEventarray = [];
    $toDayEventResulet = mysqli_query($connection, $toDayEventSql);
    while ($toDayEventRow = mysqli_fetch_assoc($toDayEventResulet)) {
        // $eventStartDate = $toDayEventRow['date'];
        // $eventDayes = $toDayEventRow['days'];
        // $eventText = $toDayEventRow['txt'];

        // $eventEndDate = date('Y-m-d', strtotime($eventStartDate . " + $eventDayes days"));
        // if ($toDay >= $eventStartDate && $toDay <= $eventEndDate) {
        //     $toDayEventarray[] = array("txt" => $eventText, "days" => $eventDayes, "date" => $eventStartDate);
        // }

        $toDayEventarray[] = $toDayEventRow;
    }
    $toDayEventJson = json_encode($toDayEventarray);
    // echo $officersJson;
    echo "
<script>
    var toDayEventJson = $toDayEventJson;
</script>
";

    ?>

</head>

<body>
    <!-- <nav class="navtop" style="display: block;">
        <div>
            <h1>Event Calender</h1>
        </div>
    </nav> -->

    <nav class="navbar navbar-dark bg-dark navtop navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                    class="bi bi-calendar-event" viewBox="0 0 16 16">
                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                    <path
                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                </svg>
                &nbsp;&nbsp;Event Calender
            </a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">User Manager</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notifySwitcher" style="cursor: pointer;"
                                onchange="Notify(); notifyingSetting();" />
                            <label class="form-check-label" for="notifySwitcher"
                                style="color: white;">&nbsp;Notifications</label>
                        </div>
                    </li>
                </ul>
            </div>
    </nav>

    <div id="easyNotify"></div>

    <div id='response' class='alert alert-<?= $msgType ?> alert-dismissible fade show mt-3' role='alert'
        style='display: none;'>
        <strong id='msgText'><?= $msg ?></strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>

    <div class="content home" id="continer">
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