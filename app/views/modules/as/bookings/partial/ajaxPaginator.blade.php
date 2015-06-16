<?php
    $presenter = new App\Appointment\Controllers\Ajax\AjaxPresenter($paginator);
?>
<?php if ($paginator->getLastPage() > 1): ?>
    <ul class="pagination">
            <?php echo $presenter->render(); ?>
    </ul>
<?php endif; ?>
