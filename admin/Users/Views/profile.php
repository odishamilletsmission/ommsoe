<style>
    .image-container {
        position: relative;
    }

    .image {
        opacity: 1;
        display: block;
        width: 100%;
        height: auto;
        transition: .5s ease;
        backface-visibility: hidden;
    }

    .middle {
        transition: .5s ease;
        opacity: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
    }

    .image-container:hover .image {
        opacity: 0.3;
    }

    .image-container:hover .middle {
        opacity: 1;
    }
</style>
<section class="content">
<?php echo form_open_multipart('', 'id="form-user"'); ?>

    <div class="block">

        <div class="block-content block-content-full">
            <div class="card-title mb-4">
                <div class="d-flex justify-content-start">
                    <div class="image-container">
                        <img src="<?=$profile_image?>" id="imgProfile" style="width: 150px; height: 150px" class="img-thumbnail" />
                        <div class="middle dm-uploader">
                            <input type="button" class="btn btn-secondary" id="btnChangePicture" value="Change" />
                            <input type="file" style="visibility: hidden;" id="profilePicture" name="file" />
                        </div>
                        <div>
                            <span class="status"></span>
                        </div>
                    </div>
                    <div class="userData ml-3">
                        <h2 class="d-block" style="font-size: 1.5rem; font-weight: bold"><a href="javascript:void(0);"><?=$agency_name?></a></h2>
                        <h6 class="d-block"><?=$district?></h6>
                        <h6 class="d-block"><?=$block?></h6>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="basicInfo-tab" data-toggle="tab" href="#basicInfo" role="tab" aria-controls="basicInfo" aria-selected="true">Basic Info</a>
                        </li>
                        <!--<li class="nav-item">
                            <a class="nav-link" id="connectedServices-tab" data-toggle="tab" href="#connectedServices" role="tab" aria-controls="connectedServices" aria-selected="false">Connected Services</a>
                        </li>-->
                    </ul>
                    <form action="" method="post">
                        <div class="tab-content ml-1" id="myTabContent">
                        <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicInfo-tab">


                            <div class="row">
                                <div class="col-sm-3 col-md-2 col-5">
                                    <label style="font-weight:bold;">Agency Name</label>
                                </div>
                                <div class="col-md-8 col-6">
                                    <input name="agency_name" class="form-control" value="<?=$agency_name?>">
                                </div>
                            </div>
                            <hr />

                            <div class="row">
                                <div class="col-sm-3 col-md-2 col-5">
                                    <label style="font-weight:bold;">User name</label>
                                </div>
                                <div class="col-md-8 col-6">
                                    <input name="firstname" class="form-control" value="<?=$firstname?>">
                                </div>
                            </div>
                            <hr />


                            <div class="row">
                                <div class="col-sm-3 col-md-2 col-5">
                                    <label style="font-weight:bold;">Email</label>
                                </div>
                                <div class="col-md-8 col-6">
                                    <input name="email" class="form-control" value="<?=$email?>">
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-3 col-md-2 col-5">
                                    <label style="font-weight:bold;">Phone</label>
                                </div>
                                <div class="col-md-8 col-6">
                                    <input name="phone" class="form-control" value="<?=$phone?>">
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-3 col-md-2 col-5">
                                    <label style="font-weight:bold;">Password</label>
                                </div>
                                <div class="col-md-8 col-6">
                                    <?php $invalid_class=''; $error = $validation->getError('password'); if($error) {$invalid_class='is-invalid';} ?>
                                    <input type="password" name="password" class="form-control <?=$invalid_class?>" value="">
                                    <div class="invalid-feedback"><?=$error?></div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-3 col-md-2 col-5">
                                    <label style="font-weight:bold;">Confirm Password</label>
                                </div>
                                <div class="col-md-8 col-6">
                                    <?php $invalid_class=''; $error = $validation->getError('password_confirm'); if($error) {$invalid_class='is-invalid';} ?>
                                    <input type="password" name="password_confirm" class="form-control <?=$invalid_class?>" value="">
                                    <div class="invalid-feedback"><?=$error?></div>
                                </div>
                            </div>

                        </div>
                        <!--<div class="tab-pane fade" id="connectedServices" role="tabpanel" aria-labelledby="ConnectedServices-tab">
                            Facebook, Google, Twitter Account that are connected to this account
                        </div>-->
                    </div>
                        <div class="mt-3 pull-right"><button class="btn btn-primary">Save</button></div>
                    </form>
                </div>
            </div>


        </div>

    </div>
</section>
<?php echo form_close(); ?>
<?php js_start(); ?>
<script>
    $(document).ready(function () {
        $imgSrc = $('#imgProfile').attr('src');

        $('#btnChangePicture').on('click', function () {
            // document.getElementById('profilePicture').click();
            $('#profilePicture').click();
        });
    });
    $('.dm-uploader').dmUploader({
        dnd:false,
        url: '<?=$upload_url?>',
        dataType:'json',
        maxFileSize: 1000000, // 1MB
        multiple: false,
        allowedTypes: 'image/*',
        extFilter: ['jpg','png','jpeg','JPG','PNG','JPEG'],
        onInit: function(){
            // Plugin is ready to use
//            console.log('initialized')
        },
        onComplete: function(){
            // All files in the queue are processed (success or error)
            $('#image-container').LoadingOverlay("hide");
        },
        onNewFile: function(id, file){
            // When a new file is added using the file selector or the DnD area
            show_error(this,'')
        },
        onBeforeUpload: function(id){
            // about tho start uploading a file

        },
        onUploadCanceled: function(id) {
            // Happens when a file is directly canceled by the user.
        },
        onUploadProgress: function(id, percent){
            // Updating file progress
            $('#image-container').LoadingOverlay("show");
        },
        onUploadSuccess: function(id, data){
            // A file was successfully uploaded server response
            if(data.status) {
//                $(this).find('.status').html(data.message);
                $('#imgProfile').attr('src', data.image);
            } else {
                show_error(this,data.message);
            }

        },
        onUploadError: function(id, xhr, status, message){
            show_error(this,message)
            $('#image-container').LoadingOverlay("hide");
        },
        onFileSizeError: function(file){
            // file.name
            show_error(this,'Invalid file size')
        },
        onFileExtError: function(file){
            // file.name
            show_error(this,'Invalid file type')
        },
        onFileTypeError: function(file){
            // file.name
            show_error(this,'Invalid file type')

        }
    });

    function show_error(obj,msg){
        $('.status').addClass('text-danger').text(msg)
    }
</script>
<?php js_end(); ?>