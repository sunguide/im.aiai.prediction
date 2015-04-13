<?php

    if(defined("APP_RUN_ENV") && APP_RUN_ENV){

        $app_run_env = strtolower(APP_RUN_ENV);

        $filePath = __DIR__."/config.{$app_run_env}.php";
        if(file_exists($filePath)){
            return include($filePath);
        }
    }

?>