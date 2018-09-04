<?php
/**
 * The template used for displaying page content
 *
 *  Template Name: Course Listing
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
get_header();
?>

<header class="entry-header-course" >
    <div class="row">
        <div class="large-12 columns">
            <a class="course_dir_title" href="/find-a-journalism-course"><?php
the_title('<h1 class="entry-title"><b>', '</b></h1>');
?></a>
            
            <?php //the_excerpt(); 
?>
       
        </div>
    </div>
    
</header>

    <div class="row">
    <div class="large-12 columns">
        <main id="main" class="site-main" role="main">
            <div class="primary-content">
                <article id="post-<?php
the_ID();
?>" <?php
post_class();
?>>
                    <?php
twentysixteen_post_thumbnail();
?>
                   <div class="row">
                        <div class="large-12 columns entry-content-about">
                            <form class="course_filters" method="Post">
                            <div class="search_course">
        
        <div class="search_keywords">
            <label for="search_keywords">Keywords</label>
            <input type="text" name="search_keywords" id="search_keywords" placeholder="Keywords" value="">
        </div>

        <div class="search_location">
            <label for="search_location">Location</label>
            <input type="text" name="search_location" id="search_location" placeholder="Location" value="">
        </div>

        
            </div>
            <ul class="course_types">
                                    <?php

$my_query = new WP_Query();
$my_query->query(array(
    'post_type' => 'my_courses',
    'posts_per_page' => -1
));

$terms = get_terms('course_category');


foreach ($terms as $term) { ?>
    <li><label><input type="checkbox" name="course_name" value="<?php echo $term->name;?>" class="clicked_course"  id="<?php echo $term->term_id;?>" data-mycatid="<?php echo $term->term_id; ?>" data-url="<?php the_permalink(); ?>" data-url="<?php the_permalink(); ?>"> <?php echo $term->name; ?></label></li>
    <?php } ?>

                                    
	    </ul>
	    <input type="hidden" name="filter_course_type[]" value="">
	    <div class="showing_course" style="display: none;"></div></form>

	<div  class="the_result" style="display: none">    
                                
            
                
                </div>

                        </div>
                        <!-- .entry-content -->
                        
                    </div>
                    <?php
edit_post_link(sprintf( /* translators: %s: Name of current post */ __('Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen'), get_the_title()), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->');
?>

                </article>

            </div>
            <!-- #post-## -->


        </main>
        <!-- .site-main -->
    </div>

<?php
get_footer();
?>



<script type="text/javascript">

$('.clicked_course').attr('checked', true);
$('.clicked_course').addClass( "clicked" );
$(".the_ftd_img img").addClass( "company_logo" );

$("input[type='checkbox']").addClass( "clicked" );

var ajaxurl = '<?php
echo admin_url("admin-ajax.php");
?>';
    initial_loading();


function initial_loading() {

             var my_cat_ID=" ";
              $('.clicked_course').each(function(){
                  my_cat_ID =  $(this).data('mycatid');//+"$@£";
                  //alert(my_cat_ID);

                  if ($(this).is(':checked')) {

                    if($(this).hasClass("clicked")){
                        
                        $(this).addClass("clicked");
                            
                            var course = $(this).val();
                            //var my_cat_ID =  $('.clicked_course').data('mycatid');
                            
                          
                            var the_url =  $('.clicked_course').data('url');
                            
                           
                                if(course != '') {
                                  
                                    var data = {
                                        action: 'myCourses',
                                        course: course,
                                        my_cat_ID:my_cat_ID,
                                        the_url:the_url
                                    }
                     
                                    $.post(ajaxurl, data, function(response) {
                                        $('.the_error').css("display","none");
                                        $('.the_result').css("display","block");
                                        //$('.the_result').append(response);
                                        var responseString = response;
                                        var responseArray = responseString.split('</ul>');
                                        var search_class = "course_position_featured";
                                        var result_collection = "";
                                        $.each(responseArray, function(index, value) { 
                                        if(value.indexOf(search_class, 1) !==-1 ){
                                            $('.the_result').prepend(value);
                                          
                                        }else{
                                             res = result_collection.concat(value);
                                        } 
                                        $('.the_result').append(res);
                                         $(".the_ftd_img img").addClass( "company_logo" );
                                         
                                        }
                                        

                                        );

                                    });

                                }
                        

                    }

                    
                }
                    });



              /*$my_arr= my_cat_ID.split();
              alert(my_cat_ID);*/

             // my_cat_ID =  $('.clicked_course').data('mycatid');
             

                /*if ($('.clicked_course').is(':checked')) {

                    if($('.clicked_course').hasClass("clicked")){
                        
                        $('.clicked_course').addClass("clicked");
                            
                            var course = $('.clicked_course').val();
                            var my_cat_ID =  $('.clicked_course').data('mycatid');
                            
                          
                            var the_url =  $('.clicked_course').data('url');
                            
                           
                                if(course != '') {
                                  
                                    var data = {
                                        action: 'myCourses',
                                        course: course,
                                        my_cat_ID:my_cat_ID,
                                        the_url:the_url
                                    }
                     
                                    $.post(ajaxurl, data, function(response) {
                                        $('.the_error').css("display","none");
                                        $('.the_result').css("display","block");
                                        $('.the_result').append(response);
                                    });
                                }
                        

                    }

                    
                }*/
            
           
        }


