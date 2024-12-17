<?php

$selectedPlan = null;
$planDetails = array(
  "Starter" => array(
    "amount" => 150,
    "duration" => "3 Days",
    "features" => array(
      "Basic Access",
      "Limited Searches",
      "Standard Support"
    )
  ),
  "Pro" => array(
    "amount" => 250,
    "duration" => "Weekly",
    "features" => array(
      "Full Premium Access",
      "Unlimited Searches",
      "Crazy Discounts",
      "3 Days Duration",
      "Complimentary Support"
    )
  )
);

// Check if data-plan is set or plan_name is submitted in the form
if (isset($_GET['data-plan'])) {
  $selectedPlan = $_GET['data-plan'];
} elseif (isset($_POST['plan_name'])) {
  $selectedPlan = $_POST['plan_name'];
}

// Validate selected plan
if (!isset($planDetails[$selectedPlan])) {
  // Handle invalid plan (e.g., redirect to error page)
  header('Location: error.php');
  exit;
}

$planInfo = $planDetails[$selectedPlan];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.3/components/pricings/pricing-1/assets/css/pricing-1.css">
</head>
<body>
    <div class="container">
      <?php if ($selectedPlan): ?>
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="card border-0 border-bottom border-success shadow-lg pt-md-4 pb-md-4 bsb-pricing-popular">
                <div class="card-body p-4 p-xxl-5">
                  <h2 class="h4 mb-2"><?php echo $selectedPlan; ?></h2>
                  <h4 class="display-3 fw-bold text-success mb-0"><?php echo $planInfo['amount']; ?> Ksh</h4>
                  <p class="text-secondary mb-4"><?php echo $planInfo['duration']; ?></p>
                  <ul class="list-group list-group-flush mb-4">
                    <?php foreach ($planInfo['features'] as $feature): ?>
                      <li class="list-group-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                        </svg>
                        <span><?php echo $feature; ?></span>
                      </li>
                    <?php endforeach; ?>
                  </ul> 
                <form action="./mpesa/stk_push.php" method="post">
                  <div class="form-group mt-4">
                    <label for="phone_number">Phone Number:</label>
                    <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="Phone Number (e.g., 254722000000)" required>
                    <div class="invalid-feedback">Please enter a valid phone number.</div>
                  </div>

                  
                    <input type="hidden" name="plan_name" value="<?php echo $selectedPlan; ?>">
                    <input type="hidden" name="amount" value="<?php echo $planInfo['amount']; ?>"> 
                    <button type="submit" class="btn bsb-btn-xl btn-success rounded-pill w-100 mt-4">Pay Now <img src="./assets/images/mpesa.svg" alt="" style="height: 32px;"></button> 

                </form>
                </div>
              </div>
            </div>
          </div>


      <?php else: ?>
        <p>Error: No plan selected.</p>
      <?php endif; ?>

    </div>
  </body>
  </html>

          
