<?php
namespace OsolMVC\Core\Helper;
class AccountHelper extends \OsolMVC\Core\CoreParent{
    public function checkIsUserActive($user_id)
    {
        return true;
    }//public function checkIsUserActive($user_id)
    public function getUserRecordWithId($id)
	{
		$database = $this->getDB();
		$table_prefix = $database->getTablePrefix();
		$selectUserSQL = "select * from `{$table_prefix}user` where id = ?";
		$userRecords  = $database->selectPS($selectUserSQL,"i",$id);
		if(count($userRecords) == 0)
        {
            throw new \Exception("INVALID_USER_ID");
        }//if(count($rowsOfPermission) > 0)
		$this->currentUserRecord = $userRecords[0];
		return $userRecords[0];
	}//public function getUserRecordWithId($id)
	public function getUserRecordWithEmail($email)
	{
		$database = $this->getDB();
		$table_prefix = $database->getTablePrefix();
		$selectUserSQL = "select * from `{$table_prefix}user` where email = ?";
		$userRecords  = $database->selectPS($selectUserSQL,"s",$email);
		if(count($userRecords) == 0)
        {
            throw new \Exception("USER_EMAIL_NOT_REGISTERED");
			exit;
        }//if(count($rowsOfPermission) > 0)
		$this->currentUserRecord = $userRecords[0];
		return $userRecords[0];
	}//public function getUserRecordWithEmail($email)
	public function getUserDetailsWithSessionVar()
	{		  
            $requestVarHelper =  RequestVarHelper::getInstance();
			$loggedInUserIdSessionValue = $requestVarHelper->getSessionVar('user_id');
			$userId = $loggedInUserIdSessionValue;//$_SESSION['user_id'];
            /* $userRecords  = $this->getUserDetailsWithId($userId);
            $userDetails = $userRecords[0]; */
			$userDetails = $this->getUserRecordWithId($userId);
			//$this->updateLastVisitedTime($userDetails['id']);
			/*get User Permissions*/
			/*get User Permissions ends here*/
			return $userDetails;
	}//public function getUserDetailsWithSessionVar()
	public function getAutoRegistrationEnabled()
	{
		$siteConfig = $this->getSiteconfig();
		$autoRegistrationEnabled = $siteConfig->getAutoRegistrationEnabled();
		return $autoRegistrationEnabled;//true;
	}//public function getAutoRegistrationEnabled()
	public function deleteUser($user_id)
	{
		$database = $this->getDB();
		$table_prefix = $database->getTablePrefix();
		
		$deleteSessionSQL = "delete from `{$table_prefix}php_sessions` where `user_id`=?";
		$deleteUserResult = $database->executePS($deleteSessionSQL,
													"i",
													$user_id
													);
													
		$deleteUserSQL = "delete from `{$table_prefix}user` where `id`=?";
		$deleteUserResult = $database->executePS($deleteUserSQL,
													"i",
													$user_id
													);
		//You can use ROW_COUNT() function to check the number of deleted rows.	
		$affectedRows = $database->affectedRows();
		return $affectedRows;
	}//public function deleteUser($user_id) 
	public function addNewGoogleUser($data) 
	{
		if(!$this->getAutoRegistrationEnabled())
		{
			throw new \Exception("AUTO_REGISRATION_DISABLED");
			exit;
		}//if(!$this->getAutoRegistrationEnabled())
		$database = $this->getDB();
		$table_prefix = $database->getTablePrefix();
		$insertUserSQL = "insert into `{$table_prefix}user` 
                                        (`first_name`,`last_name`,`email`,`picture`,`date_joined`,`refresh_token`)
                                        values (?,?,?,?,NOW(),?)";//`gender`, gender is to be retrieved from people api
                    /* $replacedSQL =  vsprintf(preg_replace("/\?/","'%s'",$insertUserSQL),
                                                array(
                                                    $data['given_name'],
                                                    $data['family_name'],
                                                    $data['email'],
                                                    $data['gender'],
                                                    $data['picture'],
                                                )                    
                                             ); */
                   //(`given_name`,`family_name`,`email`,`gender`,`picture`,`date_joined`,`refresh_token`)
                    $insertResult = $database->executePS($insertUserSQL,"sssss",
                                                $data['first_name'],
                                                $data['last_name'],
                                                $data['email'],
                                                /* $data['gender'], */
                                                $data['picture'],
                                                $data['refresh_token']
                                                );

                   /*  $replacedSQL = $this->database->getReplacedSQL($insertUserSQL,
                                                                    array(
                                                                        $data['given_name'],
                                                                        $data['family_name'],
                                                                        $data['email'],
                                                                        $data['gender'],
                                                                        $data['picture'],
                                                                        ) ); */
                    //die ($replacedSQL."<hr />"."<pre>".print_r($insertResult,true)."</pre>");//."<pre>".print_r($data,true)."</pre>");
					$userId = $database->lastInsertId();
					$this->errorJSON = str_repeat("*",50).
										$replacedSQL."\\n\\n".
										addslashes(print_r($insertResult,true))."\\n\\n".
										"userId is " . $userId."\\n\\n".
										str_repeat("*",50);//."<pre>".print_r($data,true)."</pre>"
                    
                    
                    
                    //$_SESSION['user_id'] = $userId;
                    //$_SESSION['user_email'] = $data['email'];
					
					//RequestVarHelper::getInstance()->setSessionVar('user_id', $userId);
					//RequestVarHelper::getInstance()->setSessionVar('user_email', $data['email']);
					
                    $selectUserSQL = "select * from `{$table_prefix}user` where id = ?";
                    $userRecords  = $database->selectPS($selectUserSQL,"i",$userId);
					return $userRecords[0];
	}//public function addNewGoogleUser($userDetails)
	public function updateLastVisitedTime($userId)
    {
		$database = $this->getDB();
		$table_prefix = $database->getTablePrefix();
        $stmt = "update `{$table_prefix}user`  set `last_visited`=NOW() where id = ?";
        $userRecords  = $database->executePS($stmt,"i",$userId);
    }//public function updateLastVisitedTime()
	public function logout()
    {
       //unset($_SESSION[OSOLMVC_SESSION_VAR_PREPEND . 'user_id']);
	   RequestVarHelper::getInstance()->setSessionVar('user_id',null);
	   RequestVarHelper::getInstance()->setSessionVar('userRecord',null);
	   RequestVarHelper::getInstance()->setSessionVar('availableMinusDeclinedPermissions',null);//set in ACLHelper::getAvailableMinusDeclinedPermisions
	   
	   
       $this->updateLastVisitedTime($userDetails['id']);
    }//public function logout()
	public function userGroupAndPermissions($userId)
	{
		/**
		ACL Tables
		1. osol_mvc_acl_user_permissions --> stores permission names. eg: readOnly, Write etc
		2. osol_mvc_acl_user_groups --> stores group names . Eg: author, editor etc
		3. osol_mvc_acl_group_permissions --> stores permission ids(in osol_mvc_acl_user_permissions) available for a group . 
		4. osol_mvc_acl_declined_permissions --> if any particular permission(in osol_mvc_acl_user_permissions) is declined for a user
		5. osol_mvc_acl_user_2_group -> maps user to a group		
		*/
	}//public function userGroupDetails($userId)
	public function setUserSessionsOnLogin($userRecord)
	{
		RequestVarHelper::getInstance()->setSessionVar('user_id', $userRecord['id']);
		RequestVarHelper::getInstance()->setSessionVar('userRecord', $userRecord);
	}//public function setUserSessionsOnLogin($userRecord)

}//class AccountHelper extends \OsolMVC\Core\CoreParent{
?>