var ajaxurl = '<?php
echo admin_url("admin-ajax.php");
?>';
    jQuery(function($) {
        $('body').on('click', '.clicked_course', function() {
             var my_cat_ID =  $(this).data('mycatid');
if ($(this).is(':checked')) {

    if($(this).hasClass("clicked")){

    }else{
            $(this).addClass("clicked");
            var course = $(this).val();
            var my_cat_ID =  $(this).data('mycatid');
            
          
            var the_url =  $(this).data('url');
            
           
                if(course != '') {
                  
                    var data = {
                        action: 'myCourses',
                        course: course,
                        my_cat_ID:my_cat_ID,
                        the_url:the_url
                    }
     
                    $.post(ajaxurl, data, function(response) {
                        $('.the_error').css("display","none");
                        $('.the_result').css("display","block");
                        $('.the_result').append(response);
                    });
                }
            }

    
}else{
    $(this).removeClass( "clicked" );
    jQuery('.'+my_cat_ID).css("display","none")
    
                
            }
            
           
        });
    });
</script>
<!--#search_keywords-->
<script type="text/javascript">

$.fn.enterKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                fnc.call(this, ev);
            }
        })
    })
}

$("#search_keywords").enterKey(function () {
    
            var my_text = $(this).val();
            
            
           
                if(my_text != '') {
                  
                    var data = {
                        action: 'myCourSearch',
                        my_text: my_text
                    }
     
                   $.post(ajaxurl, data, function(response) {
                                        $('.the_error').css("display","none");
                                        $('.the_result').css("display","block");
                                        //$('.the_result').append(response);
                                        var responseString = response;
                                        var responseArray = responseString.split('</ul>');
                                        var search_class = "course_position_featured";
                                        var result_collection = "";
                                        $.each(responseArray, function(index, value) { 
                                        if(value.indexOf(search_class, 1) !==-1 ){
                                            $('.the_result').prepend(value);
                                          
                                        }else{
                                             res = result_collection.concat(value);
                                        } 
                                        $('.the_result').append(res);
                                        $(".the_ftd_img img").addClass( "company_logo" );
                                         
                                        }
                                        

                                        );

                                    });
                }


                 var my_cat_ID =  $(this).data('mycatid');
                
})

    var ajaxurl = '<?php
echo admin_url("admin-ajax.php");
?>';
    jQuery(function($) {
        $('body').on('keyup', '#search_keywords', function() {
             jQuery('.course_listing').css("display","none"); 

             $('.the_error').css("display","none");
        });
    });
</script>


<!--#search_location-->
<script type="text/javascript">

$.fn.enterKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                fnc.call(this, ev);
            }
        })
    })
}

$("#search_location").enterKey(function () {
    
            var my_location = $(this).val();
           
                if(my_location != '') {
                  
                    var data = {
                        action: 'myCourlocation',
                        my_location: my_location
                    }
     
                    $.post(
			   ajaxurl, 
			   data, 
			   function(response) {
                                        $('.the_error').css("display","none");
                                        $('.the_result').css("display","block");
                                        //$('.the_result').append(response);
                                        var responseString = response;
                                        var responseArray = responseString.split('</ul>');
                                        var search_class = "course_position_featured";
                                        var result_collection = "";
                                        $.each(responseArray, function(index, value) { 
                                        if(value.indexOf(search_class, 1) !==-1 ){
                                            $('.the_result').prepend(value);
                                          
                                        }else{
                                             res = result_collection.concat(value);
                                        } 
                                        $('.the_result').append(res);
                                        $(".the_ftd_img img").addClass( "company_logo" );
                                         
                                        } );

                                    });
                }


                 var my_cat_ID =  $(this).data('mycatid');
                
})

    var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
    jQuery(function($) {
        $('body').on('keyup', '#search_location', function() {
             jQuery('.course_listing').css("display","none"); 

             $('.the_error').css("display","none");
        });
    });
</script>
