$(".eventAdder").click(function() {
    var attrDate = $(this).attr("data-AttDate");
    $('.addDate').val(attrDate);
});

$(".addEvent").click(function() {
    addEvent('add', '0');
});

$(".editEvent").click(function() {
    var eventId = $(this).attr("data-eventID");
    addEvent('edit', eventId);
});

$(".removeEvent").click(function() {
    var eventId = $(this).attr("data-eventID");
    addEvent('remove', eventId);
});

function changeMonth(){
    var newMonth = $('#monthSelector').val();
    window.location.href = "example.php?date="+newMonth;
}

function addEvent(eventAction, eventId) {

    if (eventAction == "") {
        document.getElementById("response").innerHTML = "Event Calendar22";
        return;
    } else {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("response").innerHTML = this.responseText;
            }
        };


        // document.getElementById('closeAddModal').click();

        if (eventAction == 'add') {
            var addText = $("#addText").val();
            var addDate = $('#addDate').val();
            var addDays = $("#addDays").val();
            var addColor = $("#addColor").val();

            xmlhttp.open("GET", "api.php?text=" + addText + "&date=" + addDate + "&days=" + addDays + "&color=" +
                addColor + "&event=" + eventAction);
            xmlhttp.send();
            window.location.replace("example.php");

        } else if (eventAction == 'edit') {

            var addText = $("#text" + eventId).val();
            var addDate = $('#date' + eventId).val();
            var addDays = $("#days" + eventId).val();
            var addColor = $("#color" + eventId).val();

            xmlhttp.open("GET", "api.php?text=" + addText + "&date=" + addDate + "&days=" + addDays + "&color=" +
                addColor + "&event=" + eventAction + "&eventID=" + eventId);
            xmlhttp.send();
            window.location.replace("example.php");

        } else if (eventAction == 'remove') {
            xmlhttp.open("GET", "api.php?eventID=" + eventId + "&event=" + eventAction);
            xmlhttp.send();
            window.location.replace("example.php");

        }

    }
}

var msgText = document.getElementById("msgText").textContent;
if (msgText != '') {
    $('#response').show();
} else {
    $('#response').hide();
}

setTimeout(function() {
    $('#response').hide();
}, 3000);