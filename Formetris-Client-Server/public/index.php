<?php

require '../vendor/autoload.php';
require '../app/settings.php';

use \Slim\Http\Request;
use \Slim\Http\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$app = new \Slim\App;

define('CLIENT_ID', 'testclient');
define('CLIENT_SECRET_KEY', '$2y$10$O2AkWbnFnXbrrBkRSLbVn.IIgdQOySPwzt2eeucPYsGQSPCyPGkY2');

// -- Home

$app->get('/', function (Request $request, Response $response) {

    // -- Surveys To Create

    $cptToCreate = 0;

    $tabCourseToCreate[$cptToCreate]['data-course-id'] = 'xjkldhbzeh';
    $tabCourseToCreate[$cptToCreate]['data-course-name'] = "Préparation d'accessoires";
    $tabCourseToCreate[$cptToCreate]['data-course-type'] = 'webinar';
    $cptToCreate++;

    $tabCourseToCreate[$cptToCreate]['data-course-id'] = 'hufubspawx';
    $tabCourseToCreate[$cptToCreate]['data-course-name'] = "Optimisation du traitement des retours";
    $tabCourseToCreate[$cptToCreate]['data-course-type'] = 'elearning';
    $cptToCreate++;

    // -- Created Surveys

    $cptCreated = 0;

    $tabCourseCreated[$cptCreated]['data-course-id'] = 'yhdkiaezvdhl';
    $tabCourseCreated[$cptCreated]['data-course-name'] = "Assemblage de pièces moteur";
    $tabCourseCreated[$cptCreated]['data-course-type'] = 'classroom';
    $cptCreated++;

    function returnDisplayCourses($tabCourse, $type) {

        if (empty($tabCourse))
            return false;

        if ($type == 'tocreate') {
            $buttonClass = 'generate-survey';
            $buttonName = "Générer le questionnaire";
        } else if ($type == 'created') {
            $buttonClass = 'view-stats';
            $buttonName = "Voir les statistiques du questionnaire";
        } else {
            return false;
        }

        $resultToDisplay = null;
        foreach ($tabCourse as $key => $value) {
            $resultToDisplay.= "<tr>";
            $resultToDisplay.= "<td>" . $value['data-course-name'] . "</td>";
            $resultToDisplay.= "<td><input data-course-id='course-" . $value['data-course-id'] . "' data-course-name=\"" . $value['data-course-name'] . "\" type='button' class='" . $buttonClass . "' value='" . $buttonName . "' /></td>";
            $resultToDisplay.= "</tr>";
        }
        return $resultToDisplay;
    }

    $displayCourseToCreate = returnDisplayCourses($tabCourseToCreate, 'tocreate');
    $displayCourseCreated = returnDisplayCourses($tabCourseCreated, 'created');

    return $response->write("

    <html>
    <head>
    <script
    src='https://code.jquery.com/jquery-3.3.1.js'
    integrity='sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60='
    crossorigin='anonymous'></script>
    </head>
    <body>

    <h1>Liste des formations</h1>

    <table border='0'>
 
    <tr>
    <td colspan='2'><h2>Formation sans questionnaire</h2></td>
    <td rowspan='20'><div id='result'></div></td>
    </tr>

    $displayCourseToCreate

    <tr>
    <td colspan='2'><h2 style='margin-top:20px;'>Statistique sur les questionnaires</h2></td>
    </tr>

    $displayCourseCreated
    
    </table>

    <script>

    $('.generate-survey').on('click', function(){
        courseId = $(this).attr('data-course-id');
        courseName = $(this).attr('data-course-name');
        $.ajax({
            url : '/survey-generation',
            type : 'POST',
            data : 'courseId=' + courseId + '&courseName=' + courseName,
            dataType : 'html',

            success : function(result, status){
                //$(result).appendTo('#result');
//alert('Questionnaire \"' + courseName + '\" généré avec succès');
alert(result);
            },
 
            error : function(result, status, error){
alert(error);
            },
 
            complete : function(result, status){

            }
        });
    });

    </script>

    </body>
    </html>

    ");
});


$app->get('/survey-generation', function (Request $request, Response $response) {


    function storeToken($token) {
        return true;
    }

    function getStoredToken() {
        return '{
            "access_token":"19cd52d875727224ef53fbefdc999df52121b180"
            ,"expires_in":120
            ,"token_type":"Bearer"
            ,"scope":null
            ,"expires_at":"2019-01-09 18:00:00"
            ,"hash":"$23$ujdvpl78023bbdyt$diolhb"
        }';

        //return false;
    }

    $client = new Client(['base_uri' => 'http://localhost:9000']);
 
    $response = $client->request(
        'GET',
        '/survey-generation',
        [
            'headers' => [
                    'auth' =>
                    [
                        CLIENT_ID, 
                        CLIENT_SECRET_KEY
                    ]
                ]
        ]
    );

    $statusCode = $response->getStatusCode();

print $statusCode;

//var_dump($response);










/*
    $client_id = CLIENT_ID;
    $client_secret = CLIENT_SECRET_KEY;
    //$redirect_uri = 'http://localhost:9000/survey-generation';

    $token = getStoredToken();

    $POST = $request->getParsedBody();

    $courseId = $POST['courseId'];
    $courseName = $POST['courseName'];
    $courseType = 'classroom';

    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, "http://localhost:9000/survey-generation");
    //curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/token.php");
    curl_setopt($ch, CURLOPT_POST, TRUE);

    //$code = 1; // TEST

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
    //'code' => $code,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'token' => $token,
    //'redirect_uri' => $redirect_uri,
    'course_id' => $courseId,
    'course_name' => $courseName,
    'course_type' => $courseType,
    'grant_type' => 'authorization_code'
    ));

    $data = curl_exec($ch);
    
    var_dump($data);
*/

});

// -- Exec Slim
$app->run();