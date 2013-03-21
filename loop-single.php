<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
	<?php $articleFormats = wp_get_post_terms($post->ID,'article-format');
	$articleFormat = $articleFormats[0]->slug;
	$displayAuthor = true;
	if(isset($articleFormat))
	{
		switch($articleFormat)
		{
			case 'brief':
				$displayAuthor = false;
				break;
		}
	}
	?>
	<?php 
	foreach(get_the_category() as $cat)
	{
		switch($cat->name)
		{
        	case "Bruin Sights":
        		the_blog_banner('bruinsights');
        		break(2);
        	case "Spotlight":
        		the_blog_banner('spotlight');
        		break(2);
        	case "Timestamp":
        		the_blog_banner('timestamp');
        		break(2);
        	case "Video":
        		$videoStory = true;
        		break(2);
		}
	}
	// Find out if this is a video story
	// Expand this to do something better for multimedia/gallery display
	$video_story = false;
	foreach(get_the_category() as $cat)
	{
		switch($cat->name)
		{
        	case "Video":
        		$video_story = true;
        		break(2);
		}
	}
	?>

    <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
    	<?php $customFields = get_post_custom(); ?>
      <div id="post-top">
		<h1 class="entry-title"><?php the_headline(); ?></h1>
		<?php $subhead = get_post_custom_values('db_subhead');
			if(isset($subhead) && $subhead[0] != ''): ?>
			<h2 class="subhead"><?php echo $subhead[0]; ?></h2>
		<?php endif; ?>
		<?php if(has_post_thumbnail() && !$video_story) : ?>
			<?php the_post_thumbnail('db-category-full'); ?>
			<span class="photocredit photocredit-single"><?php the_media_credit_html(get_post_thumbnail_id($post->ID)); ?></span>
			<span class="photocaption"><?php echo get_post(get_post_thumbnail_id($post->ID))->post_excerpt; ?></span>
		<?php endif; ?>
		<?php if($video_story): ?>
			<div class="video-story">
				<?php the_content(); ?>
			</div><!-- end div.video-story -->
		<?php endif; ?>
		<div class="infobar">
			<span class="infobar-day"><i class="ticon-calendar ticon-white"></i> <?php the_time('F j, Y'); ?></span>
			<span class="infobar-time"><i class="ticon-clock ticon-white"></i> <?php the_time('g:i a'); ?></span>
			<span class="infobar-categories">More stories in <?php the_category(", "); ?></span>
			<br style="clear:both" />
		</div>
       </div><!-- end div#post-top -->
		<div class="row entry-content">
			<div class="span2 post-extra visible-desktop">
				<ul id="post-extra-actions">
					<li><a href="https://twitter.com/share" rel="external" target="_blank" data-via="dailybruin">Tweet <i class="ticon-twitter"</a></i></li>
					<li><a href="https://www.facebook.com/sharer/sharer.php?u=http://dailybruin.com<?php the_permalink(); ?>" target="_blank" >Share <i class="ticon-facebook">	</a></i></li>
					<!--<li class="list-space"></li>
					<li class="post-extra-unimportant"><a href="#">Print <i class="ticon-printer"></a></i></li>
					<li class="post-extra-unimportant"><a href="#">Email <i class="ticon-email"></a></i></li>-->
					<br style="clear:both" />
				</ul>
			</div><!-- end div.post-extra -->
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			<div class="span6 post-content">
				<?php 
					if(function_exists('the_audio'))
						the_audio();
				?>
				<div class="span2 post-extra hidden-desktop">
					<ul id="post-extra-actions">
						<li><a href="https://twitter.com/share" rel="external" target="_blank" data-via="dailybruin">Tweet <i class="ticon-twitter"</a></i></li>
						<li><a href="https://www.facebook.com/sharer/sharer.php?u=http://dailybruin.com<?php the_permalink(); ?>" target="_blank" >Share <i class="ticon-facebook">	</a></i></li>
						<!--<li class="list-space"></li>
						<li class="post-extra-unimportant"><a href="#">Print <i class="ticon-printer"></a></i></li>
						<li class="post-extra-unimportant"><a href="#">Email <i class="ticon-email"></a></i></li>-->
						<br style="clear:both" />
					</ul>
				</div><!-- end div.post-extra -->
				<?php // Display the columnist's mugshot
				    if($displayAuthor && $articleFormat === "column")
				    {
				        ob_start();
    					if(function_exists('userphoto_the_author_thumbnail'))
    						userphoto_the_author_thumbnail();
    					$thumbnail = ob_get_contents();
    					$thumbnail_class = "";
    					ob_end_clean();
    					if(!isset($thumbnail) || $thumbnail == "")
    						the_byline(false);
    					else 
    					{
        					?><div class="author-photo"><?php echo $thumbnail; ?></div><?php
        					the_byline(false);
        					echo "<hr style='margin:5px 0;' />";
        				}
				    }
				    else
				    {
				        the_byline();
				    }
				?>
				<?php if(isset($customFields['db_infobox'])) : ?>
					<div class="db-infobox">
						<?php echo $customFields['db_infobox'][0]; ?>
					</div>
				<?php endif; ?>
				<?php if(!$video_story) { the_content(); } ?>
				<p class="author-contact">
				    <?php if(isset($customFields['db_authoremail']))
				    {
				        echo $customFields['db_authoremail'][0];
				    }
                    else if(intval(the_date('U','','',false)) <= 1361363177)
                    { ; }
				    else
				    {
				        $coauthors = get_coauthors();
				        $finalAuthorKey = count($coauthors) - 1;
				        $firstAuthor = true;
				        foreach($coauthors as $key=>$author)
				        {
				            $lastAuthor = ($finalAuthorKey == $key);
				            $lastName = get_the_author_meta('last_name', $author->ID);
				            $graduated = get_the_author_meta('graduated', $author->ID);
				            if(!isset($lastName) || $lastName == "" || !isset($author->user_email) || $graduated)
				                continue;
				            if($firstAuthor)
				                echo "Email ";
				            else
				            {
				                if($lastAuthor && $key == 1)
				                    echo " and email ";
				                else if($lastAuthor)
				                    echo ", and email ";
				                else
				                    echo ", email ";
				            }
			                echo $lastName . " at <a href='mailto:"
			                    . $author->user_email . "'>" . $author->user_email 
			                    . "</a>";
			                if($lastAuthor)
			                    echo ".";
			                $firstAuthor = false;
				        }
				    }?>
				</p>

			</div><!-- end div.post-content -->

		</div><!-- end div.entry-content -->
		<div class="row" id="entry-bottom">
			<div class="span2 about-post">
				<div class="post-tags">
					<p><?php the_tags(); ?></p>
				</div><!-- end div.post-tags -->
			</div>
			<div class="span6 about-author">
				<div class="sm">
					<a href="https://twitter.com/share" class="twitter-share-button" data-via="dailybruin" data-related="dailybruin">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					<div class="fb-like" data-send="true" data-width="325" data-show-faces="true" data-action="recommend" data-font="lucida grande"></div>
				</div><!-- end div.sm -->
				<?php if($displayAuthor && 0): ?>
					<?php // Find out if the user has a thumbnail
					ob_start();
					if(function_exists('userphoto_the_author_thumbnail'))
						userphoto_the_author_thumbnail();
					$thumbnail = ob_get_contents();
					$thumbnail_class = "";
					if(!isset($thumbnail) || $thumbnail == "")
						$thumbnail_class = "nothumb";
					ob_end_clean();
					?>
					<div class="author-info <?php echo $thumbnail_class; ?>">
						<?php echo $thumbnail; ?>
						<span class="author-about">About the Author</span>
						<span class="author-name"><?php the_author_posts_link(); ?></span>
						<?php if(!get_the_author_meta('graduated')) : ?>					
							<?php if(get_the_author_meta('twitter_handle')) : ?>
							<a href="https://twitter.com/<?php echo substr(get_the_author_meta('twitter_handle'),1); ?>" class="twitter-follow-button" data-show-count="false">Follow <?php the_author_meta('twitter_handle'); ?></a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
							<?php endif; ?>
							<a class="author-email" href="mailto:<?php the_author_meta('user_email'); ?>"><i class="ticon-email"></i>Email</a>
						<?php endif; ?>
						<p><?php the_author_meta('description'); ?></p>
					</div><!-- end div.author-info -->
				<?php endif; ?>
			</div>
		</div><!-- end div#entry-bottom -->
		<p id="comment-policy">Comments are supposed to create a forum for thoughtful, respectful community discussion. Please be nice. <a href="<?php echo get_permalink( get_page_by_path( 'comment-policy' ) ); ?>">View our full comments policy here.</a></p>
      <?php comments_template(); ?>
    </article>
<?php endwhile; /* End loop */ ?>
