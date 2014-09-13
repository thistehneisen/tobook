<ul class="pagination pagination-sm">
    <?php echo with(new \App\Core\Pagination\BootstrapPresenter($paginator))->render(); ?>
</ul>
