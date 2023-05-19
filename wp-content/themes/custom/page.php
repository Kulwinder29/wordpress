<?php 
get_header();
the_post();
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a class="text-dark" href="<?php echo site_url(); ?>">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
  </ol>


  <div class="container">
<div class="d-flex justify-content-center" style="">
<?php 
  the_post_thumbnail(array(500,500));
  ?>

</div>
  
    <p><?php the_content(); ?> </p>
  </div>
</nav>

<?php
get_footer();
?>