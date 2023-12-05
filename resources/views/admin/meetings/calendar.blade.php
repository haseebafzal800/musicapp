@extends('layouts.admin.default')
@section('content')
@include('includes.admin.breadcrumb')

   <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div id='calendar'></div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <style>
    .content-header{
        display: none;
    }
    nav.main-header{
        /* display: none; */
    }
  </style>
  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-md ">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Create Meeting</h4>
          <button type="button" class="close btn-mdl-cls" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <form id="quickForm" method="post" action="http://127.0.0.1:8000/zoom-settings/update" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">
                    @csrf
                    
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="start">Start At</label>
                        <input type="datetime-local" min="{{date('Y-m-d H:i')}}" required name="start" value="" class="form-control" id="start" placeholder="">
                        @error('start')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="end">End At</label>
                        <input type="datetime-local" name="end" min="{{date('Y-m-d H:i')}}" required value="" class="form-control" id="end" placeholder="">
                        @error('end')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="meetingTitle">Meeting Topic</label>
                        <input required type="text" name="meetingTitle" class="form-control" id="meetingTitle" placeholder="Meeting Title/Topic">
                                              </div>
                    </div>
                    
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="meetingDescription">Description</label>
                        <input  required type="text" name="meetingDescription" class="form-control" id="meetingDescription" placeholder="Meeting Description/Agenda">
                                              </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="meetingPassword">Meeting Password</label>
                        <input required type="text" name="meetingPassword" class="form-control" id="meetingPassword" placeholder="Meeting Password">
                                              </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="submit" class="btn btn-submit-event btn-primary" value="submit">
                        </div>
                    </div>
                  </div>
                  
                </div>
                <!-- /.card-body -->
                
                    
                </form>
            </div>
            
            <!-- Modal footer -->
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div> -->
        
      </div>
    </div>
  </div>
  @include('includes.admin.footer')
  @include('includes.admin.scripts')
  @include('includes.admin.fullcalander')

  
  <?php $selectable = false; ?>
  <?php $editable = false; ?>
  @can("meeting-create")
  <?php $selectable = true; ?>
  <?php $editable = true; ?>
  @endcan
<script>

$(document).ready(function () {
$('body').addClass('sidebar-collapse');

$('.btn-mdl-cls').click(function(){
  $('#myModal').modal('hide');
})
   
var SITEURL = "{{ url('/') }}";

$('.btn-submit-event').click(function(e){
    e.preventDefault()
    var title = $('#meetingTitle').val();
    var description = $('#meetingDescription').val();
    var start = $('#start').val();
    var end = $('#end').val();
    var password = $('#meetingPassword').val();
    $.ajax({
        url: SITEURL + "/fullcalenderAjax",
        data: {
            title: title,
            description: description,
            start: start,
            end: end,
            password: password,
            type: 'add'
        },
        type: "POST",
        success: function (data) {
            $('#meetingTitle').val('');
            $('#meetingDescription').val('');
            $('#start').val('');
            $('#end').val('');
            $('#meetingPassword').val('')
            displayMessage("Event Created Successfully");
            $('#myModal').modal('hide');

            $('#calendar').fullCalendar('renderEvent',
                {
                    id: data.id,
                    title: title,
                    start: start,
                    end: end,
                    // allDay: allDay
                },true);

            $('#calendar').fullCalendar('unselect');
        }
    });
})
  
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var calendar = $('#calendar').fullCalendar({
                    editable: '<?=$editable?>',
                    eventStartEditable: false,
                    events: SITEURL + "/meetings/calendar",
                    displayEventTime: true,
                    slotDuration: '01:00:00',
                    timeFormat: 'H:mm',
                    allDaySlot : false,
                    axisFormat: 'HH:mm',
                    // defaultView: "agendaWeek",
                    // defaultView: 'timeGridDay',
                    header: {
                        // left: "prev,next",
                        // center: "title",
                        right: "prev,month,agendaWeek,dayGridMonth,timeGridWeek,timeGridDay,next"
                    },
                    eventLimit: true,
                    eventRender: function (event, element, view) {
                        if (event.allDay === 'true') {
                                event.allDay = true;
                        } else {
                                event.allDay = false;
                        }
                    },
                    selectable: '<?=$selectable?>',
                    allDayDefault: false,

                    selectHelper: true,
                    select: function (start, end, allDay) {
                        if(start.isBefore(moment())) {
                            $('#calendar').fullCalendar('unselect');
                            displayError('Operation not allowed in previous dates', 'Error !');
                            return false;
                        }
                        // var title = prompt('Meeting Details:');
                        var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD HH:mm:ss");
                        // Parse the date string into a Date object
                        var originalDate = new Date(start);

                        // Add 1 hour to the date
                        originalDate.setHours(originalDate.getHours() + 1);

                        var year = originalDate.getFullYear();
                        var month = ("0" + (originalDate.getMonth() + 1)).slice(-2);
                        var day = ("0" + originalDate.getDate()).slice(-2);
                        var hours = ("0" + originalDate.getHours()).slice(-2);
                        var minutes = ("0" + originalDate.getMinutes()).slice(-2);
                        var seconds = ("0" + originalDate.getSeconds()).slice(-2);

                        // Format the date and time
                        var end = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
                        // var end = $.fullCalendar.formatDate(end, "YYYY-MM-DD HH:mm:ss");

                        
                        $('#start').val(start);
                        $('#end').val(end);
                        $('#myModal').modal('show');
                        
                        // if (title) {
                        //     var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD HH:mm:ss");
                        //     var end = $.fullCalendar.formatDate(end, "YYYY-MM-DD HH:mm:ss");
                        //     $.ajax({
                        //         url: SITEURL + "/fullcalenderAjax",
                        //         data: {
                        //             title: title,
                        //             start: start,
                        //             end: end,
                        //             type: 'add'
                        //         },
                        //         type: "POST",
                        //         success: function (data) {
                        //             displayMessage("Event Created Successfully");
  
                        //             calendar.fullCalendar('renderEvent',
                        //                 {
                        //                     id: data.id,
                        //                     title: title,
                        //                     start: start,
                        //                     end: end,
                        //                     allDay: allDay
                        //                 },true);
  
                        //             calendar.fullCalendar('unselect');
                        //         }
                        //     });
                        // }
                    },
                    eventDrop: function (event, delta) {
                        var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
                        var end = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD HH:mm:ss");
  
                        $.ajax({
                            url: SITEURL + '/fullcalenderAjax',
                            data: {
                                title: event.title,
                                start: start,
                                end: end,
                                id: event.id,
                                type: 'update'
                            },
                            type: "POST",
                            success: function (response) {
                                displayMessage("Event Updated Successfully");
                            }
                        });
                    },
                    eventClick: function (event) {
                      if('<?=$editable?>'){
                        var deleteMsg = confirm("Do you really want to delete?");
                        if (deleteMsg) {
                            $.ajax({
                                type: "POST",
                                url: SITEURL + '/fullcalenderAjax',
                                data: {
                                        id: event.id,
                                        type: 'delete'
                                },
                                success: function (response) {
                                    calendar.fullCalendar('removeEvents', event.id);
                                    displayMessage("Event Deleted Successfully");
                                }
                            });
                        }
                      }else{
                        return false;
                      }
                    }
 
                });
 
});
 
function displayMessage(message) {
    toastr.success(message, 'Event');
} 

function displayError(message) {
    toastr.error(message, 'Error!');
} 
  
</script>
  
@stop
