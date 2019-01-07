

<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Manage Album - <b><?=$cv_album->album_name?></b></h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <a class="btn btn-secondary" data-toggle="modal" data-target="#createAlbumModel" href="#">Add Item</a>
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
                                <strong class="card-title"><?=$cv_album->album_name?></strong>
                            </div>
                            <div class="card-body">
                                <table id="album_list" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Play</th>
                                            <th>Song Title</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        if($cv_items->num_rows()>0)
                                        {
                                            $i = 1;
                                            $result = $cv_items->result();
                                            foreach($result as $album)
                                            { 
                                        ?>
                                            <tr>
                                                <td><audio preload="auto" src="<?=site_url($album->path)?>"></audio></td>
                                                <td><?=$album->audio_name;?></td>
                                                <td><?=$album->status==1?"Active":"In Active";?></td>
                                                <td><?=date("d-m-Y", strtotime($album->created_at));?></td>
                                                <td><a class="update_album" data-name="<?=$album->audio_name;?>"  data-id="<?=$album->id;?>" href="#">Update</a> | <a href="#" id="remove_song" data-path="<?=$album->path;?>" data-id="<?=$album->id?>">Remove</a></td>
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
            <form class="createAlbum" >
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Add Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                            <div class="card-body card-block">
                                
                                <div class="form-group">
                                    <label for="album_name" class="form-control-label">Song Name</label>
                                    <input type="text" required name="song_name" id="album_name" placeholder="Enter Song name" class="form-control">
                                </div>
                                <div class="form-group uploader">
                                    <label for="album_cover" class="control-label">Song File<br>
                                    <input type="file" required onchange="readURL(this);" name="song_file" id="album_cover" class="form-control">
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
<div class="modal fade" id="createAlbumModel1" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="createAlbum" >
            	<input type="hidden" id="song_id" name="song_id" value="0">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Update Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                            <div class="card-body card-block">
                                <div class="form-group">
                                    <label for="song_name" class="form-control-label">Song Name</label>
                                    <input type="text" required name="song_name" id="song_name" placeholder="Enter Song name" class="form-control">
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

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="<?=base_url('assets/js/audio.min.js')?>"></script>
<script src="<?=base_url('vendors/datatables.net/js/jquery.dataTables.min.js')?>"></script>
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
            if(jQuery.inArray(ext, ['mp3']) == -1) 
            {
                swal({
                  title: "Error",
                  text: 'Un supported file type please select ("mp3")',
                  type: "error",
                  confirmButtonClass: "btn-primary",
                  confirmButtonText: "Ok"
                });
                return false;
            }
            else
            {
                /*data.append(key, value);
                if (input.files && input.files[0]) 
                {
                    var reader = new FileReader();
                    reader.onload = function (e) 
                    {
                        jQuery('#image_preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }*/
            }
        });
    }
    jQuery(document).ready(function()
    {
        $('audio').initAudioPlayer();
    	jQuery('#album_list').dataTable({"oLanguage": {"sEmptyTable": "This Album is empty please click on add item to add."}});
        jQuery(".update_album").on("click", function()
        {
            var data = jQuery(this).data();
            jQuery("#song_name").val(data.name);
            jQuery("#song_id").val(data.id);
            jQuery("#createAlbumModel1").modal("show");
        })
        jQuery(".createAlbum").on("submit", function(e)
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
        });
        jQuery("#remove_song").on("click", function()
        {
        	var data = jQuery(this).data();
        	swal({
		        title: "Are you sure?",
		        text: "You want to delete this file from album.",
		        type: "warning",
		        showCancelButton: true,
		        confirmButtonText: "Yes",
		        cancelButtonText: "No",
		        closeOnConfirm: true,
		        closeOnCancel: true 
		    },
		    function(isConfirm) 
		    {
		        if (isConfirm) 
		        {
		        	jQuery.post("<?=site_url("audio/remove-file")?>", {"path": data.path, "song_id": data.id},function(res)
		        	{
		        		var result = JSON.parse(res);
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
		        	})

		        }
		    });
        })
    })
</script>
<style type="text/css">
  .player-time, .player-bar{
    display: none;
  }
</style>