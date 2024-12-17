<?php
function countRooms($connect)
{
    if (!empty($_SESSION['id'])) {
        $stmt1 = $connect->prepare('SELECT apartments.apartment_id AS apt_id, apartments.name, COUNT(rooms.room_id) AS room_count
            FROM apartments
            LEFT JOIN rooms ON apartments.apartment_id = rooms.apartment_id
            WHERE apartments.user_id = :user_id
            GROUP BY apartments.apartment_id
            ORDER BY apartments.apartment_id DESC');
        $stmt1->execute(
            array(
                ':user_id' => $_SESSION['id']
            )
        );
        $apartments = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        return $apartments;
    }
}

$apartments = countRooms($connect);
?>
<style>
    @media (min-width: 375px) and (max-width: 575.98px) {
        .my-class {
            display: flex !important;
        }

        .col-sm-6 {
            width: 50% !important;
        }

        .card-counter {
            padding: 0 !important;

        }

        .card-counter .count-numbers {
            right: 0;

            font-size: 20px !important;
            /* adjust to your preferred size */
        }

        .card-counter i {
            font-size: 3em !important;
        }

        .card-counter .count-name {
            font-size: 14px !important;
            /* adjust to your preferred size */
        }
    }
</style>

<div class="row">
    <?php foreach ($apartments as $apt) {
        $room_count = $apt['room_count'];
        $apt_name = $apt['name'];
        $apartment_id = $apt['apt_id'];
        $user_id = $_SESSION['id'];
        //$url = "apartment.php?id=" . $apt['apartment_id'];


        //$url = "../auth/dashboard.php?apartment_id={$apartment_id}&user_id={$user_id}&name={$apt_name}";
        $url = "../auth/dashboard.php?apartment_id={$apartment_id}&user_id={$user_id}&name={$apt_name}#room";
    ?>

        <div class=" col-sm-6 col-md-4 m-0 p-0">
            <a href="<?php echo $url; ?>">
                <div class="card-counter success">
                    <i class="fa fa-home"></i>
                    <span class="count-name text-white f-3"><?php echo $apt_name; ?></span>
                    <span class="count-numbers text-warning"><?php echo "rooms " . $room_count; ?></span>

                </div>
            </a>
        </div>


    <?php }
    ?>