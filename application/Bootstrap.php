<?php

require_once "vendor/autoload.php";

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	protected function _initApplication() {
		// Set default timezone
		$systemSetting = $this->getOption("system");
		date_default_timezone_set($systemSetting["timezone"]);
		$app = $this->getOption("app");
		defined("APP_CTX") || define("APP_CTX", $app["ctx"]);
		Zend_Registry::set("APP_SETTING", $app);
	}
	
	protected function _initLog(){
		$adminFormat = "%message%" . PHP_EOL;
		$adminFormatter = new Zend_Log_Formatter_Simple($adminFormat);
		$logSetting = $this->getOption("log");
		$date = date ( 'Y-m-d' );
		$sysLogWriter = new Zend_Log_Writer_Stream($logSetting["sys_log_path"].".".$date );
		$sysLogWriter->setFormatter($adminFormatter);
		$sysLogger = new Zend_Log($sysLogWriter);
		Zend_Registry::set("SYS_LOGGER", $sysLogger);
	
		$tropoLogWriter = new Zend_Log_Writer_Stream($logSetting["tropo_log_path"].".".$date );
		$tropoLogWriter->setFormatter($adminFormatter);
		$tropoLogger = new Zend_Log($tropoLogWriter);
		Zend_Registry::set("TROPO_LOGGER", $tropoLogger);
	}
	
	protected function _initTropoSetting() {
		$tropo_setting = $this->getOption("tropo");
		Zend_Registry::set("TROPO_SETTING", $tropo_setting);
	}
	
	protected function _initSpecialist() {
		$specialist_setting = $this->getOption("specialist");
		Zend_Registry::set("SPECIALIST_SETTING", $specialist_setting);
	}
	
	protected function _initEmail() {
		$email_setting = $this->getOption("mail");
		Zend_Registry::set("EMAIL_SETTING", $email_setting);
	}
	

    // Init PayPal app context
    protected function _initPayPalAppContext() {
        $logSetting = $this->getOption("log");
        $paypalSetting = $this->getOption("paypal");
        $paypalApiCtx = new ApiContext(new OAuthTokenCredential($paypalSetting["client_id"], $paypalSetting["secret"]));
        $paypalApiCtx->setConfig(array(
            'mode' => 'sandbox',
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => '../log/paypal.log',
            'log.LogLevel' => 'FINE',
            'validation.level' => 'log'
        ));
        Zend_Registry::set('PAYPAL_API_CTX', $paypalApiCtx);
    }

}

