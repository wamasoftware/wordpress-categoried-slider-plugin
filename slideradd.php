<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>

<script src="<?php echo site_url('/wp-content/plugins/slider/js/jquery.Jcrop.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('/wp-content/plugins/slider/css/jquery.Jcrop.css') ?>">

<br/>
<script>
    var siteUrl = '<?php echo admin_url('admin.php?page=sliderlistdata'); ?>';
    $(document).ready(function(e) {

        $("#addAlbum").click(function(e) {
            jQuery('#sliderAlumadd').show();
        });

        jQuery(function($) {
            cropimage = function() {
                $('#homeslideimageadd').Jcrop({
                    minSize: [32, 32],
                    bgFade: true, // use fade effect
                    bgOpacity: .3, // fade opacity
                    aspectRatio: 1,
                    onSelect: updateCoords
                            // onBlur: checkCoords
                });
            };
        });


        $('#imageUploadForm').on('submit', (function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log("success");
                    console.log(data);
                },
                error: function(data) {
                    console.log("error");
                    console.log(data);
                }
            });
        }));

        $("#ImageBrowse").on("change", function() {
            $("#imageUploadForm").submit();
        });
    });
        
</script>

<h3> For Adding Slider Image :  </h3>
<form class="form-horizontal" method="post" name="slideradd" id="slideradd" action="" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo isset($record['id']) ? $record['id'] : '' ?>">
    <div class="form-group">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <label class="form-font">Choose Slider Category : </label>
                        <div style="height: 6em; width: 25em; overflow: auto;">
                        <?php foreach ($result as $key => $val) { ?>
                            <input type="checkbox" name="category[]" value="<?php echo $val->category_name; ?>" <?php if($record['slider_category'] == $val->category_name){echo 'checked';} ?>/><?php echo $val->category_name; ?> <br />
                        <?php } ?>
                        </div> 
                </div>
            </div>   
        </div>      
    </div><br />
    <div class="form-group">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <label class="form-font"> Slider images:</label>
                    <?php if (isset($record['slider_image'])) { ?>
                        <img src="<?php echo site_url() . "/wp-content/uploads/" . $record['slider_image'] ?>">
                    <?php } ?>
                    <input type="file" id="sliderimages" class="form-control" name="sliderimages">
                </div>
            </div>
        </div>
    </div><br />
    <input type="hidden" name="action" value="add_slider" />
    <div class = "form-group">
        <div class="col-sm-10 button-footer donate-project-btn">
            <input type="submit" class="btn btn-default btn-submit col-sm-3 col-xs-5" value="Save" />
        </div>
    </div>
</form> 
