<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/css/bootstrap-switch-button.min.css" rel="stylesheet">

@extends('admin.layout.admin_layout')
@section('content')
    <div class="content-wrapper" style="min-height: 357px;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Testimonials</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin::dashboard')}}">Dashboard</a> </li>
                            <li class="breadcrumb-item active">Testimonials</li>
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
{{--                                <h3 class="card-title">Team Members</h3>--}}
                                
                                <div class="card-title" style="float: right!important;">
                                    <a href="javascript:void(0);" class="btn btn-primary btn-flat" data-act="ajax-modal" data-title="Add Testimonial" data-append-id="AjaxModelContent"
                                       data-action-url="{{route("admin::add_testimonial")}}">
                                        <i class="fa fa-plus-circle"></i> Add
                                    </a>
                                </div>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Image</th>
                                        <th style="width:20%">Name</th>
                                        <th style="width:10%">Occupation</th>
                                        <th style="width:40%">Message</th>
                                        <th>Status</th>
                                        <th style="width:10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($testimonials))
                                        <?php $i=0; ?>
                                        @foreach($testimonials as $data)
                                            <?php $i++; ?>
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                    <img src="{{asset($data['profile_photo'])}}" style="width: 120px;height: 120px;">
                                                </td>
                                                <td>
                                                    {{$data['name']}}
                                                </td>
                                                <td>
                                                    {{$data['occupation']}}
                                                </td>
                                                <td>
                                                    {{$data['message']}}
                                                </td>
                                                <td>
                                                    <span id="status{{$data->id}}">
                                                    @if($data->status == 'Active')
                                                    <a href="javascript:active_inactive_banner('<?php echo $data->id; ?>','<?php echo $data->status; ?>');" class="btn btn-success btn-sm"><span class="fa fa-check"></span> </a>&emsp;
                                                    @else
                                                    <a href="javascript:active_inactive_banner('<?php echo $data->id; ?>','<?php echo $data->status; ?>');" class="btn btn-warning btn-sm" ><span class="fa fa-ban"></span> </a>&emsp;
                                                    @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" class="btn btn-warning btn-sm btn-flat" data-act="ajax-modal" data-title="Edit Testimonial" data-append-id="AjaxModelContent"
                                                       data-action-url="{{route("admin::edit_testimonial",['id'=>$data['id']])}}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a onclick="deleteItem('{{route('admin::delete_testimonial',$data['id'])}}')" href="javascript:void(0);" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Occupation</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
@push('scripts')
<script>
    var Inactive='Inactive';
    var Active='Active';
    function active_inactive_banner(id,status) {
        $.ajax({
            type: "post",
            url: '{{route('admin::status_testimonial')}}',
            data: {
                _token: '<?php echo csrf_token();?>',
                id: id,
                status: status
            },
            success: function (data) {
                var resp = JSON.parse(data);
                $('#status' + resp.id).html(resp.html);
                $(document).find('.child #status' + resp.id).html(resp.html);
                if (resp.status === 'Active') {
                    toastr.success('Status is ' + resp.status)
                }else {
                    toastr.warning('Status is ' + resp.status)
                }
            }
        });
    }
</script>
@endpush
