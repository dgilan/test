<?php
/**
 * Main layout
 *
 * @author Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

$activeIfRoute = function ($item) use (&$route) {
    return $route['_name'] === $item?'class="active"':'';
}
?>
<!DOCTYPE html>
<html lang="en-us">

<head>
    <title>Mikhail's Test CMS for DataArt</title>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
    <link href="/css/bootstrap-theme.min.css" type="text/css" rel="stylesheet"/>
    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">

    <link href="/css/theme.css" rel="stylesheet">

</head>
<body role="document">

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://www.dataart.ua/"><img src="/images/dataart.png" alt="DataArt Test"></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li <?php echo $activeIfRoute('home') ?>><a href="/">Home</a></li>
                <?php if ($user) { ?>
                <li <?php echo $activeIfRoute('add_post') ?>><a href="/posts/add">Add Post</a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (is_null($user)) { ?>
                    <li <?php echo $activeIfRoute('signin') ?>><a href="/signin">Sign in</a></li>
                    <li <?php echo $activeIfRoute('login') ?>><a href="/login">Login</a></li>
                <?php } else { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-user"></i>
                            <?php echo 'Hello, '.$user->name ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php echo $activeIfRoute('profile') ?>><a href="/profile">Profile</a></li>
                            <li class="divider"></li>
                            <li><a href="/logout">Logout</a></li>
                        </ul>
                    </li>

                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<div class="container theme-showcase" role="main">
    <div class="row">
        <?php if ($message = \Kernel\Security\Token::get('flush')) { ?>
            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <?php echo $content; ?>
    </div>
</div>

<script type="application/javascript" src="/js/jquery.min.js"></script>
<script type="application/javascript" src="/js/bootstrap.min.js"></script>
<script type="application/javascript" src="/js/jquery.hotkeys.js"></script>
<script type="application/javascript" src="/js/bootstrap-wysiwyg.js"></script>
<script type="application/javascript">
    $(document).ready(function () {
        $('#editor').wysiwyg();
        $('#post-form').submit(function (e) {
            $('#post-content').val($('#editor').html());
        })
    });
</script>

</body>
</html>