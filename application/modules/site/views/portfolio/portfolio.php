<?php
$types = $this->portfolio_model->get_all_categories_unpaginated();
$cat = '';
foreach ($types as $type)
	$cat = $cat .'<li class="'.$type->data_type.'"><a href="#'.$type->data_type.'">'.$type->name.'</a></li>';
$projects = $this->portfolio_model->get_all_projects();
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

<!-- start: Portfolio -->
<section class="row filtrable portfolio thumbnails">
	<?php	foreach($projects as $project) { ?>
        <article data-id="id-<?php echo $project->project_id; ?>" data-type="<?php echo $this->portfolio_model->get_category_datatype($project->category); ?>" class="span3">
            <div class="thumbnail hover-pf10">
                <img style="width: 600px; height: 200px;" src=" <?php echo base_url().'assets/portfolio/images/'.$project->image; ?>" alt=""/>
                <div class="mask-1"></div>
                <div class="mask-2"></div>
                <div class="caption">
                    <h2 class="title"><a href="#"><?php echo $project->project; ?></a></h2>
                    <p><?php echo substr($project->description,0,100).'....'; ?></p>
                    <a href="<?php echo base_url().'site/portfolio/project/'.$project->project_id;?>" class="info btn btn-inverse">Read More</a>
                </div>
            </div>
        </article>
	<?php } ?>
</section>
<!-- end: Portfolio -->