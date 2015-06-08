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
