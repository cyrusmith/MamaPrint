<div class="container">
    <?php foreach ($orders as $order): ?>
    <?php echo $order->id; ?>
    <?php endforeach; ?>
</div>

<?php echo $orders->links(); ?>