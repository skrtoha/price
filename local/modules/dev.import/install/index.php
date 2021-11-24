<?php

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
Class DEV_IMPORT extends CModule{
    var $exclusionAdminFiles;
    
    public function __construct(){
        
        $this->exclusionAdminFiles= [];
        $this->MODULE_ID = "dev.import";
        $this->MODULE_VERSION = '1.0';
        $this->MODULE_VERSION_DATE = '2021-02-20 00:00:00';
        $this->MODULE_NAME =  "Импорт прайса";
        $this->MODULE_DESCRIPTION = "API импорта прайсов";
        $this->PARTNER_NAME = "Dev";
        $this->PARTNER_URI = "http://dev.dev";
        
        $this->MODULE_SORT = 1;
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = "Y";
        $this->MODULE_GROUP_RIGHTS = "Y";
    }
    
    public function DoInstall(){
        global $APPLICATION;
        \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
    }
    
    function DoUninstall(){
        global $APPLICATION;
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}

?>