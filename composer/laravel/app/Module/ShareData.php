<?php
namespace App\Module;

class ShareData {
    const TITLE = 'Laravel部落格網站';
    public static function GetSex($nUserData_Sex)
    {
        return ($nUserData_Sex === 1)?'男':'女';
    }
}
?>