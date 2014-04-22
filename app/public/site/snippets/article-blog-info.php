<?php 

$tags = explode(',', $page->tags()->value );

?>

publié il y a <?php echo setDateFr( $page->date(), true ); ?>
<?php 
foreach($tags as $tag){ 
	?>
	<a class="superLink" href="<?php echo $site->url(); ?>/tagged?<?php echo trim( $tag ); ?>"><?php echo trim( $tag ); ?></a>
<?php 
} 
?>