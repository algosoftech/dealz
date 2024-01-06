<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://restapi.smscountry.com/v0.1/Accounts/d1dhcTNWU0Q0SEtHV3pqUWt6YUc6M2dmR09MMERveGlqUHVSTWFCSFROM2FFSTVJeVIyNHZXYjRyZFNvUw=/SMSes/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"Text\": \"Sample text\",
  \"Number\": \"919711882641\",
  \"SenderId\": \"ONTHPM\",
  \"DRNotifyUrl\": \"https://www.domainname.com/notifyurl\",
  \"DRNotifyHttpMethod\": \"POST\",
  \"Tool\": \"API\"
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json"
));

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);