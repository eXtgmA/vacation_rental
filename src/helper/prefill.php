<?php
/**
 * Prefill form fields by name
 *
 * Data source: array $_SESSION["old_POST"]
 *
 * @param string $name
 * @return void
 */
function prefill(string $name) : void {
    echo $_SESSION["old_POST"][$name] ?? "";
}
?>
