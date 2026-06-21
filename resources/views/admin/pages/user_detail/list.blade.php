<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/css/bootstrap-switch-button.min.css" rel="stylesheet">

@extends('admin.layout.admin_layout')
@section('content')
    @inject('helper','App\Helper\admin\siteInformation')
    <div class="content-wrapper" style="min-height: 357px;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Users Details</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin::dashboard')}}">Dashboard</a> </li>
                            <li class="breadcrumb-item active">Users Details</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        @include('admin.layout.message')
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">


                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sl.No.</th>

                                        <th style="width:20%">Name</th>
                                        <th style="width:10%">phone</th>
                                        <th style="width:25%">email</th>
                                        <th style="width:25%">course</th>
                                        <th style="width:20%">Submitted At</th>

                                        {{-- <th style="width:10%">Action</th> --}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($users))
                                        <?php $i=0; ?>
                                        @foreach($users as $data)
                                            <?php $i++; ?>
                                            <tr>
                                                <td>{{$i}}</td>

                                                <td>
                                                    {{$data['name']}}
                                                </td>
                                                <td>
                                                    {{$data['phone']}}
                                                </td>
                                                <td>
                                                    {{$data['email']}}
                                                </td>
                                                <td>
                                                    {{$helper->course($data['course_id'] ) }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($data['created_at'])->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Sl.No.</th>

                                        <th>Name</th>
                                        <th>phone</th>
                                        <th>email</th>
                                        <th>course</th>
                                        <th>Submitted At</th>
                                        {{-- <th>Action</th> --}}
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
        <!-- /.content -->
    </div>
@endsection

