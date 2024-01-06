<!DOCTYPE html>
<html>
<head>
  <title>DealzArabia</title>
</head>
<body>
  <p>Your payment has been successfully processed! Please do not close or refresh the page while we finalize your transaction.</p>
  <script>
    // Automatically redirect the parent window when this iframe page loads
    window.onload = function() {
      parent.window.location.href = '<?=base_url("/order-success")?>';
    };
  </script>
</body>
</html>
