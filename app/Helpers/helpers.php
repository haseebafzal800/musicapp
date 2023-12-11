<?php

function json_response($response = array(), $code = 201)
{
    return response()->json($response, $code);
}

?>