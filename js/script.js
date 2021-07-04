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
    var selectedDate = new Date(newMonth);
    var currentSelectedMonth = selectedDate.getMonth() + 1;

    var currentDate = new Date();
    var currentMonth = currentDate.getMonth()+1;

    if (currentSelectedMonth == currentMonth){
        window.location.replace("example.php");
    }else{
        window.location.replace("example.php?date="+newMonth);
    }
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

        if (eventAction == 'add') {
            var addText = $("#addText").val();
            var addDate = $('#addDate').val();
            var addDays = $("#addDays").val();
            var addColor = $("#addColor").val();

            xmlhttp.open("GET", "api.php?text=" + addText + "&date=" + addDate + "&days=" + addDays + "&color=" +
                addColor + "&event=" + eventAction);
            xmlhttp.send();
            // reloadWithAjax();
            // showResponse()
            // window.location.replace("example.php?date="+ $('#monthSelector').val());
            changeMonth()

        } else if (eventAction == 'edit') {

            var addText = $("#text" + eventId).val();
            var addDate = $('#date' + eventId).val();
            var addDays = $("#days" + eventId).val();
            var addColor = $("#color" + eventId).val();

            if (confirm('Please confirm the edit operation.')) {
                xmlhttp.open("GET", "api.php?text=" + addText + "&date=" + addDate + "&days=" + addDays + "&color=" +
                addColor + "&event=" + eventAction + "&eventID=" + eventId);
            xmlhttp.send();
            // reloadWithAjax();
            // showResponse()
            // window.location.replace("example.php?date="+ $('#monthSelector').val());
            changeMonth()
            }

          

        } else if (eventAction == 'remove') {

            if (confirm('THIS EVENT WILL BE DELETED PERMANENTLY. Are you sure?')) {
                xmlhttp.open("GET", "api.php?eventID=" + eventId + "&event=" + eventAction);
                xmlhttp.send();
                // reloadWithAjax();
                // showResponse()
                // window.location.replace("example.php?date="+ $('#monthSelector').val());
                changeMonth()
            }

        }

    }
}

function reloadWithAjax(){

    var selectedDate = $('#monthSelector').val();
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("continer").innerHTML = this.responseText;
           
        }          
    };

    $('.modal').modal('hide');
    xmlhttp.open("GET", "Calendar.php?date=" + selectedDate + '&isAjaxReq=true');
    xmlhttp.send(); 
}

function showResponse(){
    var msgText = document.getElementById("msgText").textContent;
    if (msgText != '') {
        $('#response').show();
    } else {
        $('#response').hide();
    }
    setTimeout(function() {
        $('#response').hide();
    }, 3000);
}

$( document ).ready(function() {
    if (toDayEventJson.length > 0) {
        for (let i = 0; i < toDayEventJson.length; i++) {
            var event = toDayEventJson[i];
            var options = {
                title: "Today Event",
                options: {
                    body: event['txt'] + ' - For ' + event['days'] + "Days(s)",
                    lang: 'en-USA',
                }
            };
            $("#easyNotify").easyNotify(options);
        }
    }
});

showResponse()