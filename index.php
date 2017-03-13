<?php
/*
Plugin Name: WP Courses
Plugin URI: http://www.progressivemediagroup.com/
Description: Add courses
Version: 1.0.0
Author: Alex
*/

add_action('wp_ajax_myCourses' , 'collect_courses');
add_action('wp_ajax_nopriv_myCourses' , 'collect_courses');

function collect_courses(){
	if(isset($_POST["course"])){
			$the_cs=$_POST["course"];
			$the_url=$_POST['the_url']; 
			$my_cat_ID=$_POST['my_cat_ID'];

			 
			$myposts = get_posts(array(
			    'showposts' => -1,
			    'post_type' => 'my_courses',
			    'tax_query' => array(
			           array(
			                'taxonomy' => 'course_category',
			                'terms' => array( $my_cat_ID  )
			            )
			            ))
			);



			foreach ($myposts as $my_post) {
						$location_csf="location"; 
						$location = get_post_meta($my_post->ID, $location_csf, true);
						$featured_csf="featured_check"; 
						$featured = get_post_meta($my_post->ID, $featured_csf, true);
						$location = get_post_meta($my_post->ID, $location_csf, true);
					    $my_title=$my_post->post_title;
					    $my_ID=$my_post->ID;
					    //$my_url=$my_post->guid; get_permalink
					    $my_url=get_permalink($my_ID);
					    $my_cat=$my_post->post_title;
					    $featuredimg = get_the_post_thumbnail( $my_ID);
					    if($featured==true){
					    	$featured_class = "course_position_featured";

					    }else{
					    	$featured_class = "";
					    }
					    
						$cont .= "<ul class=' $featured  job_listings $my_cat_ID'>
								
									<li class='job_listing $featured_class'  style='visibility: visible;'>
								    <a href='$my_url' class='the_ftd_img '>
									$featuredimg
									<div class='position'><h3>$my_title</h3><div class='company'></div></div>
									<div class='location'>$location</div>
									<ul class='meta'><li class='job-type education'> </li>
									<li class='date'><date> </date></li></ul></a></li></ul>";
				
			          }
			
				echo "$cont";


			}


			die();



}


add_action('wp_ajax_myCourSearch' , 'search_courses');
add_action('wp_ajax_nopriv_myCourSearch' , 'search_courses');

function search_courses(){
					if(isset($_POST["my_text"])){
					$my_text=$_POST["my_text"];
					$my_query = new WP_Query();
					
					$my_query->query(
						array('post_type' => 'my_courses',
										    'posts_per_page' => -1
										    ));

					$terms = get_terms( 'course_category' );
					$i=0;
					
					foreach ( $terms as $term ) {

						$course_cats [$i++]=  $term->term_id;

											 }
											
											$myposts = get_posts(array('showposts' => -1,
												'post_type' => 'my_courses',
												'tax_query' => array(array('taxonomy' => 'course_category',
													'terms' => $course_cats 
													            )
													            ))
													);


						foreach ($myposts as $my_post) {
							$location_csf="location"; 
							$featured_csf="featured_check"; 
							$featured = get_post_meta($my_post->ID, $featured_csf, true);
							$location = get_post_meta($my_post->ID, $location_csf, true);
							$my_cat=$my_post->post_title;
							$my_title=$my_post->post_title;
						    $my_ID=$my_post->ID;
						    $my_url=get_permalink($my_ID);
						    $simvar=similar_text($my_cat,$my_text, $percent);
						    $featuredimg = get_the_post_thumbnail( $my_ID);

						    if($featured==true){
						    	$featured_class = "course_position_featured";

						    }else{
						    	$featured_class = " ";
						    }

						    if($percent>50){
						    	$cont .= "<ul class=' $featured  job_listings '>
									
										<li class='job_listing $featured_class'  style='visibility: visible;'>
									    <a href='$my_url' class='the_ftd_img'>
										$featuredimg
										<div class='position'><h3>$my_title</h3><div class='company'></div></div>
										<div class='location'>$location</div>
										<ul class='meta'><li class='job-type education'> </li>
										<li class='date'><date> </date></li></ul></a></li></ul>";
						    }
							
						    	

						    }

						    if($cont==""){
						    	$cont = "<ul class='job_listings the_error' style='display:block; color:red;'><h1>Sorry $my_text not found </h1></ul>";
						    	

						    }

						    echo $cont;
						    die();

						    

						}


}



add_action('wp_ajax_myCourlocation' , 'search_by_location');
add_action('wp_ajax_nopriv_myCourlocation' , 'search_by_location');

function search_by_location(){
				if(isset($_POST["my_location"])){
								$my_text=$_POST["my_location"];
								$my_query = new WP_Query();
								$my_query->query(array('post_type' => 'my_courses',
														'posts_per_page' => -1
																		    ));
								$terms = get_terms( 'course_category' );
								$i=0;
								

								foreach ( $terms as $term ) {

									$course_cats [$i++]=  $term->term_id;

									}


									$myposts = get_posts(array(
								    'showposts' => -1,
								    'post_type' => 'my_courses',
								    'tax_query' => array(array(
								                'taxonomy' => 'course_category',
								                'terms' => $course_cats 
								            )
								            ))
								);


								foreach ($myposts as $my_post) {

									$location_csf="location"; 
									$featured_csf="featured_check"; 
									$location = get_post_meta($my_post->ID, $location_csf, true);
									$featured = get_post_meta($my_post->ID, $featured_csf, true);
									$my_cat=$my_post->post_title;
									$my_title=$my_post->post_title;
								    $my_ID=$my_post->ID;
								    $my_url=get_permalink($my_ID);
								    $simvar=similar_text($location, $my_text, $percent);
								    $featuredimg = get_the_post_thumbnail( $my_ID);

								    if($featured==true){
								    	$featured_class = "course_position_featured";

								    }else{
								    	$featured_class = " ";
								    }

											?>
											<script type="text/javascript">
											$(".the_ftd_img img").addClass( "company_logo" );
											</script>
											<?php

								    if($percent>50){
								    	$cont .= "<ul class=' $featured  job_listings '>
											
												<li class='job_listing $featured_class'  style='visibility: visible;'>
											    <a href='$my_url' class='the_ftd_img'>
												$featuredimg
												<div class='position'><h3>$my_title</h3><div class='company'></div></div>
												<div class='location'>$location</div>
												<ul class='meta'><li class='job-type education'> </li>
												<li class='date'><date> </date></li></ul></a></li></ul>";
								    }
									
								    	

								    }

								    if($cont==""){
								    	$cont = "<ul class='job_listings the_error' style='display:block; color:red;'><h1>Sorry location $my_text not found </h1></ul>";
								    	

								    }

								    echo $cont;
								    die();

				    

}
}





?>