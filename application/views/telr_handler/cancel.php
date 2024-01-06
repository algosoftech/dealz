<!DOCTYPE html>
<html>
<head>
  <title>DealzArabia</title>
</head>
<body>
  <p>Cancellation Successful<p>
<p>
Your order has been successfully cancelled. If you have any questions or need further assistance, please don't hesitate to reach out to our support team. Thank you for considering our service.</p>
  <script>
    // Automatically redirect the parent window when this iframe page loads
    window.onload = function() {
      parent.window.location.href = '<?=base_url("/")?>';
    };
  </script>
</body>
</html>
