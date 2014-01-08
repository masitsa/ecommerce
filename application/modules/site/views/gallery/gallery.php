<!-- start: Page header / Breadcrumbs -->
<?php
$types = $this->gallery_model->get_type_list();
$cat = '';
foreach ($types as $type)
	$cat = $cat .'<li class="'.$type->data_type.'"><a href="#'.$type->data_type.'">'.$type->name.'</a></li>';
$gallerys = $this->gallery_model->get_all_gallerys();
?>
    <div class="row">

        <!-- start: Page section -->
        <section id="page-sidebar" class="span12">
            <!-- start: Filter-->
            <div class="row-fluid">
                <div class="span12">
                    <ul id="filtrable" class="clearfix">
                        <li class="current all"><a href="#all">All</a></li>
                        <?php echo $cat ?>
                    </ul>
                </div>
            </div>
            <!-- end: Filter-->
        </section>
        <!-- end: Page section -->

    </div>

	<!-- start: gallery -->
    <section class="row thumbnails da-thumbs portfolio filtrable clearfix ">
	<?php foreach($gallerys as $gallery) { ?>
        <article data-id="id-<?php echo $gallery->photo_id; ?>" data-type="<?php echo $this->gallery_model->get_category_datatype($gallery->gallery); ?>" class="span3">
			<div class="thumbnail">
	            <img style="width: 600px; height: 200px;" src=" <?php echo base_url().'assets/gallery/images/'.$gallery->image; ?>" alt=""/>
				<div class="pd">
					<a href="<?php echo base_url().'assets/gallery/images/'.$gallery->image; ?>" class="p-view"  data-rel="prettyPhoto"></a>
	            </div>		
			</div>
		</article>
	<?php } ?>
    </section>
    <!-- end: gallery -->