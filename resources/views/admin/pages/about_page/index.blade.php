@extends('admin.layout.admin_layout')
@section('content')
    <div class="content-wrapper" style="min-height: 357px;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">About Page</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin::dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">About Page</li>
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
                                <h3 class="card-title">About Page</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($allAbout))
                                        <?php $i = 0; ?>
                                        @foreach($allAbout as $data)
                                            <?php $i++; ?>
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                    <iframe class="w100 h100"
                                                            src="https://www.youtube.com/embed/{{$data['image']}}"
                                                            title="YouTube video player" frameborder="0"
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                            allowfullscreen>
                                                    </iframe>
                                                    {{--                                                    {{$data['image']}}--}}
                                                    {{--                                                    <img src="{{asset($data['image'])}}" style="width: 120px;height: 120px;">--}}
                                                </td>
                                                <td>
                                                    {{$data['title']}}
                                                </td>
                                                <td>
                                                    {!! $data['description'] !!}
                                                </td>
                                                <td>
                                                    <span id="status{{$data->id}}">
                                                        @if($data->status == 'Active')
                                                            <a href="javascript:active_inactive_about('<?php echo $data->id; ?>','<?php echo $data->status; ?>');"
                                                               class="btn btn-success btn-sm"><span
                                                                    class="fa fa-check"></span> </a>&emsp;
                                                        @else
                                                            <a href="javascript:active_inactive_about('<?php echo $data->id; ?>','<?php echo $data->status; ?>');"
                                                               class="btn btn-warning btn-sm"><span
                                                                    class="fa fa-ban"></span> </a>&emsp;
                                                        @endif
                                                    </span>
                                                    <a href="javascript:void(0);"
                                                       class="btn btn-warning btn-sm btn-flat" data-act="ajax-modal"
                                                       data-title="Edit About" data-append-id="AjaxModelContent"
                                                       data-action-url="{{route("admin::edit_about",['id'=>$data['id']])}}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Sl.No.</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Description</th>
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
        var Inactive = 'Inactive';
        var Active = 'Active';

        function active_inactive_about(id, status) {
            $.ajax({
                type: "post",
                url: '{{route('admin::status_about')}}',
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
                    } else {
                        toastr.warning('Status is ' + resp.status)
                    }
                }
            });
        }
    </script>
@endpush
