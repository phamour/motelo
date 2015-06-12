<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MOTELO: modelling tests logger</title>

    <!-- Libraries -->
    <link rel="stylesheet" href="/css/lib/bootstrap.min.css">
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- General styles -->
    <link rel="stylesheet" href="/css/frontend.css">
</head>
<body>
    <header id="header" class="container-fluid">
        <nav class="navbar navbar-default">
            <a class="navbar-brand" href="<?= $app->urlFor('root') ?>">
                <strong>MOTELO</strong>
            </a>
            <div class="collapse navbar-collapse" id="navigation">
                <ul class="nav navbar-nav">
                    <li class="<?= preg_match('/\/model/', $app->request->getResourceUri()) ? 'active' : '' ?>">
                        <a href="<?= $app->urlFor('list', array('type' => 'model')) ?>">
                            Model
                        </a>
                    </li>
                    <li class="<?= preg_match('/\/instance/', $app->request->getResourceUri()) ? 'active' : '' ?>">
                        <a href="<?= $app->urlFor('list', array('type' => 'instance')) ?>">
                            Instance
                        </a>
                    </li>
                    <li class="<?= preg_match('/\/solution/', $app->request->getResourceUri()) ? 'active' : '' ?>">
                        <a href="<?= $app->urlFor('list', array('type' => 'solution')) ?>">
                            Solution
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            Performance<sup style="color: red;"><em>planned</em></sup>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div id="container" class="container-fluid">
