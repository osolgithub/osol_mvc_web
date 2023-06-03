<?php
$mvcArguments = array();
$mvcArguments['PROJECT_PATH'] = "projectBase/PR11";
$mvcArguments['PRIVATE_FOLDER_ROOT'] = "private";
$mvcArguments['DB_SETTINGS'] = array(
                                        'DB_USER'  =>   "root",
                                        'DB_PASS'  =>   "",
                                        'DB_SERVER'  =>   "localhost",
                                        'DB_NAME'  =>   "osol_mvc",
                                        'table_prefix' => "osol_mvc_",
                                        'log_queries' => true,
                                        'query_log_type' => 'file',//'echo'
                                        );



/***TO BE SET***/
$mvcArguments['siteSettings'] = array(
                                    'site_title' => 'OSOL_MVC_SITE_TITLE',
                                    // sample time zones 'America/Los_Angeles'  , 'America/New_York' etc
                                    'system_time_zone' => 'UTC', //timezone in php.ini
                                    "mysql_time_zone" => "Asia/Kolkata",//UTC",
                                    'site_time_zone' => 'Asia/Kolkata',
									'autoRegistrationEnabled' => true,
									'frontendTemplate' => 'default',
									'adminTemplate' => 'bsAdmin'

                                    );
$mvcArguments['sessionSettings'] =  array(
                                            //'site_time_zone' => 'Asia/Kolkata',// this is later set in ClassSiteConfig =  $this->siteSettings['site_time_zone'];
                                            'session_life_time' => 31536000,//60 * 60 * 24 *365 ->  365 day cookie lifetime                                            
                                            //redundant_time is visited but not logged in 
                                            'redundant_time' => 86400,   // ->60 * 60 * 24   1 day cookie lifetime
                                            'idle_allowed' => 2592000,//60 * 60 * 24 * 30 ie 30 days
											'destroyDuplicateSessionURL' => "destroyDuplicateSession"

                                        );
$mvcArguments['smtpSettings'] = array(
										"smtpHost" => 'smtp.gmail.com',
										"port" => 587,
										"smtpLogin" => 'your email',
										"smtpPassword" => 'your password',
										"SMTPSecure" => 'TLS', // could be TLS or SMTPS
										"mailSendFrom" => 'your email',
										"mailSendFromName" => 'OSOL MVC Contact',
										"sendToEmail" => 'your email',
										"sendToName" => 'YOUR SITE NAME',
										);
$mvcArguments['googleAppSettings'] =  array(
                                        
                                        "appName2Display" => 'YOUR SITE NAME',
                                        "clientId" => 'client ID',
                                        "clientSecret" => 'client Secret',
										"redirectURL" => /* $this->getFullURL2Root() . */ "Account/googleLoginRedirect"
                                        );
$mvcArguments['facebookAppSettings'] =  array(
                                        
                                        "app_id" => 'app_id',
                                        "app_secret" => 'app_secret',                                        
										"OAuthRedirectURI" => /* $this->getFullURL2Root() . */ "Account/facebookLoginRedirect"
										);										
?>