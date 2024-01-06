<!DOCTYPE html>
<html>
<head>
  <title>DealzArabia</title>
</head>
<body>
  <p>We're sorry, but there seems to be an issue with the card you provided. Please verify the card details and try again. If the problem persists, please contact your card issuer for more information.</p>
  <script>
    // Automatically redirect the parent window when this iframe page loads
    window.onload = function() {
      parent.window.location.href = '<?=base_url("order-fail")?>';
    };
  </script>
</body>
</html>
