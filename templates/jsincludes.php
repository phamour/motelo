<!-- Include the core AngularJS library -->
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script>

<!-- Include the AngularJS routing library -->
<script src="https://code.angularjs.org/1.2.28/angular-route.min.js"></script>

<!-- Permanent libraries -->
<script src="/js/lib/jquery-1.11.3.min.js"></script>
<script src="/js/lib/bootstrap.min.js"></script>

<!-- Custom libraries -->
<?php if (isset($libs)): ?>
    <?php foreach ($libs as $lib_path): ?>
        <script src="<?= $lib_path ?>"></script>
    <?php endforeach ?>
<?php endif ?>

<!-- Custome scripts -->
<?php if (isset($scripts)): ?>
    <?php foreach ($scripts as $script_path): ?>
        <script src="<?= $script_path ?>"></script>
    <?php endforeach ?>
<?php endif ?>
