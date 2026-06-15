<div class="card-body">
    <form id="validation2" action="{{route('admin::save_cms')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <input type="hidden" name="key" class="form-control"  value="{{$data['key']}}">
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Title</label><span style="color:red;">*</span>
                    <input type="text" name="title" class="validate[required] form-control" placeholder="Enter ..." value="{{$data['title']}}">
                </div>
                @if ($errors->has('title'))
                    <span class="alert alert-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-sm-12">

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="5" cols="50" placeholder="Enter ...">{{$data['description']}}</textarea>
                </div>
                @if ($errors->has('description'))
                    <span class="alert alert-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
        </div>
        <!-- input states -->

        <div class="row">
            <div class="col-sm-2">
                <div class="custom-control custom-checkbox">
                    <input name="status" class="custom-control-input custom-control-input-info" type="checkbox" id="customCheckbox4" value="Active" @if($data['status'] == "Active") checked @endif >
                    <label for="customCheckbox4" class="custom-control-label">Status</label>
                </div>
                @if ($errors->has('status'))
                    <span class="alert alert-danger">{{ $errors->first('status') }}</span>
                @endif
            </div>
        </div>

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
