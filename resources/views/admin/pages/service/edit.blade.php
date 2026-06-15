<div class="card-body">
    <form id="validation2" action="{{route('admin::update_services',$data['id'])}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="exampleInputEmail1">Preview Image</label>
                    <img src="{{url($data['image'])}}" alt="No Picture Found" style="width: auto;height: 200px;object-fit: cover;">
                </div>
            </div>
        </div>
        <br>

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
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Title</label>
                    <span style="color:red;">*</span>
                    <input type="text" name="title" value="{{$data['title']}}" class="validate[required] form-control" placeholder="Enter ...">
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
                    <span style="color:red;">*</span>
                    <textarea name="description" class="form-control" rows="6" cols="50" placeholder="Enter ...">{{$data['description']}}</textarea>
                </div>
                @if ($errors->has('description'))
                    <span class="alert alert-danger">{{ $errors->first('description') }}</span>
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

