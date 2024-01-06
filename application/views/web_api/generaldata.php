<!DOCTYPE html>
<html lang="en">
<head>
  <title><?=stripslashes($title)?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
    .privacy_policy    h2{
        font-family: 'Open Sans', sans-serif;
    text-align: left;
    font-size: 30px;
    color: #031b26;
    text-align: center;
        }
    .privacy_policy   p {
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    line-height: 28px;
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    font-weight: 400;
    text-align: left;
    color: #6c757d;
    margin-bottom: 0;
}
    </style>
    </head>
    <body>
        <div class="privacy_policy">
               <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2> <?=stripslashes($title)?></h2>
                        <?=stripslashes($description)?>
                        
                        <br>
                    </div>
                </div>
               </div>
        </div>

    </body>
    </html>
