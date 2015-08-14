<h3> For Adding Slider Category : </h3>

<form class="form-horizontal" method="post" name="slidercategory" id="slidercategory" action="" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo isset($record['id']) ? $record['id'] : '' ?>">
    <div class="form-group">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <label class="form-font"> Slider Category : </label>
                    <input type="text" class="form-control" name="categoryname" id="categoryname" placeholder="Slider category" value="<?php echo isset($record['category_name']) ? $record['category_name'] : '' ?>">
                </div>
            </div>   
        </div>      
    </div><br />
    <input type="hidden" name="action" value="add_category" />
    <div class = "form-group">
        <div class="col-sm-10 button-footer donate-project-btn">
            <input type="submit" class="btn btn-default btn-submit col-sm-3 col-xs-5" value="Save" />
        </div>
    </div>
</form>