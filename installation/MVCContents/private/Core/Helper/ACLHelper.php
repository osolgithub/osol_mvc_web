<?php
namespace OsolMVC\Core\Helper;
class ACLHelper extends \OsolMVC\Core\CoreParent{
	private $travesedParentIds = [];
    public function checkIsUserActive($user_id)
    {
        return true;
    }//public function checkIsUserActive($user_id)
    public function hasPermission($requiredPermissions,$user_id = 0)
	{
		/*
		1. check if user is active from `osol_mvc_user`, else return false
		2. get group_id s of user from `osol_mvc_acl_user_2_group` --> $allGroupIdsRowsOfUser  --> $allGroupIdsOfUser
		3. if(level 1 admin ie group_id=4) return true
		4. get parent_group_id of group from `osol_mvc_acl_user_groups`
		5. get permissions of groups `osol_mvc_acl_group_permissions` & `osol_mvc_acl_user_permissions`, $allAvailableGroupPermissions
		6. get declined permisions for group_ids from `osol_mvc_acl_group_declined_permissions`, $allDeclinedGroupPermissions
		7. get declined permissions for user_id from `osol_mvc_acl_user_declined_permissions`, $allDeclinedUserPermissions 
		8. get all available permissions for group into an array , $availableMinusDeclinedPermissions = array_diff($allAvailableGroupPermissions, $allDeclinedGroupPermissions,  $allDeclinedUserPermissions )
		9. return (count(array_diff($requiredPermissions,$availableMinusDeclinedPermissions))>0);
		*/
		$requestVarHelper = $this->getCoreHelper("RequestVar");
		if($user_id == 0)
		{
			$user_id = $requestVarHelper->getSessionVar("user_id");
			if(is_null($user_id))return false;
		}//if($user_id == 0)
		$db = $this->getDB();
        $dbSettings = $this->getSiteConfig()->getDBSettings();
        $table_prefix = $dbSettings['table_prefix'];
	//1. check if user is active from `osol_mvc_user`, else return false
		$stmt = "SELECT `active` FROM `{$table_prefix}user` WHERE `id` = ?";
   
        $preparedSQl = $db->getReplacedSQLAdv($stmt,
                                                        "i",
                                                        $user_id
                                                    );

        /* $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);   */
        $rowsOfUser = $db->selectPS($stmt,"i",$user_id);
		if(count($rowsOfUser) == 0 || $rowsOfUser[0]['active'] == 0) return false;
		
		$availableMinusDeclinedPermissions = $this->getAvailableMinusDeclinedPermisions($user_id);
	//9. return (count(array_diff($availablePermissions,$requiredPermissions))>0)
		$declinedPermissionsAmongRequired = array_diff($requiredPermissions,$availableMinusDeclinedPermissions);
		/* global $requiredPermissions;
		$hasPermissionsAmongRequired = array_filter($availableMinusDeclinedPermissions, function($v){
																		global $requiredPermissions;
																		 return (in_array($v, $requiredPermissions));
																		}); */
		$hasPermissionsAmongRequired = array_intersect($requiredPermissions,$availableMinusDeclinedPermissions);
		//echo "LINE#:" . __LINE__ . " in file ". __FILE__ ."requiredPermissions <pre>".print_r($requiredPermissions,true)."</pre>";
		
		/* echo "LINE#:" . __LINE__ . " in file ". __FILE__ ."
					<br />hasPermissionsAmongRequired<pre>".print_r($hasPermissionsAmongRequired,true)."</pre>"; */
		
		//echo "LINE#:" . __LINE__ . " in file ". __FILE__ ."declinedPermissionsAmongRequired<pre>".print_r($declinedPermissionsAmongRequired,true)."</pre>";
		
		//return (count($declinedPermissionsAmongRequired)==0);
		return (count($hasPermissionsAmongRequired)>0);
		
		
	}//public function hasPermission($user_id,$requiredPermissions)
	public function getAvailableMinusDeclinedPermisions($user_id)
	{
		$requestVarHelper = $this->getCoreHelper("RequestVar");
		if($user_id == 0)
		{
			$user_id = $requestVarHelper->getSessionVar("user_id");
			if(is_null($user_id))return false;
		}//if($user_id == 0)
		if($user_id == $requestVarHelper->getSessionVar("user_id"))
		{
			$sessionValAvailableMinusDeclinedPermissions =  $requestVarHelper->getSessionVar("availableMinusDeclinedPermissions");
			if(!is_null($sessionValAvailableMinusDeclinedPermissions))return $sessionValAvailableMinusDeclinedPermissions;
		}//if($user_id == $requestVarHelper->setSessionVar("user_id"))
			
		$db = $this->getDB();
        $dbSettings = $this->getSiteConfig()->getDBSettings();
        $table_prefix = $dbSettings['table_prefix'];
		
		//2. get group_id s of user from `osol_mvc_acl_user_2_group`
        $stmt = "SELECT `group_id` FROM `{$table_prefix}acl_user_2_group` WHERE `user_id` = ?";
   
        $preparedSQl = $db->getReplacedSQLAdv($stmt,
                                                        "i",
                                                        $user_id
                                                    );

        /* $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);   */
        $allGroupIdsRowsOfUser = $db->selectPS($stmt,"i",$user_id);
		//echo "LINE#:" . __LINE__ . " in file ". __FILE__ ."<pre>".print_r($allGroupIdsRowsOfUser,true)."</pre>";
		$allGroupIdsOfUser = array_map(function($v){return $v['group_id'];},$allGroupIdsRowsOfUser);
		if(count($allGroupIdsOfUser) == 0) return [];
		//echo "LINE#:" . __LINE__ . " in file ". __FILE__ ."<pre>".print_r($allGroupIdsOfUser,true)."</pre>";
	//3. if(level 1 admin ie group_id=4) return true
		if(in_array(4,$allGroupIdsOfUser)){
			
			$stmt = "select a.`permission_name` from `{$table_prefix}acl_user_permissions` a where a.`permission_name` like '%admin.core.%'";
			$availableMinusDeclinedPermissionsRows = $db->select_sql($stmt);
			$availableMinusDeclinedPermissions = array_map(function($v){return $v['permission_name'];},$availableMinusDeclinedPermissionsRows);
			return $availableMinusDeclinedPermissions;
		}
	//4. get parent_group_ids of groups from `osol_mvc_acl_user_groups`
		//(only one level of inheritance is allowed, ie if parent_group_id cannot be of group having another parent_group_id)
		
		//$this->travesedParentIds = [];
		$concatGroupIds = join(",",$allGroupIdsOfUser);
		$stmt = "SELECT `parent_group_id` FROM `{$table_prefix}acl_user_groups` WHERE `group_id` in({$concatGroupIds})";
   
        $preparedSQl = $stmt; /* $db->getReplacedSQLAdv($stmt,
                                                        "i",
                                                        $user_id
                                                    ); */

        /* $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);   */
        $allParentIdRowsOfGroupIdsRowsOfUser = $db->select_sql($stmt);
		//echo "LINE#:" . __LINE__ . " in file ". __FILE__ ."<pre>".print_r($allParentIdRowsOfGroupIdsRowsOfUser,true)."</pre>";
		$allParentIdsOfGroupIdsOfUser = array_map(function($v){return $v['parent_group_id'];},$allParentIdRowsOfGroupIdsRowsOfUser);
		//echo "LINE#:" . __LINE__ . " in file ". __FILE__ ."<pre>".print_r($allParentIdsOfGroupIdsOfUser,true)."</pre>";
		$allGroupIdsToCheck =  array_merge($allGroupIdsOfUser,$allParentIdsOfGroupIdsOfUser);
		$concatGroupIds = join(",",$allGroupIdsToCheck);
		//echo "LINE#:" . __LINE__ . " in file ". __FILE__ ."<pre>".print_r($allGroupIdsToCheck,true)."</pre>";
	//5. get permissions of groups `osol_mvc_acl_group_permissions` & `osol_mvc_acl_user_permissions`, $allAvailableGroupPermissions
		/*
		select a.`permission_name` from `osol_mvc_acl_user_permissions` a, 
												`osol_mvc_acl_group_permissions` b 
			where a.`permission_id` = b.`permission_id` and b.`group_id` in ({$concatGroupIds})
		*/
		$stmt = "select a.`permission_name` from `{$table_prefix}acl_user_permissions` a, 
												`{$table_prefix}acl_group_permissions` b 
			where a.`permission_id` = b.`permission_id` and b.`group_id` in ({$concatGroupIds})";
   
        $preparedSQl = $stmt; /* $db->getReplacedSQLAdv($stmt,
                                                        "i",
                                                        $user_id
                                                    ); */

        /* $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);   */
        $allAvailableGroupPermissionRows = $db->select_sql($stmt);
		$allAvailableGroupPermissions = array_map(function($v){return $v['permission_name'];},$allAvailableGroupPermissionRows);
		if(count($allAvailableGroupPermissions) == 0) return [];
	//6. get declined permisions for group_ids from `{$table_prefix}acl_group_declined_permissions`, $allDeclinedGroupPermissions
		$stmt = "select a.`permission_name` from `{$table_prefix}acl_user_permissions` a, 
												`{$table_prefix}acl_group_declined_permissions` b 
			where a.`permission_id` = b.`permission_id` and b.`group_id` in ({$concatGroupIds})";
		$preparedSQl = $stmt; /* $db->getReplacedSQLAdv($stmt,
                                                        "i",
                                                        $user_id
                                                    ); */

        /* $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);   */
        $allDeclinedGroupPermissionRows = $db->select_sql($stmt);
		$allDeclinedGroupPermissions = array_map(function($v){return $v['permission_name'];},$allDeclinedGroupPermissionRows);
	//7. get declined permissions for user_id from `{$table_prefix}acl_user_declined_permissions`, $allDeclinedUserPermissions 
		$stmt = "select a.`permission_name` from `{$table_prefix}acl_user_permissions` a, 
												`{$table_prefix}acl_user_declined_permissions` b 
			where a.`permission_id` = b.`permission_id` and b.`user_id` in ({$concatGroupIds})";
		$preparedSQl = $db->getReplacedSQLAdv($stmt,
                                                        "i",
                                                        $user_id
                                                    );

        /* $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);   */
        $allDeclinedUserPermissionsRows = $db->select_sql($stmt);//$db->selectPS($stmt,"i",$user_id);
		$allDeclinedUserPermissions = array_map(function($v){return $v['permission_name'];},$allDeclinedUserPermissionsRows); 
	//8. get all available permissions for group into an array , $availablePermissions = array_diff($allAvailableGroupPermissions, $allDeclinedGroupPermissions,  $allDeclinedUserPermissions )
		$availableMinusDeclinedPermissions = array_diff($allAvailableGroupPermissions, $allDeclinedGroupPermissions,  $allDeclinedUserPermissions );
		if($user_id == $requestVarHelper->getSessionVar("user_id"))
		{
			$requestVarHelper->setSessionVar("availableMinusDeclinedPermissions",$availableMinusDeclinedPermissions);
		}//if($user_id == $requestVarHelper->setSessionVar("user_id"))
		return $availableMinusDeclinedPermissions;
	}//public function getAvailableMinusDeclinedPermisions($user_id)
	
	
		
