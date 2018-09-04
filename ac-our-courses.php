<?php
/*
Plugin name: our courses
Author: Alexander Chamwada
Author URI: http://www.alexanderchamwada.com/
Version: 1.1
Description: Our Courses courses plugin
*/

add_action('init', 'presscourses');

function presscourses()
{
    register_post_type('my_courses', array(
        'labels' => array(
            'name' => __('Our Courses'),
            'singular_name' => __('Course')
        ),
        'public' => true,
        'has_archive' => true,
        
        'description' => 'Courses here',
        
        'menu_position' => 20,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            'custom-fields',
            'revisions'
        )
    ));
    create_my_tax();
}

function create_my_tax()
{
    register_taxonomy('course_category', 'my_courses', array(
        'label' => __('Course Categories'),
        'rewrite' => array(
            'slug' => 'course_category'
        ),
        'hierarchical' => true
    ));
}

add_action('wp_ajax_myCourses', 'collect_courses');
add_action('wp_ajax_nopriv_myCourses', 'collect_courses');

function collect_courses()
{
    if (isset($_POST["course"])) {
        $the_cs    = $_POST["course"];
        $the_url   = $_POST['the_url'];
        $my_cat_ID = $_POST['my_cat_ID'];
        
        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'my_courses',
            'tax_query' => array(
                array(
                    'taxonomy' => 'course_category',
                    'terms' => array(
                        $my_cat_ID
                    )
                )
            )
        ));
	    
        foreach ($myposts as $my_post) {
            $location_csf = "location";
            $location     = get_post_meta($my_post->ID, $location_csf, true);
            $featured_csf = "featured_check";
            $featured     = get_post_meta($my_post->ID, $featured_csf, true);
            $location     = get_post_meta($my_post->ID, $location_csf, true);
            $my_title     = $my_post->post_title;
            $my_ID        = $my_post->ID;
            //$my_url=$my_post->guid; get_permalink
            $my_url       = get_permalink($my_ID);
            $my_cat       = $my_post->post_title;
            $featuredimg  = get_the_post_thumbnail($my_ID);
            if ($featured == true) {
                $featured_class = "course_position_featured";
            } else {
                $featured_class = "";
            }
            
            $cont .= "<ul class=' $featured  course_listings $my_cat_ID'>
                                
                                    <li class='course_listing $featured_class'  style='visibility: visible;'>
                                    <a href='$my_url' class='the_ftd_img '>
                                    $featuredimg
                                    <div class='position'><h3>$my_title</h3><div class='company'></div></div>
                                    <div class='location'>$location</div>
                                    <ul class='meta'><li class='course-type education'> </li>
                                    <li class='date'><date> </date></li></ul></a></li></ul>";
            
        }
        
        echo "$cont";
    }
    die();
}

add_action('wp_ajax_myCourSearch', 'search_courses');
add_action('wp_ajax_nopriv_myCourSearch', 'search_courses');

function search_courses()
{
    if (isset($_POST["my_text"])) {
        $my_text  = $_POST["my_text"];
        $my_query = new WP_Query();
        
        $my_query->query(array(
            'post_type' => 'my_courses',
            'posts_per_page' => -1
        ));
        $terms = get_terms('course_category');
        $i     = 0;
        
        foreach ($terms as $term) {
            $course_cats[$i++] = $term->term_id;
        }
        
        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'my_courses',
            'tax_query' => array(
                array(
                    'taxonomy' => 'course_category',
                    'terms' => $course_cats
                )
            )
        ));
        foreach ($myposts as $my_post) {
            $location_csf = "location";
            $featured_csf = "featured_check";
            $featured     = get_post_meta($my_post->ID, $featured_csf, true);
            $location     = get_post_meta($my_post->ID, $location_csf, true);
            $my_cat       = $my_post->post_title;
            $my_title     = $my_post->post_title;
            $my_ID        = $my_post->ID;
            $my_url       = get_permalink($my_ID);
            $simvar       = similar_text($my_cat, $my_text, $percent);
            $featuredimg  = get_the_post_thumbnail($my_ID);
            if ($featured == true) {
                $featured_class = "course_position_featured";
            } else {
                $featured_class = " ";
            }
            if ($percent > 50) {
                $cont .= "<ul class=' $featured  course_listings '>
                                    
                                        <li class='course_listing $featured_class'  style='visibility: visible;'>
                                        <a href='$my_url' class='the_ftd_img'>
                                        $featuredimg
                                        <div class='position'><h3>$my_title</h3><div class='company'></div></div>
                                        <div class='location'>$location</div>
                                        <ul class='meta'><li class='course-type education'> </li>
                                        <li class='date'><date> </date></li></ul></a></li></ul>";
            }
            
            
        }
        if ($cont == "") {
            $cont = "<ul class='course_listings the_error' style='display:block; color:red;'><h1>Sorry $my_text not found </h1></ul>";
            
        }
        echo $cont;
        die();
        
    }
}

add_action('wp_ajax_myCourlocation', 'search_by_location');
add_action('wp_ajax_nopriv_myCourlocation', 'search_by_location');

function search_by_location()
{
    if (isset($_POST["my_location"])) {
        $my_text  = $_POST["my_location"];
        $my_query = new WP_Query();
        $my_query->query(array(
            'post_type' => 'my_courses',
            'posts_per_page' => -1
        ));
        $terms = get_terms('course_category');
        $i     = 0;
        
        foreach ($terms as $term) {
            $course_cats[$i++] = $term->term_id;
        }
	    
        $myposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'my_courses',
            'tax_query' => array(
                array(
                    'taxonomy' => 'course_category',
                    'terms' => $course_cats
                )
            )
        ));
	    
        foreach ($myposts as $my_post) {
            $location_csf = "location";
            $featured_csf = "featured_check";
            $location     = get_post_meta($my_post->ID, $location_csf, true);
            $featured     = get_post_meta($my_post->ID, $featured_csf, true);
            $my_cat       = $my_post->post_title;
            $my_title     = $my_post->post_title;
            $my_ID        = $my_post->ID;
            $my_url       = get_permalink($my_ID);
            $simvar       = similar_text($location, $my_text, $percent);
            $featuredimg  = get_the_post_thumbnail($my_ID);
            if ($featured == true) {
                $featured_class = "course_position_featured";
            } else {
                $featured_class = " ";
            }?>
	   <script type="text/javascript">$(".the_ftd_img img").addClass( "company_logo" );</script>
	    <?php
            if ($percent > 50) {
                $cont .= "<ul class=' $featured  course_listings '>
                                            
                                                <li class='course_listing $featured_class'  style='visibility: visible;'>
                                                <a href='$my_url' class='the_ftd_img'>
                                                $featuredimg
                                                <div class='position'><h3>$my_title</h3><div class='company'></div></div>
                                                <div class='location'>$location</div>
                                                <ul class='meta'><li class='course-type education'> </li>
                                                <li class='date'><date> </date></li></ul></a></li></ul>";
            }
            
            
        }
	    
        if ($cont == "") {
            $cont = "<ul class='course_listings the_error' style='display:block; color:red;'><h1>Sorry location $my_text not found </h1></ul>";
            
        }
        echo $cont;
        die();
        
    }
}
