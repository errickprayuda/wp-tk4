<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Videos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body>
    <div class="container text-center">
        <h1>My Videos</h1>
        
        <!-- Video Player -->
        <div class="container">
        <div class="m-3 row">
            <video width="640" height="360" controls id="player"></video>
            <source src="" id="source">
        </div>

        <!-- Modal Button -->
        <div class="m-3 row justify-content-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#upload-modal">Upload Video</button>
        </div>
        </div>

        <!-- List Video -->
        <div class="container text-start">
            <ul id="list-video">
                 @foreach ($videos as $video)
                    <li><button type="button" class="list btn btn-link" value="{{ $video->judul }}">{{ $video->judul }}</button></li>                                        
                @endforeach
            </ul>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="upload-modal" tabindex="-1" aria-labelledby="upload-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="upload-modal-label">Upload Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form action="{{route('videocontroller.store')}}" method="post" enctype="multipart/form-data" id=data>
                    @csrf
                    <div class="form-group m-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul">
                        <span class="text-danger" id="judul-error"></span>
                    </div>

                    <div class="form-group m-3">
                        <label for="video" class="form-label">Video</label>
                        <input class="form-control" type="file" id="video" name="video">
                        <span class="text-danger" id="video-error"></span>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close">Close</button>
                <button type="submit" class="btn btn-primary" id="submit">Upload</button> 
            </div>
            </form>
            </div>
        </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script
        src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous">
    </script>

    <script>
        $("form#data").submit(function(e) {
            $('#judul-error').text(null);
            $('#video-error').text(null);
        e.preventDefault();
        var formData = new FormData(this);

            $.ajax({
                url: "{{ route('videocontroller.store') }}",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success : function (res){
                    console.log(res);
                    alert(res.text)
                    location.reload(true);
                },
                error: function (xhr) {
                    console.log(xhr)
                    if(xhr.status == 422){
                        $('#judul-error').text(xhr.responseJSON.errors.judul);
                        $('#video-error').text(xhr.responseJSON.errors.video);
                }else{
                    alert(xhr.responseJSON.text);
                }
                }
            })
        })

        $(document).on('click', '.list', function(){
            var old_source = document.getElementById('source');
            old_source.remove()

            var source = document.createElement('source');
            source.setAttribute('id', 'source');

            var judul = $(this).attr('value');
            source.setAttribute('src', './storage/videos/'+judul+'');

            var player = document.getElementById('player');
            player.appendChild(source);
            player.load();
            player.play();
        })

    </script>
</body>
</html>