<?php
$this->sessionSettings =  array(
                                            //'site_time_zone' => 'Asia/Kolkata',// this is later set in ClassSiteConfig =  $this->siteSettings['site_time_zone'];
                                            'session_life_time' => 31536000,//60 * 60 * 24 *365 ->  365 day cookie lifetime                                            
                                            //redundant_time is visited but not logged in 
                                            'redundant_time' => 86400,   // ->60 * 60 * 24   1 day cookie lifetime
                                            'idle_allowed' => 2592000,//60 * 60 * 24 * 30 ie 30 days
											'destroyDuplicateSessionURL' => "destroyDuplicateSession"

                                        );
?>