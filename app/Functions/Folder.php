<?php

//region Namespace
namespace App\Functions;
//endregion

//region Using

//endregion

class Folder
{
    public static function existFile($path): bool
    {
        $returnBool = false;

        try {
            if (file_exists($path) && is_file($path) && is_readable($path)) {
                $returnBool = true;
            }
        }
        catch (\Exception $e) {
            //region Log
            $this->container->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnBool;
    }
}


