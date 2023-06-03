## ACLHelper::hasPermission


**If user has atleast one permission among required permission, he is allowed**

1. check if user is active from `osol_mvc_user`, else return false
2. get group_id s of user from `osol_mvc_acl_user_2_group` --> $allGroupIdsRowsOfUser  --> $allGroupIdsOfUser
3. if(level 1 admin ie group_id=4) return true
4. get parent_id of group from `osol_mvc_acl_user_groups`
5. get permissions of groups `osol_mvc_acl_group_permissions` & `osol_mvc_acl_user_permissions`, $allAvailableGroupPermissions
6. get declined permisions for group_ids from `osol_mvc_acl_group_declined_permissions`, $allDeclinedGroupPermissions
7. get declined permissions for user_id from `osol_mvc_acl_user_declined_permissions`, $allDeclinedUserPermissions 
8. get all available permissions for group into an array , $availablePermissions = array_diff($allAvailableGroupPermissions, $allDeclinedGroupPermissions,  $allDeclinedUserPermissions )
9. return (count(array_diff($availablePermissions,$requiredPermissions))>0)

```
@startuml
start

if (user active?) then (no)
	#pink:return false;
	stop
endif

:2. get group_id s of user from 
`osol_mvc_acl_user_2_group` --> 
$allGroupIdsRowsOfUser  --> 
$allGroupIdsOfUser;

if(level 1 admin ie group_id=4) then (yes)
 #palegreen:return true;
 stop
endif

:4. get parent_id of group 
from `osol_mvc_acl_user_groups`;

:get permissions of groups from 
`osol_mvc_acl_group_permissions` & `osol_mvc_acl_user_permissions`,
 $allAvailableGroupPermissions;
 
:6. get declined permisions for group_ids from 
`osol_mvc_acl_group_declined_permissions`, 
$allDeclinedGroupPermissions;

:7. get declined permissions for user_id from 
`osol_mvc_acl_user_declined_permissions`, 
$allDeclinedUserPermissions;

:8. get all available permissions for group 
into an array , **$availableMinusDeclinedPermissions** = 
array_diff($allAvailableGroupPermissions, 
$allDeclinedGroupPermissions,  
$allDeclinedUserPermissions );

:9. return (count(array_intersect($requiredPermissions,$availableMinusDeclinedPermissions))>0);

stop
@enduml
```
### Diagram with [PUML SERVER](http://www.plantuml.com/plantuml/uml/SyfFKj2rKt3CoKnELR1Io4ZDoSa70000)

![Alt text](file://hasPermissionDiagram.png "Text on mouseover")