<!-- start: Breadcrumbs -->
<section class="breadcrumbs hidden-tablet hidden-phone">
    <div class="breadcrumbs">
        <i class="icon-list"></i> <a href="<?php echo base_url().'site/home';?>">Home</a><i class="icon-angle-right"></i><?php echo $this->template->title; ?>
    </div>
</section>
<section class="breadcrumbs hidden-desktop">
	<div class="subnav">
	<?php echo modules::run('site/show_subnav'); ?>
	</div>
</section>
<!-- end: Breadcrumbs -->