<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Audio Albums</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <a class="btn btn-secondary" data-toggle="modal" data-target="#createAlbumModel" href="#">Create Album</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Album List</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Album Name</th>
                                            <th>Price</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        if($cv_albums->num_rows()>0)
                                        {
                                            $i = 1;
                                            $result = $cv_albums->result();
                                            foreach($result as $album)
                                            { 
                                        ?>
                                            <tr>
                                                <td><?=$i++?></td>
                                                <td><a class="btn btn-link btn-lg active" href="<?=site_url('audio/manage-audio-album/'.$album->id)?>"><?=$album->album_name;?></a></td>
                                                <td><?=$album->price;?></td>
                                                <td><?=date("d-m-Y", strtotime($album->created_at));?></td>
                                                <td><a class="update_album" data-name="<?=$album->album_name;?>" data-price="<?=$album->price;?>" data-id="<?=$album->id;?>" data-album_cover="<?=base_url(AUDIO_ALBUM_PATH.'/'.$album->album_cover);?>" href="#">Update</a> | <a href="<?=site_url('audio/manage-audio-album/'.$album->id)?>">manage</a></td>
                                            </tr>
                                    <?php }} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
<div class="modal fade" id="createAlbumModel" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="createAlbum" >
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Create Album</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                            <div class="card-body card-block">
                                <input type="hidden" id="album_id" name="album_id" value="0">
                                <div class="form-group">
                                    <label for="album_name" class="form-control-label">Album Name</label>
                                    <input type="text" required name="album_name" id="album_name" placeholder="Enter Album name" class="form-control">
                                </div>
                                <div class="form-group uploader">
                                    <label for="album_cover" class="control-label">Album Cover<br>
                                    <img id="image_preview" width="200" height="300" src="<?=base_url("images/default_album_cover.png")?>" style="cursor:pointer"></label>
                                    <div class="col-sm-9" style="display: none;">
                                        <input type="file" onchange="readURL(this);" name="album_cover" id="album_cover" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="price" class="form-control-label">Price</label>
                                    <input type="number" min="0" required name="price" id="price" placeholder="Price" class="form-control">
                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?=base_url('vendors/datatables.net/js/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/js/init-scripts/data-table/datatables-init.js')?>"></script>
<script type="text/javascript">
    var files;
    var file_name= '';
    var data = new FormData();
    function readURL(input) 
    {
        files = event.target.files;
        jQuery.each(files, function(key, value)
        {
            file_name = value.name;
            var ext = (value.name).split('.').pop().toLowerCase();
            if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
            {
                swal({
                  title: "Error",
                  text: 'Un supported file type please select ("gif","png","jpg","jpeg")',
                  type: "error",
                  confirmButtonClass: "btn-primary",
                  confirmButtonText: "Ok"
                });
                return false;
            }
            else
            {
                data.append(key, value);
                if (input.files && input.files[0]) 
                {
                    var reader = new FileReader();
                    reader.onload = function (e) 
                    {
                        jQuery('#image_preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    }
    jQuery(document).ready(function()
    {
        jQuery(".update_album").on("click", function()
        {
            var data = jQuery(this).data();
            jQuery("#album_name").val(data.name);
            jQuery("#price").val(data.price);
            jQuery("#album_id").val(data.id);
            jQuery('#image_preview').attr('src', data.album_cover);
            jQuery("#createAlbumModel").modal("show");
        })
        jQuery("#createAlbum").on("submit", function(e)
        {
            e.preventDefault();
            show_loading(1);
            var formData = new FormData(this);
            jQuery.ajax({
                type:'POST',
                url: "",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success:function(data){
                    show_loading(0);
                    var result = JSON.parse(data);
                    swal({
                      title: ucFirst(result.alert_type),
                      text: result.message,
                      type: result.alert_type,
                      confirmButtonClass: "btn-primary",
                      confirmButtonText: "Ok"
                    },
                    function(){
                        if(result.error == false)
                            window.location.reload();
                    });
                },
                error: function(data){
                    console.log("error");
                    console.log(data);
                }
            });
        })
    })
</script>