<?php
// @codeCoverageIgnoreStart
if ( ! function_exists('dd')) {
    function dd()
    {
        array_map(function ($item) {
            var_dump($item);
        }, func_get_args());

        die(1);
    }
}
// @codeCoverageIgnoreEnd