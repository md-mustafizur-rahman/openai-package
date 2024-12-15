<?php
namespace OpenAiPackage\Facades;

use Illuminate\Support\Facades\Facade;

class OpenAi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'openservice'; // Alias for the service container
    }
}

?>