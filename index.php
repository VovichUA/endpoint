<?php
/**
 * Created by PhpStorm.
 * User: vovichua
 * Date: 28.11.18
 * Time: 14:55
 */
require  'vendor/autoload.php';
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/endpoint-file.json');
/*  SEND TO GOOGLE SHEETS */
$client = new Google_Client;

try{
    $client->useApplicationDefaultCredentials();
    $client->setApplicationName("Something to do with my representatives");
    $client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);
    if ($client->isAccessTokenExpired()) {
        $client->refreshTokenWithAssertion();
    }
    $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
    ServiceRequestFactory::setInstance(
        new DefaultServiceRequest($accessToken)
    );
    // Get our spreadsheet
    $spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
        ->getSpreadsheetFeed()
        ->getByTitle('endpoint');
    // Get the first worksheet (tab)
    $worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
    $worksheet = $worksheets[0];
    $listFeed = $worksheet->getListFeed();
    $listFeed->insert([
        'date' => date_create('now')->format('Y-m-d H:i:s'),
        'city' => $_POST['city'],
        'phone' => $_POST['phone'],
        'name' => $_POST['name'],
        'mail' => $_POST['e-mail']
    ]);

}catch(Exception $e){
    echo $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getCode;
}

/*  SEND TO GOOGLE SHEETS */


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Главная</title>
</head>
<body>
    <form method="post" action="index.php">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label for="exampleFormControlSelect1">Example select</label>
                <select class="form-control" id="exampleFormControlSelect1" name="city">
                    <option>od</option>
                    <option>kv</option>
                    <option>kh</option>
                    <option>dp</option>
                    <option>vn</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Телефон в формате +(38XXX)XXX-XX-XX</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Телефон в формате +(XXX)XXX-XX-XX" pattern="\+(38\d{3}\)\d{3}-\d{2}-\d{2}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="validationCustom03">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="e-mail" name="e-mail" aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <button class="btn btn-primary" type="submit">Отправить</button>
        </div>
    </form>

</body>
</html>
