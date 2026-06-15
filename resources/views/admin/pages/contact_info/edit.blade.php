<div class="card-body">
    <form id="validation2" action="{{route('admin::update_contact_info',$data['id'])}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Email</label><span style="color:red;">*</span>
                    <input type="text" name="email" class="validate[required] form-control" placeholder="Enter ..." value="{{$data['email']}}">
                </div>
                @if ($errors->has('email'))
                    <span class="alert alert-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>

        <div class="col-sm-6">
                <div class="form-group">
                    <label>Website</label><span style="color:red;">*</span>
                    <input type="text" name="website" class="validate[required] form-control" placeholder="Enter ..." value="{{$data['website']}}">
                </div>
                @if ($errors->has('website'))
                    <span class="alert alert-danger">{{ $errors->first('website') }}</span>
                @endif

            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Phone Code</label><span style="color:red;">*</span>
                    <input type="text" name="phone_code" class="validate[required] form-control" placeholder="Enter ..." value="{{$data['phone_code']}}">
                </div>
                @if ($errors->has('phone_code'))
                    <span class="alert alert-danger">{{ $errors->first('phone_code') }}</span>
                @endif

            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>Phone</label><span style="color:red;">*</span>
                    <input type="text" name="phone" class="validate[required] form-control" placeholder="Enter ..." value="{{$data['phone']}}">
                </div>
                @if ($errors->has('phone'))
                    <span class="alert alert-danger">{{ $errors->first('phone') }}</span>
                @endif

            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-sm-12">

                <div class="form-group">
                    <label>Address</label><span style="color:red;">*</span>
                    <textarea name="address" class="validate[required] form-control" rows="5" cols="50" placeholder="Enter ...">{{$data['address']}}</textarea>
                </div>
                @if ($errors->has('address'))
                    <span class="alert alert-danger">{{ $errors->first('address') }}</span>
                @endif
            </div>
        </div>
        <!-- input states -->

        <div class="row">

            <div class="col-md-12">
                <div class="form-group" style="text-align:right">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><span class="fa fa-close"></span> Close</button>
                    <button type="submit" class="btn btn-primary btn-flat"><span class="fa fa-check-circle"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    jQuery("#validation2").validationEngine({promptPosition: 'inline'});
    $('.select2').select2();
</script>
