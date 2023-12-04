<?php
/**
 * Send a raw HTTP header like function header() , but send $_POST-data with it using $_SESSION
 *
 * Data can be retrieved at destination via associative array $_SESSION["old_POST"]
 *
 * @param string $headerstring
 * @param bool $replace
 * @param int $response_code
 * @param array<string> $old_POST
 * @return void
 */
function redirect(string $headerstring, bool $replace = true, int $response_code = 0, array $old_POST = NULL): void
{
    $_SESSION["old_POST"] = $old_POST ?? null;
    header($headerstring, $replace, $response_code);
}
?>
