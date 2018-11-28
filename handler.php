<?php
/**
 * Created by PhpStorm.
 * User: vovichua
 * Date: 28.11.18
 * Time: 16:23
 */
require  'vendor/autoload.php';
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/endpoint-file.json');
/*  SEND TO GOOGLE SHEETS */
$client = new Google_Client;

$input = json_encode($_POST, true);
$data = json_decode($input, TRUE);

$error = [];

if (empty($data['city']) || empty($data['phone']) || empty($data['name']) || empty($data['e-mail'])) {
    array_push($error, ['type' => 'forms','description' => 'Need to fill in all forms']);
}

$validCodes = ['od', 'kv', 'kh', 'dp', 'vn'];
if (!in_array($data['city'], $validCodes)) {
    array_push($error, ['type' => 'city','description' => 'Wrong city ID']);
};

$reg = "/^\(\+380\)\d{9}$/i";
if (!preg_match_all($reg,$data['phone'])){
    array_push($error, ['type' => 'phone','description' => 'The phone must be written in the format (+380)XXXXXXXXX']);
}

if (!filter_var($data['e-mail'], FILTER_VALIDATE_EMAIL)) {
    array_push($error, ['type' => 'e-mail','description' => 'Invalid email format']);
}

if (empty($error)) {

    try {
        $client->useApplicationDefaultCredentials();
        $client->setApplicationName("Something to do with my representatives");
        $client->setScopes(['https://www.googleapis.com/auth/drive', 'https://spreadsheets.google.com/feeds']);
        if ($client->isAccessTokenExpired()) {
            $client->refreshTokenWithAssertion();
        }
        $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
        ServiceRequestFactory::setInstance(
            new DefaultServiceRequest($accessToken)
        );

        $spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
            ->getSpreadsheetFeed()
            ->getByTitle('endpoint');

        $worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
        $worksheet = $worksheets[0];
        $listFeed = $worksheet->getListFeed();
        if ($data['city'] == 'od') {
            $data['city'] = 'Одесса';
        } elseif ($data['city'] == 'kv') {
            $data['city'] = 'Киев';
        } elseif ($data['city'] == 'kh') {
            $data['city'] = 'Харьков';
        } elseif ($data['city'] == 'dp') {
            $data['city'] = 'Днепр';
        } else {
            $data['city'] = 'Винница';
        }
        $listFeed->insert([
            'date' => Date('Y-m-d H:i:s'),
            'city' => $data['city'],
            'phone' => $data['phone'],
            'name' => $data['name'],
            'mail' => $data['e-mail']
        ]);

        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok']);


    } catch (Exception $e) {
        echo $e->getMessage().' '.$e->getLine().' '.$e->getFile().' '.$e->getCode;

    }
} else {
    header('Content-Type: application/json');
    echo json_encode([ 'status' => 'error', 'errors' => [$error]]);
}

