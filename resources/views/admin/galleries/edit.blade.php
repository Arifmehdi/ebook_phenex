@extends('admin.master')
@section('title',"Admin Dashboard | Edit Gallery")

@section('body')
    <section class="pt-5">
        <div class="card">
            <div class="card-header bg-info">
                <div class="card-title">Edit Gallery </div>
            </div>
            <div class="card-body w3-light-gray">
                <form action="{{ route('galleries.update',$gallery) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method("PATCH")
                    <div class="row py-2">
                        <div class="col-12 col-md-12 m-auto card p-4">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="" placeholder="Title.." name="title" value=" {{  $gallery->title }}">
                                @error('title')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="designation">Designation</label>
                                <input type="text" class="form-control @error('designation') is_invalid @enderror" id="" placeholder="Designation.." name="designation" value=" {{  $gallery->designation }}">
                                @error('designation')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="featuredImage">Featured Image</label>
                               <input type="file" name="featuredImage" class="form-control"><br>
                               <img src="{{ route('imagecache', [ 'template'=>'sbixs','filename' => $gallery->fi() ]) }}" alt="">
                            </div>

                            {{-- <div class="form-group">
                                <label for="exampleInputPassword1">Images(Multiple Select)</label>
                               <input type="file" name="extraImages[]" class="form-control" multiple>
                            </div>
                            <div class="row">
                                @foreach ($gallery->images as $image)
                                    <div class="col-6 cardRemove">
                                        <div class="card">
                                            <div class="card-image">
                                                <img src="{{ route('imagecache', [ 'template'=>'sbixs','filename' => $image->fi() ]) }}" alt="">
                                            </div>
                                            <div class="card-body w3-light-gray">
                                                <textarea class="form-control itemDescirption" rows="5" data-url="{{ route('itemDescriptionUpdate',$image->id) }}">{{ $image->description }}</textarea>
                                            </div>
                                            <div class="card-footer p-0 text-center">
                                                <button type="button" class="btn btn-warning" id="ImageItemDelete" data-url="{{route('imageItemDelete',$image) }}" > <i class="fa fa-trash text-danger" ></i> </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div> --}}

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="active" name="active" {{ $gallery->active ? "checked" : "" }}>
                                <label class="form-check-label" for="active">Active</label>
                            </div>

                            {{-- <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="" name="published" {{ $gallery->status == "published" ? "checked" : "" }}>
                                <label class="form-check-label" for="exampleCheck1">Published</label>
                            </div> --}}

                            <input type="submit" class="btn btn-success mt-2" value="Update">

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $(document).on("change",'.itemDescirption',function(){

        var that = $(this);
        var url  = that.attr('data-url');
        var data = that.val();
        $.ajax({
           url: url,
            type: 'GET',
            dataType: 'json',
            cache: false,
             data:{
                data : data
            }
        })
    })


    $(document).on("click",'#ImageItemDelete',function(event){
        var that = $(this);
        var url  = that.attr('data-url');
        var data = that.val();
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            cache: false,
            data:{
             data : data
            }
        })
            .done(function(data){
            that.closest('.cardRemove').remove();
        });
    })


});
</script>

@endpush
