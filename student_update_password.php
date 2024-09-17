<?php
include('dbcon.php');
include('session.php');

$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$retype_password = $_POST['retype_password'];

// Retrieve the current password from the database
$result = mysqli_query($conn, "SELECT password FROM student WHERE student_id = '$session_id'") or die(mysqli_error($conn));
$row = mysqli_fetch_assoc($result);
$stored_password = $row['password'];

// Check if the entered current password matches the stored password
if (password_verify($current_password, $stored_password) and $row['status'] = 'Registered') {
  // Current password matches, proceed with password update logic

  // Additional validation for the new password
  if (strlen($new_password) >= 8 && preg_match('/[A-Za-z]/', $new_password) && preg_match('/\d/', $new_password) && preg_match('/[^A-Za-z0-9]/', $new_password)) {
    // New password meets the requirements

    if ($new_password === $retype_password) {
      // Retyped password matches the new password

      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

      mysqli_query($conn, "UPDATE student SET password = '$hashed_password' WHERE student_id = '$session_id'") or die(mysqli_error($conn));
	  mysqli_query($conn, "UPDATE student SET status = 'Registered' WHERE student_id = '$session_id'") or die(mysqli_error($conn));

      // Password update successful
      $response = array(
        'status' => 'success',
        'message' => 'Password updated successfully.'
      );
    } else {
      // Retyped password does not match the new password
      $response = array(
        'status' => 'error',
        'message' => 'Retyped password does not match the new password.'
      );
    }
  } else {
    // New password does not meet the requirements
    $response = array(
      'status' => 'error',
      'message' => 'New password should have a minimum of 8 characters with at least one letter, one number, and one special character.'
    );
  }
} else if (($current_password == $stored_password) and $row['status'] = 'Unregistered') {
	  if (strlen($new_password) >= 8 && preg_match('/[A-Za-z]/', $new_password) && preg_match('/\d/', $new_password) && preg_match('/[^A-Za-z0-9]/', $new_password)) {
    if ($new_password === $retype_password) {
      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
      mysqli_query($conn, "UPDATE student SET password = '$hashed_password' WHERE student_id = '$session_id'") or die(mysqli_error($conn));
	  mysqli_query($conn, "UPDATE student SET status = 'Registered' WHERE student_id = '$session_id'") or die(mysqli_error($conn));
      $response = array(
        'status' => 'success',
        'message' => 'Password updated successfully.'
      );
    } else {
      $response = array(
        'status' => 'error',
        'message' => 'Retyped password does not match the new password.'
      );
    }
  } else {
    $response = array(
      'status' => 'error',
      'message' => 'New password should have a minimum of 8 characters with at least one letter, one number, and one special character.'
    );
  }
} else {
  // Current password does not match
  $response = array(
    'status' => 'error',
    'message' => 'Current password is incorrect.'
  );
}

// Send the response back to the AJAX request
echo json_encode($response);
