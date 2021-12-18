<?php
require_once "Mail.php";
// display_time_remaining:
// Helper function to help figure out what time to display
function display_time_remaining($interval) {

    if ($interval->days == 0 && $interval->h == 0) {
      // Less than one hour remaining: print mins + seconds:
      $time_remaining = $interval->format('%im %Ss');
    }
    else if ($interval->days == 0) {
      // Less than one day remaining: print hrs + mins:
      $time_remaining = $interval->format('%hh %im');
    }
    else {
      // At least one day remaining: print days + hrs:
      $time_remaining = $interval->format('%ad %hh');
    }

  return $time_remaining;

}

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time, $image)
{
  // Truncate long descriptions
  if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  }
  else {
    $desc_shortened = $desc;
  }
  
  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }
  
  // Calculate time to auction end
  $now = new DateTime();
  if ($now > $end_time) {
    $time_remaining = 'This auction has ended';
  }
  else {
    // Get interval:
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = display_time_remaining($time_to_end) . ' remaining';
  }
  
  // Print HTML
  echo('
  <li class="list-group-item d-flex justify-content-between">
  <div style="width:100px;height:100px;white-space:nowrap;text-align:center">
  <span style="display: inline-block;height: 100%;vertical-align: middle"></span>
  <img class="cropped" alt="" src="images/'.$image.'" style="max-width:100px;max-height:100px;overflow:hidden;vertical-align:middle">
  </div>
  <div class="p-2 mr-5" style="max-width:700px;width:100%"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '</div>
  <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>' . $time_remaining . '</div>
  </li>'
  );
}

function query($query) {
  require('db_credentials.php');
  $connection = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
  $result = mysqli_query($connection,$query);
  mysqli_close($connection);
  return $result;
}

function escape_string($data) {
  require('db_credentials.php');
  $connection = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
  $data = mysqli_real_escape_string($connection,$data);
  mysqli_close($connection);
  return $data;
}


 
  function send_email($recipient,$subject,$body){
  // Identify the sender, recipient, mail subject, and body
  $sender    = "Silly Goose <sillygoosecoursework@gmail.com>";
  $recipient = $recipient;
  $subject = $subject;
  $body = $body;
  // Identify the mail server, username, password, and port
  $server   = "smtp.gmail.com";
  $username = "sillygoosecoursework@gmail.com";
  $password = "&C6QLPbNS&6iBdRQ";
  $port     = "587";

  // Set up the mail headers
  $headers = array(
      "From"    => $sender,
      "To"      => $recipient,
      "Subject" => $subject
  );

  // Configure the mailer mechanism
  $smtp = Mail::factory("smtp",
      array(
          "host"     => $server,
          "username" => $username,
          "password" => $password,
          "auth"     => true,
          "port"     => $port
      )
  );

  // Send the message
  $mail = $smtp->send($recipient, $headers, $body,);

  if (PEAR::isError($mail)) {
      echo("<p>" . $mail->getMessage() . "</p>");
  } 
  //else {
  //     pass;
  //     #echo("<p>Message successfully sent!</p>");
  // }
  }
?>