<?php

return array(

        "group"=>array(
            "createGroup"=>array(
                "success"=>"Group created successfully",
                "failure"=>"Failed To Create Group",
            ),"removeGroup"=>array(
                "success"=>"Group removed successfully",
                "failure"=>"Failed To remove Group",
            ),"MyGroups"=>array(
                "success"=>"Groups fetched successfully",
                "failure"=>"Failed to fetch groups",
            ),"enrolledGroups"=>array(
                "success"=>"Enrolled groups fetched successfully",
                "failure"=>"Failed to fetch enrolled groups",
            ),"filesInGroup"=>array(
                "success"=>"Files in group fetched successfully",
                "failure"=>"Failed to fetch files in group",
            ),
            "addFilesToGroup"=>array(
                "success"=>"Files Added To Group",
                "failure"=>"Failed To Add Files To Group",
            ),
            "addUsersToGroup"=>array(
                "success"=>"Users Added To Group",
                "failure"=>"Failed To Add Users To Group",
            ),
            "removeFilesFromGroup"=>array(
                "success"=>"Files Removed From Group",
                "failure"=>"Failed To Remove Files From Group",
            ),
            "removeUsersFromGroup"=>array(
                "success"=>"Users Removed From Group",
                "failure"=>"Failed To Remove Users From Group",
            ),
        ),
        "user"=>array(
            "logIn"=>array(
                "success"=>"Logged in successfully",
                "failure"=>"Incorrect username or password",
            ),"allUsers"=>array(
                "success"=>"Fetched all users successfully",
                "failure"=>"Error fetching all users",
            ),
            "register"=>array(
                "success"=>"Incorrect username or password",
                "failure"=>"Error creating user",
            ),
            "logOut"=>array(
                "success"=>"Logged out user successfully",
                "failure"=>"Error logging out user",
            ),
        ),
        "file"=>array(
            "checkIn"=>array(
                "success"=>"Checked In Successfully",
                "failure"=>"Check In Failed",
            ),
            "checkOut"=>array(
                "success"=>"Checked Out Successfully",
                "failure"=>"Check Out Failed",
            ),
            "getMyFiles"=>array(
                "success"=>"Files Fetched Successfully",
                "failure"=>"No Files Found",
            ),
            "bulkCheckIn"=>array(
                "success"=>"Checked In Successfully",
                "failure"=>"Check In Failed",
            ),
            "uploadFiles"=>array(
                "success"=>"Files Uploaded successfully",
                "failure"=>"Files Upload Failed",
            ),
            "removeFiles"=>array(
                "success"=>"Files Removed successfully",
                "failure"=>"Files Removal Failed",
            ),
            "readFile"=>array(
                "success"=>"File Read successfully",
                "failure"=>"Failed To Read",
            ),
        ),

);
