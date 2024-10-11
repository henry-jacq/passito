<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= $appTheme ?>">

<?php echo ($this->renderLayout('head', $params)); ?>

<body>

    <section class="container">
        {{contents}}
    </section>

    <?php echo ($this->renderComponent('modal', $params));
    echo ($this->renderLayout('script', $params)); ?>
    
</body>

</html>
