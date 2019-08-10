<?php
/*
 * Template Name: Team Members Template
 * Description: A Page Template with a darker design.
 */

get_header(); ?>



	<section id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<header class="page-header">					
				<h1 class="archive-title"><?php the_title(); ?></h1>
			</header>

			<ul class="team_members">		

				<?php $args = array(
					'posts_per_page'   => 12,
					'offset'           => 0,
					'cat'         => '',
					'category_name'    => '',
					'orderby'          => 'date',
					'order'            => 'DESC',
					'include'          => '',
					'exclude'          => '',
					'meta_key'         => '',
					'meta_value'       => '',
					'post_type'        => 'team-members',
					'post_mime_type'   => '',
					'post_parent'      => '',
					'author'	   => '',
					'author_name'	   => '',
					'post_status'      => 'publish',
					'suppress_filters' => true,
					'fields'           => '',
				);
				$teamMembers = new WP_Query( $args );

				if($teamMembers->found_posts > 0){

					foreach($teamMembers->posts as $item){
						$name = $item->post_title;
						$image = get_the_post_thumbnail($item->ID,'post-thumbnail');
						$description = $item->post_content;

						$position = esc_attr(get_post_meta($item->ID, 'tmcf_position', true));
					    $twitter = esc_url(esc_attr(get_post_meta($item->ID, 'tmcf_twitter', true)));
					    $facebook = esc_url(esc_attr(get_post_meta($item->ID, 'tmcf_facebook', true)));

						?>

						<li class="team_member" id="member_<?php echo $item->ID; ?>">
							<div class="image">
								<?php echo $image; ?>
							</div>
							<h3><?php echo $name; ?></h5>
							<?php echo $position ? "<h6>$position</h5>" : ''; ?>
							<div class="social">
								<?php
									echo $facebook ? "<a href='".$facebook."' class='facebook_icon' target='_blank'></a>" : '';
									echo $twitter ? "<a href='".$twitter."' class='twitter_icon' target='_blank'></a>" : '';
								?>
							</div>
							<?php echo $description ? '<div class="member_description"><p>'.$description.'</p></div><button onclick="toggleMember('.$item->ID.')">Read More</button>' : ''; ?>
							
						</li>
							
						<?php

					}
							
				}


				?>

			</ul>
		

		</main><!-- #main -->

	</section><!-- #primary -->

	

	<?php get_sidebar(); ?>

<?php get_footer(); ?>