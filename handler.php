<?php
/**
 * Created by PhpStorm.
 * User: vovichua
 * Date: 28.11.18
 * Time: 16:23
 */

$input = json_encode($_POST, true);
$data = json_decode($input, TRUE);

$error = [];

if (!$data['city'] || !$data['phone'] || !$data['name'] || !$data['e-mail']) {
    array_push($error, "Нужно заполнить все поля");
}

if (!$data['city'] == ('od' || 'kv' || 'kh' || 'dp' || 'vn')) {
    array_push($error, "Wrong city ID");
}

$reg = "/^\(\+380\)\d{9}$/i";
if (!preg_match_all($reg,$data['phone'])){
    array_push($error, "Номер должен быть записан в формате (+380)XXXXXXXXX");
}

if (!filter_var($data['e-mail'], FILTER_VALIDATE_EMAIL)) {
    array_push($error, "Invalid email format");
}

//echo "<pre>";
//var_dump($error);die;

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
        'date' => Date('Y-m-d H:i:s'),
        'city' => $data['city'],
        'phone' => $data['phone'],
        'name' => $data['name'],
        'mail' => $data['e-mail']
    ]);

    echo json_encode([ 'status' => 'ok']);

}catch(Exception $e){
    echo $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getCode;

    echo json_encode([ 'status' => 'error', 'errors' => [$error]]);
}

/*  SEND TO GOOGLE SHEETS */



