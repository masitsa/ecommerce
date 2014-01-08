<?php
$types = $this->portfolio_model->get_all_categories_unpaginated();
$cat = '';
foreach ($types as $type)
	$cat = $cat .'<li class="'.$type->name.'"><a href="#'.$type->name.'">'.$type->name.'</a></li>';
$projects = $this->portfolio_model->get_category_project_list($details->category);
?>
<!-- start: Page section -->
<section id="page-sidebar" class="span12">

	<div class="page-inner">
		<div class="row-fluid single-portfolio">
			<div class="span8">
				<div id="mainslider" class="flexslider">
					<img src=<?php echo base_url().'assets/portfolio/images/'.$details->image; ?> alt="" />
				</div>
			</div>
			<div class="span4">
				<div class="ps-description">
					<h3><?php echo $details->project; ?></h3>
					<p><?php echo $details->description; ?></p>
					<h4>Project Overview</h4>
					<ul class="icons">
						<li><i class="icon-angle-right"></i><span>Date: </span><?php echo  date("jS M Y",strtotime($details->date)); ?></li>
						<li><i class="icon-angle-right"></i><span>Client: </span> <?php echo $details->client; ?></li>
						<li><i class="icon-angle-right"></i><span>Category: </span><?php echo $this->portfolio_model->get_category_name($details->category); ?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<hr>

	<div class="row-fluid">
		<h3>Related Projects</h3>
	</div>
	<div class="row-fluid">
		<!-- start: Related Projects -->
		<section class="portfolio related-projects thumbnails">
			<?php foreach ($projects as $project) if($project->project_id != $details->project_id) { ?>
			<article data-id="id-<?php echo $project->project_id; ?>" data-type="<?php echo $this->portfolio_model->get_category_name($project->category); ?>" class="span3">
				<div class="thumbnail hover-pf10">
					<img style="width: 600px; height: 200px;" src=" <?php echo base_url().'assets/portfolio/images/'.$project->image; ?>" alt=""/>
					<div class="mask-1"></div>
					<div class="mask-2"></div>
					<div class="caption">
						<h2 class="title"><a href="#"><?php echo $project->project; ?></a></h2>
						<p><?php echo substr($project->description,0,100).'....'; ?></p>
						<a href="<?php echo base_url().'site/portfolio/project/'.$project->project_id;?>" class="info btn btn-inverse" rel="facebox">Read More</a>
					</div>
				</div>
			</article>
			<?php } ?>
		</section>
		<!-- end: Related Projects -->
    </div>

</section>
<!-- end: Page section -->