    public function checkUserHasPermissionOld($user_id,$permission)
    {
		/**
		ACL Tables
		1. {$table_prefix}acl_user_permissions --> stores permission names. eg: readOnly, Write etc
		2. {$table_prefix}acl_user_groups --> stores group names . Eg: author, editor etc
		3. {$table_prefix}acl_group_permissions --> stores permission ids(in {$table_prefix}acl_user_permissions) available for a group . 
		4. {$table_prefix}acl_declined_permissions --> if any particular permission(in {$table_prefix}acl_user_permissions) is declined for a user
		5. {$table_prefix}acl_user_2_group -> maps user to a group
		
		permissions would be like article.create  ,  article.delete  ,  article.edit  ,  article.read
		Steps
		1. get group id from group name from `{$table_prefix}acl_user_groups` and check if that group id is set for user from `{$table_prefix}acl_user_2_group` 
		if (not){throw new Exception("NOT_AUTHORIZED_USER_NOT_IN_GROUP");}
		2. get permissions id of the group from `{$table_prefix}acl_group_permissions` and permission name from `{$table_prefix}acl_user_permissions`
		3. get permissions declined for user from {$table_prefix}acl_declined_permissions
		4. $allowed_permissions = array_diff($group_permissions,$declined_permissions)
		5. $declined_permissions_required_for_page = array_diff($allowed_permissions, $permissions_required_for_page);
		6. if( count($declined_permissions_required_for_page)){throw new Exception("NOT_AUTHORIZED_SPECIFIC_PERMISSION");}
		*/
        /**
         *  
         *
         * @param [type] $user_id
         * @param [type] $permission_id
         * @return void
         * @details 
         * FIRST AND FOREMOST, CHECK IF USER IS DEACTIVATED
         * IF NOT DO FOLLOWING CHECKS
         * 	1. get permision from {$table_prefix}acl_user_permissions table
         *  2. check is individually declined for user, ie in {$table_prefix}acl_declined_permissions
         *  3. get groups of user from {$table_prefix}acl_user_2_group
         *  4. check if permision is allowed for any of groups of user, form  
         *      {$table_prefix}acl_user_groups & 
         *      {$table_prefix}acl_group_permissions       
         */
        
        if(!$isUserActive = $this->checkIsUserActive($user_id))
        {
            throw new Exception("USER_IS_INACTIVE");
        }//if(!$isUserActive = $this->checkIsUserActive($user_id))
		$db = $this->getDB();
        $dbSettings = $this->getSiteConfig()->getDBSettings();
        $table_prefix = $dbSettings['table_prefix'];

        $stmt = "SELECT 'permission_id` FROM {$table_prefix}acl_user_permissions WHERE `permission` = ? LIMIT 1";
   
        $preparedSQl = $db->getReplacedSQLAdv($stmt,
                                                        "s",
                                                        $permission
                                                    );

        $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);  
        $rowsOfPermission = $db->selectPS($stmt,"s",$id);
        if(count($rowsOfPermission) == 0)
        {
            throw new Exception("INVALID_PERMISSION_GIVEN");
        }//if(count($rowsOfPermission) > 0)
        
