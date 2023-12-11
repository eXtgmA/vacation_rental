<?php

/**
 *  Send a raw HTTP header like function header() , but send $_POST-data with it using $_SESSION
 *
 *  Data can be retrieved at destination via associative array $_SESSION["old_POST"]
 *
 * @param string $headerstring
 * @param int $response_code
 * @param array<string|int>|null $old_POST
 * @param bool $replace
 * @return void
 */
function redirect(string $headerstring, int $response_code, array $old_POST = null, bool $replace = true): void
{
    if ($old_POST != null) {
        $_SESSION["old_POST"] = $old_POST;
    }
    header("location: {$headerstring}", $replace, $response_code);
}
?>
