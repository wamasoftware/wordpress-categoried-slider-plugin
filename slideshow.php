<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
<script src="<?php echo site_url('/wp-content/plugins/slider/js/slideshow.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('/wp-content/plugins/slider/css/slideshow.css') ?>">

<div id="slideshow">
    <?php
        $pathAndName = wp_upload_dir();
        //echo '<pre>';print_r($pathAndName[basedir]);
    ?>
    <?php foreach ($slides as $key => $val) {
    ?>
        <div>
            <img src="//farm6.static.flickr.com/5224/5658667829_2bb7d42a9c_m.jpg">
        </div>
   <?php } ?>
</div>