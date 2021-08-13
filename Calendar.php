<?php
session_name('userPer');
session_start();

class Calendar
{

    private $active_year, $active_month, $active_day, $dateValue;
    private $events = [];
    private $addEvents = [];


    public function __construct($date = null)
    {
        if (isset($_GET['date'])) {
            $newMonth = $_GET['date'];
            if (!empty($newMonth)) {
                $newMonth = explode("-", $newMonth);
                $this->active_year = $newMonth[0];
                $this->active_month = $newMonth[1];
                $this->active_day = 0;
                $this->dateValue = $newMonth[0] . "-" . $newMonth[1];
            } else {
                $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
                $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
                $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
                $this->dateValue = $this->active_year . '-' . $this->active_month;
            }
        } else {
            $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
            $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
            $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
            $this->dateValue = $this->active_year . '-' . $this->active_month;
        }
    }

    public function add_event($id, $txt, $date, $days = 1, $color = '')
    {
        $color = $color ? ' ' . $color : $color;
        $this->events[] = [$id, $txt, $date, $days, $color];
    }

    public function __toString()
    {
        $_SESSION['userPer'] = 'editor';
        $userType = $_SESSION['userPer'];

        $num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
        $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= '<span>' . date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day)) . '</span>';
        // $html .= '<br><input id="bday-month" type="month" name="bday-month" value="2017-06">';

        $html .= '<span class="monthSelector"><strong>Select month </strong> ';
        $html .= '<input type="month" id="monthSelector" onchange="changeMonth()" value="' . $this->dateValue . '"></span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';

        foreach ($days as $day) {
            $html .= '
                <div class="day_name">
                    ' . $day . '
                </div>
            ';
        }
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $html .= '
                <div class="day_num ignore">
                    ' . ($num_days_last_month - $i + 1) . '
                </div>
            ';
        }
        for ($i = 1; $i <= $num_days; $i++) {
            $selected = '';
            if ($i == $this->active_day) {
                $selected = ' selected';
            }

            if ($i < 10) {
                $attData = '0' . $i;
            } else {
                $attData = $i;
            }

            $html .= '<div class="day_num' . $selected . '">';
            $html .= '<span><a class="eventAdder" href="javascript:undefined;" data-AttDate="' . $this->active_year . '-' . $this->active_month . '-' . $attData . '" data-bs-toggle="modal" data-bs-target="#AddModal">' . $i . '</a></span>';

            foreach ($this->events as $event) {



                for ($d = 0; $d <= ($event[3] - 1); $d++) {

                    if (date('y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[2]))) {
                        $html .=  ($userType == 'editor' ? '<a  data-bs-toggle="modal" data-bs-target="#modelID' . $event[0] . '">' : "");
                        $html .= '<div class="event' . $event[4] . '">';
                        $html .= $event[1];
                        $html .= '</div>';
                        $html .= '</a>';

                        $red =  "";
                        $green = "";
                        $blue =  "";
                        $yellow =  "";

                        if ($userType == 'editor') {

                            if ($event[4] == ' red') {
                                $red = "selected";
                            }
                            if ($event[4] == ' green') {
                                $green = "selected";
                            }
                            if ($event[4] == ' blue') {
                                $blue = "selected";
                            }
                            if ($event[4] == ' yellow') {
                                $yellow = "selected";
                            }

                            if (!in_array($event[0], $this->addEvents)) {
                                array_push($this->addEvents, $event[0]);
                                $html .= '                
                                    <div class="modal fade" id="modelID' . $event[0] . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <form action="" id="form' . $event[0] . '" method="post" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">' . $event[1] . '</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="mb-3">
                                                        <label for="name">Event</label>
                                                        <input type="text" id="text' . $event[0] . '" class="form-control" value="' . $event[1] . '">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="name">Date</label>
                                                        <input type="date" name="date" id="date' . $event[0] . '" class="form-control" value="' . $event[2] . '">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="name">Days</label>
                                                        <input type="number" id="days' . $event[0] . '" class="form-control" value="' . $event[3] . '">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="name">Color</label>
                                                        <select id="color' . $event[0] . '" class="form-control">
                                                            <option value="red" ' . $red . '>Red</option>
                                                            <option value="green" ' . $green . '>Green</option>
                                                            <option value="blue" ' . $blue . '>Blue</option>
                                                            <option value="yellow" ' . $yellow . '>Yellow</option>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <a  href="javascript:undefined;" class="editEvent"  data-eventID="' . $event[0] . '"><button type="button" class="btn btn-primary ">Save changes</button></a>
                                                    <a  href="javascript:undefined;" class="removeEvent"  data-eventID="' . $event[0] . '"><button type="button" class="btn btn-danger ">Remove</button></a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    ';
                            }
                        }
                    }
                }
            }
            $html .= '</div>';
        }

        for ($i = 1; $i <= (42 - $num_days - max($first_day_of_week, 0)); $i++) {
            $html .= '
                <div class="day_num ignore">
                    ' . $i . '
                </div>
            ';
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
}