<?php

namespace ApCode\Billet;

interface StorageInterface
{
    function clearCache();
    function getRecord($class, $id);
    function saveRecord(BaseBillet $billet);
    function removeRecord(BaseBillet $billet);
}