<div class="blog-area kelebihan-kisel dotted-style-2  pb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h3><?php echo ucwords($label[2]->name); ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($kelebihan as $kel) { ?>
            <div class="col-sm-3">
                <div class="single-blog">
                    <div class="blog-img">
                        <img src="<?php echo $kel->iconFile; ?>" alt="<?php echo $kel->name; ?>" />
                    </div>
                    <div class="blog-content-inner">
                        <div class="blog-content">
                            <h4><?php echo $kel->name; ?></h4>
                            <p class="mb-0"><?php echo $kel->description; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>