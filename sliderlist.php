<html>
    <head>
        <title>listing</title>
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
        <script src="<?php echo site_url('/wp-content/plugins/slider/js/jquery.dataTables.min.js') ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('/wp-content/plugins/slider/css/jquery.dataTables.min.css') ?>">
    </head>
    <script>
        var siteUrl = '<?php echo admin_url('admin.php?page=sliderlistdata'); ?>';
        $(document).ready(function() {
            $("#slider-table").dataTable({
                "bProcessing": true,
                "bServerSide": false,
                // "sAjaxSource": siteUrl,
                "aaSorting": [[0, "desc"]],
                "aoColumnDefs": [
                      {"bSortable": false, "aTargets": [2,3]},
                ],
                "fnServerData": function(sSource, aoData, fnCallback) {
                    $.ajax({
                        "dataType": 'json',
                        "type": "GET",
                        "url": sSource,
                        "data": aoData,
                        "success": fnCallback
                    });
                }
            });
        });
    </script>
    <body>
        <br/>
        <a class="button-primary" href="<?php echo admin_url('admin.php?page=slideradd'); ?>">Add Slider Image</a><br /><br /><br />
        <table border="1" id="slider-table">
            <thead>
            <th>SLIDER CATEGORY</th>
            <th>IMAGE</th>
            <th>UPDATE</th>
            <th>DELETE</th>
        </thead>
        <tbody> 
            <?php foreach ($result as $key => $val) { ?>
                <tr>
                    <td><center><a href="<?php echo admin_url('admin.php?page=addsliderimage&id=' . $val->id); ?>"><?php echo $val->slider_category; ?></a></center></td>
                    <td><center><img src="<?php echo site_url() . "/wp-content/uploads/" . $val->slider_image ?>" height="100" width="100"></center></td>
                    <td><center><a href="<?php echo admin_url('admin.php?page=slideradd&id=' . $val->id . '&method=update'); ?>">Update</a></center></td>
                    <td><center><a href="<?php echo admin_url('admin.php?page=slideradd&id=' . $val->id . '&method=delete'); ?>" class="deleteRecord">Delete</a></center></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>