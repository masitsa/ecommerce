<div class="row">

	<section class="pull-left span8" id="page-sidebar">
		<!-- start: post-->
		<article class="blog-post">
			<h3 class="post-title"><a href="#"><?php echo $details->post_title; ?></a></h3>
			<div class="blog-post-inner">
				<div class="post-content">
					<div class="row-fluid">
						<ul class="post-meta">
							<li>
								<a class="post-meta-date" href="#">
								<span class="line1"><?php echo date('jS M Y',strtotime($details->submitted)); ?></span>
								</a>
							</li>
							<li><span class="post-meta-label"><i class="icon-user"></i>:</span> <a href="#"><?php echo $details->fullname; ?></a></li>
							<li><span class="post-meta-label"><i class="icon-comments"></i>:</span> <a href="#comments"><?php echo count($comments).' Comments'; ?></a></li>
						</ul>
						<?php echo $details->post_content; ?>
					</div>
				</div>
			</div>
		</article>
		<!-- end: post-->
		
		<!-- start: Comments -->
		<div id="post-comments">
			<?php echo $this->template->widget('post_comments',$details->post_uuid); ?>
		</div>
        <!-- end: Comments -->
		
        <hr/>
		
        <!-- start: Comments form -->
        <h3>Leave A Reply</h3>
        <div class="page-inner">
            <form name="comment" method="post" action="<?php echo base_url().'index.php/site/blog/comment'; ?>" class="af-form" id="af-form">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="af-outer af-required">
                            <div class="af-inner">
                                <input type="text" name="name" id="name" size="30" value="<?php echo set_value('name'); ?>" placeholder="Your Name" class="text-input span12"/>
                                <label class="error" for="name" id="name_error"><?php echo form_error('name'); ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="af-outer af-required">
                            <div class="af-inner">
                                <input type="text" name="email" id="email" size="30" value="<?php echo set_value('email'); ?>" placeholder="Your Email (Won't be published)" class="text-input span12"/>
                                <label class="error" for="email" id="email_error"><?php echo form_error('email'); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="af-outer af-required">
                            <div class="af-inner">
                                <textarea name="message" id="input-message" cols="30" placeholder="Your Message" class="text-input span12"><?php echo $_POST?$_POST['message']:''; ?></textarea>
                                <label class="error" for="input-message" id="message_error"><?php echo form_error('message'); ?></label>
								<input type="bot" name="bot_honey" id="bot_honey" class="bot-honey" placeholder="Leave Blank"/>
								<input type="hidden" name="post_uuid" id="post_uuid" value="<?php echo $details->post_uuid; ?>"/>
								<input  type="hidden" name="post_url" id="post_url" value="<?php echo $details->post_url; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="af-outer af-required">
                            <div class="af-inner">
                                <input type="submit" name="submit" class="form-button btn btn-large btn-primary" id="submit_btn" value="Post Comment"/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- end: Comments form -->
		
	</section>
	
	<?php echo $this->template->widget("post_archives"); ?>
	
</div>