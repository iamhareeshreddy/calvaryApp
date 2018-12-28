<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Video Albums</h1>
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
                                <strong class="card-title">Data Table</strong>
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
                                                <td><a class="btn btn-link btn-lg active" href="<?=site_url('manage-video-album/'.$album->id)?>"><?=$album->album_name;?></a></td>
                                                <td><?=$album->price;?></td>
                                                <td><?=$album->created_at;?></td>
                                                <td><a class="update_album" data-name="<?=$album->album_name;?>" data-price="<?=$album->price;?>" data-id="<?=$album->id;?>" href="#">Update</a> | <a href="<?=site_url('manage-video-album/'.$album->id)?>">manage</a></td>
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

<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="vendors/jszip/dist/jszip.min.js"></script>
<script src="vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="vendors/pdfmake/build/vfs_fonts.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="assets/js/init-scripts/data-table/datatables-init.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function()
    {
        jQuery(".update_album").on("click", function()
        {
            var data = jQuery(this).data();
            jQuery("#album_name").val(data.name);
            jQuery("#price").val(data.price);
            jQuery("#album_id").val(data.id);
            jQuery("#createAlbumModel").modal("show");
        })
        jQuery("#createAlbum").on("submit", function(e)
        {
            e.preventDefault();
            show_loading(1);
            jQuery.post("", jQuery(this).serialize(), function(res)
            {
                show_loading(0);
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
        })
    })
</script>