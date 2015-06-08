<div id="flash">
    <?php if (isset($_SESSION['y.flash']['success'])): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?= $_SESSION['y.flash']['success'] ?>
            <?php unset($_SESSION['y.flash']['success']); ?>
        </div>
    <?php endif ?>
    <?php if (isset($_SESSION['y.flash']['error'])): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?= $_SESSION['y.flash']['error'] ?>
            <?php unset($_SESSION['y.flash']['error']); ?>
        </div>
    <?php endif ?>
    <?php if (isset($_SESSION['y.flash']['warning'])): ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?= $_SESSION['y.flash']['warning'] ?>
            <?php unset($_SESSION['y.flash']['warning']); ?>
        </div>
    <?php endif ?>
    <?php if (isset($_SESSION['y.flash']['info'])): ?>
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?= $_SESSION['y.flash']['info'] ?>
            <?php unset($_SESSION['y.flash']['info']); ?>
        </div>
    <?php endif ?>
</div>
