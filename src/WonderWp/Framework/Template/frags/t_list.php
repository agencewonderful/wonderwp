<?php if (!empty($notifications)) {
    echo implode("\n", $notifications);
} ?>
<?php
$screen = get_current_screen();
?>
<div class="list-table-wrap" data-screen="<?php echo $screen->id; ?>">
    <?php
    /** @var \WonderWp\Framework\AbstractPlugin\AbstractListTable $listTableInstance */
    $wp_list_table = $listTableInstance;
    $pagenum       = $wp_list_table->get_pagenum();
    $wp_list_table->prepare_items();

    //pagination arguments
    $total_pages = $wp_list_table->get_pagination_arg("total_pages");
    if ($pagenum > $total_pages && $total_pages > 0) {
        wp_redirect(add_query_arg("paged", $total_pages));
    }

    //Table of elements
    $wp_list_table->display();
    ?>
</div>
