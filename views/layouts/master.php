<!DOCTYPE html>
<html lang="en">

<?php echo ($this->renderLayout('head', $params)); ?>

<body>

    {{contents}}

    <?php echo ($this->renderLayout('script', $params)); ?>
    
</body>

</html>
