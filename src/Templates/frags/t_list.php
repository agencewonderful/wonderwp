<?php if(!empty($notifications)){ echo implode("\n",$notifications); } ?>

<div class="list-table-wrap">
<?php
/** @var \WonderWp\APlugin\ListTable $listTableInstance */
$wp_list_table = $listTableInstance;
$pagenum = $wp_list_table->get_pagenum();
$wp_list_table->prepare_items();

//pagination arguments
$total_pages = $wp_list_table->get_pagination_arg( "total_pages" );
if ( $pagenum > $total_pages && $total_pages > 0 ) {
    wp_redirect(add_query_arg("paged", $total_pages));
}

//Table of elements
$bulk_actions = $wp_list_table->get_bulk_actions();
if(!empty($bulk_actions)){ echo'<form id="list_class_bulk_actions" method="post">'; }
$wp_list_table->display();
if(!empty($bulk_actions)){ echo'</form>'; }
?>
</div>
