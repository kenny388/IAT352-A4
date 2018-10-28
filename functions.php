<?php

// Date Format Check Function Retrieved from https://stackoverflow.com/questions/19271381/correctly-determine-if-date-string-is-a-valid-date-in-that-format
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}
?>

<?php

//Below are functions extracted from Wk7 challenge

function url_for($script_path) {
  // add the leading '/' if not present
  if($script_path[0] != '/') {
    $script_path = "/" . $script_path;
  }
  return WWW_ROOT . $script_path;
}

function u($string="") {
  return urlencode($string);
}

function raw_u($string="") {
  return rawurlencode($string);
}

function h($string="") {
  return htmlspecialchars($string);
}

function error_404() {
  header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit();
}

function error_500() {
  header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
  exit();
}

function redirect_to($location) {
  header("Location: " . $location);
  exit;
}

function is_post_request() {
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request() {
  return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function display_errors($errors=array()) {
  $output = '';
  if(!empty($errors)) {
    $output .= "<div class=\"errors\">";
    $output .= "<label>Please fix the following errors:</label>";
    $output .= "<ul>";
    foreach($errors as $error) {
      $output .= "<li><label>" . h($error) . "</label></li>";
    }
    $output .= "</ul>";
    $output .= "</div>";
  }
  return $output;
}

function get_and_clear_session_message() {
  if(isset($_SESSION['message']) && $_SESSION['message'] != '') {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
    return $msg;
  }
}

function display_session_message() {
  $msg = get_and_clear_session_message();
  if(!is_blank($msg)) {
    return '<div id="message">' . h($msg) . '</div>';
  }
}

?>
