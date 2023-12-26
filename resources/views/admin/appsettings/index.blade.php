@extends('layouts.admin.default')
@section('content')
@include('includes.admin.breadcrumb')
@include('includes.admin.dataTableCss')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- <div class="col-12">
            <a href="{{@url('/meeting/'.request()->segment(2).'/add-participent')}}" class="btn btn-primary  btn-sm">Add New</a>
            <a href="{{@url('/meeting/'.request()->segment(2).'/add-batch-participent')}}" class="btn btn-primary btn-sm">Add in bulk</a>
          </div> -->
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
                        <th>Key</th>
                        <th>Value</th>
                        <th width="140px">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                  <tfoot>
                  <tr>
                      <th>ID</th>
                      <th>Key</th>
                      <th>Value</th>
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
            ajax: '{{ @url("/admin/app-settings") }}',
            columns: [
                {data: 'id', name: 'id'},
                // {data: 'rownum', name: 'rownum', searchable: false },
                {data: 'skey', name: 'skey'},
                {data: 'sval', name: 'sval'},
                {data: 'action', name: 'action', orderable: false, searchable: true},
            ]
        });
      });
          
    </script>
@stop
