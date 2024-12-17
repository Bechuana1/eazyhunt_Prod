<?php 
//require '../../config/config.php';
include '../../include/header.php';

if(empty($_SESSION['username'])){
    header('location: login.php');

}
try {
    $stmt =$connect->prepare('SELECT 
                    r.room_id AS room_id,
                    r.image_url AS image,
                    r.price AS price,
                    r.type AS room_type,
                    a.name AS apartment_name,
                    landmark,
                    a.apartment_id AS apt_id,
                    u.user_id AS Uid,
                    u.mobile_number AS phone
                FROM
                    rooms r
                    INNER JOIN apartments a ON r.apartment_id = a.apartment_id
                    INNER JOIN users u ON a.user_id = u.user_id;');

    $stmt->execute(array());
    $data1 = $stmt->fetchAll (PDO::FETCH_ASSOC);

        //print_r($data1);
    } 
    catch (PDOException $e) {
            $errMsg = $e->getMessage();
            // print $errMsg;
    }



?>
<style>
@media (max-width: 320px) {
  .row {
    flex-direction: column;
  }
}
 
@media (min-width: 321px) and (max-width: 574px) {
  .card-text {
    display: flex ;
    flex-direction: row ;
    flex-wrap: wrap ;
  }
  .card-text p {
    margin-right: 1ch;
    
  }
} 
</style>

<h1 style="margin-top: 70px;">Listed Rooms</h1>
<div class="container">
  <div class="row">
    <?php foreach ($data1 as $room) { ?>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card mb-4">
          <img src="<?php echo $room['image']; ?>" class="card-img-top" alt="Room Image">
          <div class="card-body">
            <p class="card-title"><b><?php echo $room['room_type']; ?> Room</b><p>
            <div class="card-text">
              <p>Price: <b><?php echo $room['price']; ?></b></p>
              <p>Apartment: <?php echo $room['apartment_name']; ?></p>
              <p>Location: <?php echo $room['landmark']; ?></p>
              <p>Apartment ID: <?php echo $room['apt_id']; ?></p>
              <p>User ID: <?php echo $room['Uid']; ?></p>
              <p class="text-success">Phone: <b><?php echo $room['phone']; ?></b></p> <!-- more work ion this later. hide the contact for the (show contact function) -->
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

