<?php

$env = $app->detectEnvironment(function()
{
    return $_SERVER['MY_LARAVEL_ENV'];
});

?>