<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<?php echo ($this->renderLayout('head', $params)); ?>

<body>

    {{header}}

    <?php echo ($this->renderLayout('navbar', $params)); ?>

    <div class="d-flex">

        <?php echo ($this->renderLayout('sidebar', $params)); ?>

        <div class="content-container">
            <div class="content">
                {{contents}}
            </div>
        </div>

    </div>

    {{footer}}

    <?php echo ($this->renderComponent('scroll', $params));
    echo ($this->renderComponent('modal', $params));
    echo ($this->renderLayout('script', $params)); ?>

</body>

</html>