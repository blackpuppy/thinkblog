<?php
    // require_once '../vendor/autoload.php';
    require_once '../mix.php';
?>
<!DOCTYPE html>
<html ng-app="thinkblogApp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>
        <if condition="!empty($title)">
        {:"$title - "}
        </if>
        {$Think.lang.APPLICATION_NAME}
    </title>

    <link rel="stylesheet" href="<?php echo mix('css/app.css'); ?>" />
</head>
<body>
    <header>
        <ui-view name="header" />
    </header>

    <section>
        <div class="container">
            <ui-view name="content" />
        </div>
    </section>

    <footer class="footer">
        <ui-view name="footer" />
    </footer>

    <script type="text/javascript" src="<?php echo mix('js/app.js'); ?>"></script>
    <include file="Partial/url" />
</body>

</html>
