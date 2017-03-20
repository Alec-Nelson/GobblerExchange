<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="utf-8">
        <title>Gobbler Exchange</title>
        <script src="<?= BASE_URL ?>/public/js/jquery.min.js"></script>
        <script src="<?= BASE_URL ?>/public/js/bootstrap.min.js"></script>
        <script src="<?= BASE_URL ?>/public/js/base.js"></script>
        <link href="<?= BASE_URL ?>/public/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?= BASE_URL ?>/public/css/template.css?ver=<?php echo filemtime('<?= BASE_URL ?>/public/css/base.css');?>" type="text/css" rel="stylesheet">
        <script src="https://use.fontawesome.com/625f8d2098.js"></script>
    </head>
    <body>
      <div class="container">
        <div class="row">
        <div class="col-lg-8">
            <h1>
                <a class="title" href="<?= BASE_URL ?>" style="text-decoration:none"><span class="maroon">Gobbler</span></a>
                <a class="title" href="<?= BASE_URL ?>" style="text-decoration:none"><span class="orange">Exchange</span></a>

                <!-- <span class="orange">Exchange</span> -->
            </h1>
        </div>
        <div class="col-lg-2">
            <p id = "signedinas" class="description" style="float: right;">
            Signed in as...
            </p>
        </div>
        <!-- <div class="col-lg-2"> -->
            <button id = "signout" type="button" class="btn btn-primary" style="float: right;">
                Sign Out
            </button>
        <!-- </div> -->
      </div>


<!-- Search bar -->
<div class="container">
    <div class="row">
      <div class="col-lg-2"></div>
      <div class="col-lg-8">
        <div class="input-group">
          <div class="input-group-btn">
            <button id = "searchdropdown" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                CRN <span class="caret"><span>
            </button>
            <ul class="dropdown-menu" role = "menu">
              <li><a href="#">CRN</a></li>
              <li><a href="#">Group</a></li>
              <li><a href="#">Username</a></li>
              <li><a href="#">Email</a></li>
            </ul>
          </div><!-- /btn-group -->
          <input type="hidden" name="search_param" value="all" id="search_param">
          <input type="text" class="form-control" name="x" placeholder="Search term...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button">
              <i class="fa fa-search" aria-hidden="true"></i>
            </button>
          </span>
        </div><!-- /input-group -->
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->
</div>

<br>

    <!-- Class navigation (forum, calendar, notes, whiteboard) -->
    <div class="container">
      <div class="row">
        <div class="col-lg-2" style="text-align: center;">
          <form method="POST" action="<?= BASE_URL ?>/jsontable">
            <button id = "button" type="submit" class="btn btn-primary">
                New Class
            </button>
          </form>
        </div>
        <div class="col-lg-8">
            <ul class="nav nav-tabs">
              <li id = "tab" role="presentation"><a href="<?= BASE_URL ?>/forum">Forum</a></li>
              <li id = "tab" role="presentation"><a href="<?= BASE_URL ?>/calendar">Calendar</a></li>
              <li id = "tab" role="presentation"><a href="#">Notes</a></li>
              <li id = "tab" role="presentation"><a href="#">Whiteboard</a></li>
            </ul>
        </div>
        <div class="col-lg-2"></div>
      </div>
    </div>

<br>
<div class = "container" id="classTabs">
        <div class="row">
            <div class="col-lg-2">
                <a href="#" class="list-group-item">CS 3114</a>
                <a href="#" class="list-group-item">STAT 4705</a>
                <a href="#" class="list-group-item">ENGL 1704</a>
            </div>
            <div class="col-lg-7">

                <!-- Main space -->
                <div id="module">
