
<?php
error_reporting(0);
$buttonId = $_POST['buttonId'];
$connection = mysqli_connect('localhost', 'username', 'password', 'database');

$previousButtonId = $buttonId - 1;
$query = "SELECT click_count FROM deposite WHERE id = $previousButtonId";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);
$previousCount = $row['click_count'];

$query = "INSERT INTO buttons (id, click_count) VALUES ($buttonId, 1)
          ON DUPLICATE KEY UPDATE click_count = click_count + 1;
          UPDATE buttons SET click_count = click_count + $previousCount WHERE id = $previousButtonId";
mysqli_multi_query($connection, $query);


mysqli_close($connection);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Button Demo</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>
  <h1>Buttons</h1>

  <?php
  
  $connection = mysqli_connect('localhost', 'username', 'password', 'database');
  $query = "SELECT * FROM deposite ORDER BY id ASC";
  $result = mysqli_query($connection, $query);

  while ($row = mysqli_fetch_assoc($result)) {
    $buttonId = $row['id'];
    $clickCount = $row['click_count'];
    $disabled = ($clickCount > 0) ? 'disabled' : ''; 

    echo '<button class="increment-button" data-button-id="'.$buttonId.'" '.$disabled.'>Button '.$buttonId.'</button>';
    echo '<span>Click Count: '.$clickCount.'</span>';
    echo '<br>';
  }


  mysqli_close($connection);
  ?>

  <script>

    function handleClick(event) {
      var button = $(this);
      var buttonId = button.data('button-id');

      button.prop('disabled', true);

      // Send an AJAX request to update the button data
      $.ajax({
        url: 'update_button.php',
        type: 'POST',
        data: { buttonId: buttonId },
        success: function() {
          console.log('Button click updated successfully!');
        },
        error: function() {
          console.error('Failed to update button click!');
        }
      });
    }

    $('.increment-button').click(handleClick);
  </script>
</body>
</html>
