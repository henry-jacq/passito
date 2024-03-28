<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<?php echo ($this->renderLayout('head', $params)); ?>

<body>

    {{header}}

    <section class="container">
        {{contents}}
    </section>

    {{footer}}

    <?php echo ($this->renderComponent('scroll', $params));
    echo ($this->renderComponent('modal', $params));
    echo ($this->renderLayout('script', $params)); ?>
    
</body>

</html>
