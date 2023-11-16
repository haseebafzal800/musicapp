@extends('layouts.admin.default')
@section('content')
@include('includes.admin.breadcrumb')
   <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
        <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">{{@$pageTitle}}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" method="post" action="#" enctype="multipart/form-data">
                <div class="card-body">
                @csrf
                <input type="hidden" name="id" value="{{ $item->id }}" id="id">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="start">Start At</label>
                        <input type="datetime-local" name="start" min="{{date('Y-m-d H:i')}}" required  value="{{ $item->start }}" class="form-control" id="start" placeholder="Enter Mata Tags">
                        @error('start')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="end">End At</label>
                        <input type="datetime-local" name="end" min="{{date('Y-m-d H:i')}}" required  value="{{ $item->end }}" class="form-control" id="end" placeholder="Enter Mata Tags">
                        @error('end')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="meetingTitle">Meeting Topic</label>
                    <input required type="text" value="{{ $item->title }}" name="meetingTitle" class="form-control" id="meetingTitle" placeholder="Meeting Title/Topic">
                    @error('meetingTitle')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="meetingDescription">Meeting Description</label>
                    <input  required type="text" value="{{ $item->description }}" name="meetingDescription" class="form-control" id="meetingDescription" placeholder="Meeting Description/Agenda">
                    @error('meetingDescription')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  
                  <div class="form-group">
                    <label for="meetingPassword">Meeting Password</label>
                    <input required type="text" value="{{ $item->meeting_password }}" name="meetingPassword" class="form-control" id="meetingPassword" placeholder="Meeting Password">
                    @error('meetingPassword')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" value="submit">
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  

  @include('includes.admin.footer')
  @include('includes.admin.scripts')
  @include('includes.admin.validationScript')
  <!-- Summernote -->
<script src="{{@url('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<link rel="stylesheet" href="{{@url('admin/plugins/summernote/summernote-bs4.min.css')}}">
 

  <script>
$(function () {
  $('#summernote').summernote({height: 300})
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.validator.setDefaults({
    submitHandler: function () {
      var SITEURL = "{{ url('/') }}";
      // e.preventDefault()
      var title = $('#meetingTitle').val();
      var id = $('#id').val();
      var description = $('#meetingDescription').val();
      var start = $('#start').val();
      var end = $('#end').val();
      var password = $('#meetingPassword').val();
      // console.log(password); return;
      $.ajax({
          url: SITEURL + "/fullcalenderAjax",
          data: {
              id: id,
              title: title,
              description: description,
              start: start,
              end: end,
              password: password,
              type: 'update'
          },
          type: "POST",
          success: function (data) {
              $('#meetingTitle').val('');
              $('#meetingDescription').val('');
              $('#start').val('');
              $('#end').val('');
              $('#meetingPassword').val('')
              // displayMessage("Event Created Successfully");
              // $('#myModal').modal('hide');
              displayMessage("Meeting Updated Successfully")
              // return;
              setTimeout(function(){
                window.location.href=SITEURL+'/meetings'
              }, 5000);

          },
          error: function(response) { 
            displayError(response);
            return false;
          } 
      });
      // $('#quickForm').submit();    
    }
  });
  $('#quickForm').validate({
    rules: {
      title: {
        required: true,
        minlength: 5
      },
      description: {
        required: true,
        minlength: 30
      },
      summernote: {
        required: true
      },
      tag_id: {
        required: true
      },
      image: {
            required: true,
            extension: "jpg|jpeg|png"
        }
    },
    messages: {
      email: {
        required: "Please enter a email address",
        email: "Please enter a valid email address"
      },
      image: {
            required: "Please upload file.",
            extension: "Please upload file in these format only (jpg, jpeg, png)."
        },
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      summernote: "Description is required",
      tag_id: "Please select a tag",
      terms: "Please accept our terms"
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
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
