<?php
session_name('userPer');
session_start();

$connection = mysqli_connect("localhost", "root", "");
mysqli_set_charset($connection, 'utf8');
mysqli_select_db($connection, "mayfinalcal");

if (isset($_GET['event'])) {
    $action = $_GET['event'];
} else {
    $_SESSION['userMsg'] = 'Something went wrong, Please try again';
    $_SESSION['msgType'] =  'danger';
}
//c

if ($action == 'add' || $action == 'edit') {

    if (isset($_GET['text']) && isset($_GET['date']) && isset($_GET['days']) && isset($_GET['color']) && isset($_GET['event'])) {

        if ($_GET['text'] != '' && $_GET['date'] != '' && $_GET['days'] != '' && $_GET['color'] != ''  && $_GET['event'] != '') {

            $text  = ($_GET['text']);
            $date  = ($_GET['date']);
            $days  = intval($_GET['days']);
            $color = ($_GET['color']);

            $text  =  mysqli_real_escape_string($connection, $text);
            $date  =  mysqli_real_escape_string($connection, $date);
            $days  =  mysqli_real_escape_string($connection, $days);
            $color =  mysqli_real_escape_string($connection, $color);

            if ($action == "add") {
                $addEvent = "INSERT INTO events (id, txt, date, days, color) VALUES ('','$text', '$date', '$days', '$color') ";
                if (mysqli_query($connection, $addEvent)) {
                    $_SESSION['userMsg'] =  'Event Add successfully';
                    $_SESSION['msgType'] =  'success';
                }
            } elseif ($action == "edit") {

                if (isset($_GET['eventID'])) {
                    $eventID = intval($_GET['eventID']);
                    $editEvent = "UPDATE events SET  txt='$text', date='$date', days='$days', color='$color' WHERE id = $eventID";
                    if (mysqli_query($connection, $editEvent)) {
                        $_SESSION['userMsg'] =  'Event Updated successfully';
                        $_SESSION['msgType'] =  'success';
                    }
                } else {
                    $_SESSION['userMsg'] = 'Something went wrong, Please try again';
                    $_SESSION['msgType'] =  'danger';
                }
            } else {
                $_SESSION['userMsg'] = 'Something went wrong, Please try again';
                $_SESSION['msgType'] =  'danger';
            }
        } else {
            $_SESSION['userMsg'] = 'Can\'t add or edit null values';
            $_SESSION['msgType'] =  'warning';
        }
    } else {
        $_SESSION['userMsg'] = 'Something went wrong, Please try again';
        $_SESSION['msgType'] =  'danger';
    }
} elseif ($action == 'remove') {
    if (isset($_GET['eventID'])) {
        $eventID = intval($_GET['eventID']);
        $deleteEvent = "DELETE FROM events WHERE id = $eventID ";
        if (mysqli_query($connection, $deleteEvent)) {
            $_SESSION['userMsg'] =  'Event Deleted successfully';
            $_SESSION['msgType'] =  'success';
        }
    } else {
        $_SESSION['userMsg'] = 'Something went wrong, Please try again';
        $_SESSION['msgType'] =  'danger';
    }
}