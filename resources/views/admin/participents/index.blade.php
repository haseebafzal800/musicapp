@extends('layouts.admin.default')
@section('content')
@include('includes.admin.breadcrumb')
@include('includes.admin.dataTableCss')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <a href="{{@url('/meeting/'.request()->segment(2).'/add-participent')}}" class="btn btn-primary  btn-sm">Add New</a>
            <a href="{{@url('/meeting/'.request()->segment(2).'/add-batch-participent')}}" class="btn btn-primary btn-sm">Add in bulk</a>
          </div>
          <div class="col-12">
          @if(session()->has('msg'))
                <p class="alert text-center {{ session()->get('alert-class') }}">{{ session()->get('msg') }}</p>
              @endif
            <div class="card">
              <!-- <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
              </div> -->
              <!-- /.card-header -->
              
              <div class="card-body table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Meeting</th>
                        <th>Join URL</th>
                        <th>Start at</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
                 
                  <tfoot>
                  <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Meeting</th>
                        <th>Join URL</th>
                        <th>Start at</th>
                        <th width="100px">Action</th>
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
      $(function () {
        
        var table = $('#example1').DataTable({
          
            processing: true,
            serverSide: true,
            ajax: "{{ @url('meeting/'.request()->segment(2).'/participents') }}",
            columns: [
              { data: 'rownum', name: 'rownum', orderable: false, searchable: false },
                // {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'title', name: 'title'},
                {data: 'join_url', name: 'join_url'},
                {data: 'start', name: 'start'},
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