        $permission_id = $rowsOfPermission[0]['permission_id'];
        //2. check is individually declined for user, ie in {$table_prefix}acl_declined_permissions
        $stmt = "SELECT `user_id` FROM {$table_prefix}acl_declined_permissions WHERE `permission_id` = ? LIMIT 1";
   
        $preparedSQl = $db->getReplacedSQLAdv($stmt,
                                                        "i",
                                                        $permission_id
                                                    );

        $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);  
        $rowsOfdeclinedPermission = $db->selectPS($stmt,"s",$id);
        if(count($rowsOfdeclinedPermission) > 0)
        {
            throw new Exception("PERMISSION_DECLINED_PERSONALLY_FOR_USER");
        }//if(count($rowsOfPermission) > 0)
        //3. get groups of user from {$table_prefix}acl_user_2_group
        $stmt = "SELECT `group_id` FROM {$table_prefix}acl_user_2_group WHERE `user_id` = ?";
   
        $preparedSQl = $db->getReplacedSQLAdv($stmt,
                                                        "i",
                                                        $user_id
                                                    );

        $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);  
        $rowsOfusers_group = $db->selectPS($stmt,"s",$id);
        if(count($rowsOfusers_group) == 0)// user doesnt have any previlleges
        {
            throw new Exception("USER_NOT_ALLOWED");
        }//if(count($rowsOfPermission) > 0)
        /*  4. check if permision is allowed for any of groups of user, form  
         *      {$table_prefix}acl_user_groups (group_id)& 
         *      {$table_prefix}acl_group_permissions  (permission_id)     
         */
        $totGroups = count($rowsOfusers_group);
        $groupsOfUser = array();
        foreach($rowsOfusers_group as $rowOfusers_group)
        {
            $groupsOfUser[] = $rowOfusers_group['group_id'];
        }//foreach($rowsOfusers_group as $rowOfusers_group)
        $groupIdsTypeStr = str_repeat("i", $totGroups);
        $groupIdsQs4SQL = join(",",array_fill(0,$totGroups,"?"));
        
        $stmt = "SELECT `group_id`,`permission_id` FROM `{$table_prefix}acl_group_permissions`
                                                        WHERE
                                                        `group_id` IN ( " . $groupIdsQs4SQL . ")";
   
        $preparedSQl = $db->getReplacedSQLAdv($stmt,
                                                        $groupIdsTypeStr,
                                                        $groupsOfUser
                                                    );

        $logMessage = "select SQL is " . $preparedSQl ."\r\n";
        $this->getLogHelper()->doLog($logMessage);  
        $rowsOfusers_group = $db->selectPS($stmt,"s",$id);
        if(count($rowsOfusers_group) == 0)// user doesnt have any previlleges
        {
            throw new Exception("USER_NOT_ALLOWED");
        }//if(count($rowsOfPermission) > 0)
        return true;

    }//public function checkUserHasPermission($user_id,$permission)

}//class ACLHelperClass extends \OsolMVC\Core\CoreParent{
?>