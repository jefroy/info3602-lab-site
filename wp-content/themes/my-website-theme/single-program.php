<?php // exercise 1 lecture 3
//the_ID();
get_header();
while(have_posts()){ // iterate through all posts
    the_post(); // curr post
    // jump out of php into html
    pageBanner(array());
    ?>
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program') ?>">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    Back to Programs
                </a>
            </p>
        </div>
        <div class="generic-content">
            <? the_content(); ?>
        </div>
        <?php

        $relatedProfs = new WP_Query(array(
	        'posts_per_page' => -1,
	        'post_type'=> 'professor',
	        'orderby' => 'title',
	        'order' => 'ASC',
	        'meta_query' => array(
		        array(
			        'key' => 'related_programs',
			        'compare' => 'LIKE',
			        'value' => '"' . get_the_ID() . '"' // ensure we compare strings not ints
		        )
            )
        ));
//        echo 'ran prof query';
        if($relatedProfs->have_posts()){
//            echo "found related profs";
            ?>
            <hr class="section-break">
            <h2 class="headline headline--medium"> <?php echo get_the_title(); ?> Professors </h2>
            <ul class="professor-cards">
            <?php
            while($relatedProfs->have_posts()){
                $relatedProfs->the_post();
                ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="">
                        <span class="professor-card__name">
                            <?php
                            the_title();
//                            echo ' ; pageid = '; the_ID();
                            ?>
                        </span>
                    </a>
                </li>
                <?php
            }
            ?>
            </ul>
            <?php
	        wp_reset_postdata();
        }

        $homepageEvents = new WP_Query(array(
	        'posts_per_page' => -1,
	        'post_type'=> 'event',
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => date('Ymd'),
                    'type' => 'numeric'
                ),
                array(
	                'key' => 'related_programs',
	                'compare' => 'LIKE',
	                'value' => '"' . get_the_ID() . '"', // ensure we compare strings not ints
                )
            ),

        ));

        if($homepageEvents->have_posts()){
	        ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">
                Upcoming <?php echo get_the_title(); ?> Events
            </h2>
            <?php
            while($homepageEvents -> have_posts()){
		        $homepageEvents->the_post();
		        ?>
                <div class="event-summary">
                    <a class="event-summary__date t-center" href="#">
				        <?php
				        $eventDate = new DateTime(get_field('event_date'));
				        ?>
                        <span class="event-summary__month"><?php echo $eventDate->format('M') ?></span>
                        <span class="event-summary__day"><?php echo $eventDate->format('d') ?></span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a href="#"><?php the_title(); ?></a></h5>
                        <p><?php echo wp_trim_words(get_the_content(), 20); ?><a href="<?php the_permalink(); ?>" class="nu gray"><br>Learn more</a></p>
                    </div>
                </div>
		        <?php
	        }// end while
            wp_reset_postdata();
        }
        ?>
        <hr class="section-break">
        <a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--large btn--blue">Check out our Events!</a>
    </div>
    <?php
} // end while
get_footer();
?>