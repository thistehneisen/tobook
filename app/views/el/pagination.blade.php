<ul class="pagination">
    <?php echo with(new \App\Core\Pagination\BootstrapPresenter($paginator))->render(); ?>
</ul>
