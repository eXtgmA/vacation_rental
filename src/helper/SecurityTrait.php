<?php
namespace src\helper;

trait SecurityTrait
{
    /**
     * @param array<mixed> $item
     * @return void
     */
    function sanitize(&$item)
    {
        array_walk_recursive($item, function (&$item) {
            $item=htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
        });
    }
}
