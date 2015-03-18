<!DOCTYPE html>
<html lang="<?= $app['locale'] ?>">
<head>
    <meta charset="<?= $app['charset'] ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="author" content="<?= $app['authors'] ?>" />
    <meta name="description" content="<?= isset($page['keywords']) ? $page['keywords'] : $app['description'] ?>" />
    <meta name="keywords" content="<?= isset($page['keywords']) ? $page['keywords'] : $app['keywords'] ?>" />
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
    <?= $this->css('css/app.css') ?>
    <!--[if lte IE 9]> 
        <script text="text/javascript" src="js/html5.min.js"></script>
        <script text="text/javascript" src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="apple-touch-icon" href="img/touch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/touch-icon-ipad.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/touch-icon-iphone-retina.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/touch-icon-ipad-retina.png">
    <link rel="start" href="{{ app.url }}" title="Accueil" />
    <link rel="help" href="politique-accessibilite.html" title="Accessibilité" />
    <link rel="accesskeys" href="politique-accessibilite.html#accesskeys" title="Raccourcis et Accesskeys" />
    <link rel="shortcut icon" type="image/gif" href="{{IMG_PATH}}/favicon.gif" />
    <title><?= isset($page['title']) ? $page['title'] : $app['name'].' - '.$app['description'] ?></title>
</head>
<body>

<header class="container" id="header" role="header">
    <h1><a href="<?= $app['url'] ?>"><?= $app['name'] ?></a></h1>
    <p><?= $app['description'] ?></p>
    
    <nav class="nav-bar" id="menu">
        <?php if ($this->isLogged()): ?>
        <ul>
           <li>
               <?= $this->a(t('Logout'), 'user', 'logout', array(), true) ?>
               <span class="username hide-tablet">(<?= $this->a($this->e($this->getFullname()), 'user', 'show', array('user_id' => $this->userSession->getId())) ?>)</span>
           </li>
        </ul>
        <?php endif; ?>
    </nav>
</header>

<section class="container" id="content">
<div class="row">
    <aside class="col-md-2" id="bar">
    Great menu!
    </aside>

    <article class="col-md-10" id="mainContent" role="main-content">
        <?= $this->flash('<div class="alert alert-success alert-fade-out">%s</div>') ?>
        <?= $this->flashError('<div class="alert alert-error">%s</div>') ?>
        <?= $content_for_layout ?>
    </article>
</div>
</section>

<footer class="container" id="footer" role="footer">
<div class="class="col-md-10 col-md-offset-2">
    <p>
        <?= $app['name'].' - '.$app['description'] ?>
    </p>
</div>
</footer>

<script text="text/javascript" src="js/jquery.min.js"></script>
<script text="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>
