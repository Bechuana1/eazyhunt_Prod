<?php
    require '../../config/config.php';

?>

<div class="row">
    <?php foreach ($total_apartments as $apartment) { ?>
        <div class="col-md-4">
        <a href="#">  <!-- create a hyperlink <a href="../app/list.php?apartment_id=<?php //echo $apartment['id']; ?>"> -->
                <div class="card-counter danger">
                    <i class="fa fa-home"></i>
                    <span class="count-numbers"><?php //echo $apartment['total_rooms']; ?></span>
                    <span class="count-name"><?php echo $apartment['name']; ?></span>
                </div>
            </a>
        </div>
    <?php } ?>
</div>
