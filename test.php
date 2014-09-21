<?php 
    require_once("./vendor/autoload.php");
    use PredictionIO\PredictionIOClient;
    $client = PredictionIOClient::factory(array("appkey" => "n35ZzH2tocqW10rbJuq4lUTyATmJO6Upo7WY4xcJiCPYJGG0lXR6AWAjxaXhxHNq"));

    var_dump($client);
?>
