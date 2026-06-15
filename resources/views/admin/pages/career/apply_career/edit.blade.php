@extends('admin.layout.admin_layout')
<style>
    .content-wrapper {
        height: auto !important;
    }
    .content .card .card-body h5{
        text-transform: capitalize;
        font-weight: bold;
    }
    .content .card .card-body ul{
        padding-left: 0;
    }
    .content .card .card-body ul li{
        list-style: none;
        font-weight: bold;
        margin-bottom: 3px;
    }

    .content .card .card-header h5{
        text-transform: capitalize;
        font-weight: bold;
    }
</style>
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-danger text-bold text-capitalize">view details</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
{{--                                                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>--}}
                            <li class="breadcrumb-item active">Application User</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">

                <div class="row justify-content-end">
                    <div class="float-right">
                        <a href="{{route('admin::apply_career')}}" class="btn btn-primary text-capitalize">Back</a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-danger">profile details</h5>
                                <div class="row">
                                    <div class="col-md-4 col-lg-3">
                                        <ul class="profile-details">
                                            <li>Full name- {{$datas['name']}}</li>
                                            <li>Date of Birth- {{$datas['dob']}}</li>
                                            <li>Gender- {{$datas['gender']}}</li>
                                            <li>Marital Status- {{$datas['marital_status']}}</li>

                                        </ul>
                                    </div>
                                    <div class="col-md-4 col-lg-3">
                                        <ul class="profile-details">
                                            <li>Email- {{$datas['email']}}</li>

                                        </ul>
                                    </div>
                                    <div class="col-md-4 col-lg-3">
                                        <ul class="profile-details">
                                            <li>Mobile number- {{$datas['mobile']}}</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 col-lg-3">
                                        <ul class="profile-details">
                                            <li>Phone- {{$datas['phone']}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-danger"><b>profile Address</b></h5>
                                <div class="row">
                                    <div class="col-md-4 col-lg-3">
                                        <ul class="profile-details">
                                            <li>Country- {{$datas['country']}}</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 col-lg-3">
                                        <ul class="profile-details">
                                            <li>Adress- {{$datas['address']}}</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 col-lg-3">
                                        <ul class="profile-details">
                                            <li>Current Location- {{$datas['c_location']}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-danger">Qualification</h5>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <ul class="sports-details">
                                            <li>Basic Qualification- {{$datas['degree1']}}</li>
                                            @if(!empty($datas['degree2']))
                                            <li>Higher Qualification - {{$datas['degree2']}}</li>

                                            @endif
                                            <li>Subject - {{$datas['subject']}}</li>
                                            <li>Skill - {{$datas['skill']}}</li>

                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-danger">Job Experience</h5>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <ul class="sports-details">
                                            <li>Current Company- {{$datas['current_company']}}</li>
                                            <li>Current Industry- {{$datas['current_industry']}}</li>
                                            <li>Total Experience- {{$datas['total_experience']}}</li>
                                            <li>Current Salary- {{$datas['c_salary']}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-danger">Other Detail</h5>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <ul class="sports-details">
                                            <li>Preferred Area- {{$datas['preferred_area']}}</li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-danger">Message</h5>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <ul class="sports-details">
                                            <li>Message- {{$datas['preferred_area']}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-danger">Certificate</h5>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <ul class="sports-details">
                                            <a href="" target="_blank">
                                                <button class="btn"><i class="fa fa-download"></i> Download File</button>
                                            </a>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-danger">Resume</h5>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <ul class="sports-details">
                                            <a href="" target="_blank">
                                                <button class="btn"><i class="fa fa-download"></i> Download File</button>
                                            </a>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

