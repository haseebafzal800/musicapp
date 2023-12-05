@extends('layouts.admin.default')
@section('content')
@include('includes.admin.breadcrumb')
@include('includes.admin.dataTableCss')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
          @if(session()->has('msg'))
                <p class="alert text-center {{ session()->get('alert-class') }}">{{ session()->get('msg') }}</p>
              @endif
            <div class="card">
              <!-- <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
              </div> -->
              <!-- /.card-header -->
              
              <div class="card-body table-responsive ">
                <table id="example1" class="table table-bordered table-striped ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <!-- <th>Description</th> -->
                        <th>Start at</th>
                        <th>Host Email</th>
                        <!-- <th width="10%">Start URL</th> -->
                        <th>Join URL</th>
                        <th>Password</th>
                        <th>Time Zone</th>
                        <th style="width: 50px !important;" >Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
                 
                  <tfoot>
                  <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <!-- <th>Description</th> -->
                    <th>Start at</th>
                    <th>Host Email</th>
                    <!-- <th>Start URL</th> -->
                    <th>Join URL</th>
                    <th>Password</th>
                    <th>Time Zone</th>
                    <th style="width: 50px !important;" >Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
  </div>
  <!-- /.content-wrapper -->

  @include('includes.admin.footer')
  @include('includes.admin.scripts')
  @include('includes.admin.dataTableScripts')
    <script type="text/javascript">
      function DeleteMeeting(btn, url){
          var id = $(btn).attr('id');
          var tr = $(btn).closest('tr');
          $.ajaxSetup({
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          Swal.fire({
            title: "Once removed, Can't be recovered?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: `Confirm`,
            denyButtonText: `Cancel`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              $.post(url, {id: id, type: 'delete'}, function(resp){
                if(resp){
                  $(tr).hide();
                  // $(tr).remove().draw( false );
                  $(tr).remove();
                  Swal.fire('Deleted!', '', 'success')
                }else{
                  Swal.fire('Something went wrong, try again!', '', 'warning')
                }
              })
            }
          })
        }
      $(function () {

        
        
        var table = $('#example1').DataTable({
          
            processing: true,
            serverSide: true,
            ajax: "{{ route('meetings') }}",
            columns: [
              { data: 'rownum', name: 'rownum', orderable: false, searchable: false },
                {data: 'title', name: 'title'},
                // {data: 'description', name: 'description'},
                {data: 'start', name: 'start'},
                {data: 'host_email', name: 'host_email'},
                // {data: 'meeting_start_url', name: 'meeting_start_url'},
                {data: 'meeting_join_url', name: 'meeting_join_url'},
                {data: 'meeting_password', name: 'meeting_password'},
                {data: 'meeting_timezone', name: 'meeting_timezone'},
                {data: 'action', name: 'action', orderable: false, searchable: true},
            ]
        });
        table.on('draw.dt', function () {
            table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        });
        //console.log(table);
      });
          
    </script>
@stop
