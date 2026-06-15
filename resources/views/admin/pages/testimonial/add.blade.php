<div class="card-body">
    <form id="validation2" action="{{route('admin::save_testimonial')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="exampleInputFile">File input</label>
                <span style="color:red;">*</span>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                    </div>
                </div>
            </div>

            @if ($errors->has('image'))
                <span class="alert alert-danger">{{ $errors->first('image') }}</span>
            @endif
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Name</label>
                <span style="color:red;">*</span>
                <input type="text" name="name" class="validate[required] form-control" placeholder="Enter ...">
            </div>
            @if ($errors->has('name'))
                <span class="alert alert-danger">{{ $errors->first('name') }}</span>
            @endif
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>Occupation</label>
                <span style="color:red;">*</span>
                <input type="text" name="occupation" class="validate[required] form-control" placeholder="Enter ...">
            </div>
            @if ($errors->has('occupation'))
                <span class="alert alert-danger">{{ $errors->first('occupation') }}</span>
            @endif
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Message</label>
                <span style="color:red;">*</span>
                <textarea name="message" class="form-control" rows="6" cols="50" placeholder="Enter ..."></textarea>
            </div>
            @if ($errors->has('message'))
                <span class="alert alert-danger">{{ $errors->first('message') }}</span>
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
