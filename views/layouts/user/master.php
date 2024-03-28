<!DOCTYPE html>
<?php
$theme = $params['user']['preferences']['theme'] ?? 'dark';

?>

<html lang="en" data-bs-theme="dark">

<?php echo ($this->renderLayout('head', $params)); ?>

<body>

    {{header}}

    <?php if ($params['role'] == "admin") {
        echo ('{{contents}}');
    } else {
        echo ('<section class="container">{{contents}}</section>');
    } ?>

    {{footer}}

    <?php echo ($this->renderComponent('scroll', $params)); ?>
    <?php echo ($this->renderComponent('modal', $params)); ?>
    <?php echo ($this->renderLayout('script', $params)); ?>
</body>

</html>