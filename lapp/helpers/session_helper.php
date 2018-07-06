<?php

    function retrieveSession()
    {
        if ($_SESSION['username']) {
            $data['username']=$_SESSION['username'];
            
            $data['AddItem'] = '0';
            $data['EditItem'] = '0';
            $data['DeleteItem'] = '0';
            $data['Upload_Video'] = '0';
            $data['CreateUser'] = '0';
            $data['SetParameters'] = '0';
            $data['ViewLogReport'] = '0';
            $data['ClearLogFiles'] = '0';
            $data['ViewReports'] = '0';
            $data['CreatePublisher'] = '0';
            $data['CreateComedian'] = '0';
            $data['CreateCategory'] = '0';
            $data['ApproveVideo'] = '0';
            $data['ApproveComment'] = '0';
            $data['AddBanners'] = '0';
            $data['ModifyStaticPage'] = '0';
            $data['AddArticlesToBlog'] = '0';
            $data['CheckDailyReports'] = '0';
            $data['AddMobileOperator'] = '0';
            $data['CreateEvents'] = '0';
                        
            if ($_SESSION['username']) {
                $data['username'] = $_SESSION['username'];
            }
            if ($_SESSION['firstname']) {
                $data['firstname'] = $_SESSION['firstname'];
            }
            if ($_SESSION['lastname']) {
                $data['lastname'] = $_SESSION['lastname'];
            }
            if ($_SESSION['UserFullName']) {
                $data['UserFullName'] = $_SESSION['UserFullName'];
            }
            if ($_SESSION['pwd']) {
                $data['pwd'] = $_SESSION['pwd'];
            }
            if ($_SESSION['phone']) {
                $data['phone'] = $_SESSION['phone'];
            }
            if ($_SESSION['email']) {
                $data['email'] = $_SESSION['email'];
            }
            if ($_SESSION['datecreated']) {
                $data['datecreated'] = $_SESSION['datecreated'];
            }
            if ($_SESSION['accountstatus']) {
                $data['accountstatus'] = $_SESSION['accountstatus'];
            }
            if ($_SESSION['role']) {
                $data['role'] = $_SESSION['role'];
            }
            
            #################################
            #Permissions
            if ($_SESSION['AddItem']==1) {
                $data['AddItem'] = $_SESSION['AddItem'];
            }
            if ($_SESSION['EditItem']==1) {
                $data['EditItem'] = $_SESSION['EditItem'];
            }
            if ($_SESSION['DeleteItem']== 1) {
                $data['DeleteItem'] = $_SESSION['DeleteItem'];
            }
            if ($_SESSION['Upload_Video']== 1) {
                $data['Upload_Video'] = $_SESSION['Upload_Video'];
            }
            if ($_SESSION['CreateUser']==1) {
                $data['CreateUser'] = $_SESSION['CreateUser'];
            }
            if ($_SESSION['SetParameters']== 1) {
                $data['SetParameters'] = $_SESSION['SetParameters'];
            }
            if ($_SESSION['ViewLogReport']== 1) {
                $data['ViewLogReport'] = $_SESSION['ViewLogReport'];
            }
            if ($_SESSION['ClearLogFiles']==1) {
                $data['ClearLogFiles'] = $_SESSION['ClearLogFiles'];
            }
            if ($_SESSION['ViewReports']==1) {
                $data['ViewReports'] = $_SESSION['ViewReports'];
            }
            if ($_SESSION['CreatePublisher']==1) {
                $data['CreatePublisher'] = $_SESSION['CreatePublisher'];
            }
            if ($_SESSION['CreateComedian']== 1) {
                $data['CreateComedian'] = $_SESSION['CreateComedian'];
            }
            if ($_SESSION['CreateCategory']== 1) {
                $data['CreateCategory'] = $_SESSION['CreateCategory'];
            }
            if ($_SESSION['ApproveVideo']==1) {
                $data['ApproveVideo'] = $_SESSION['ApproveVideo'];
            }
            if ($_SESSION['ApproveComment']==1) {
                $data['ApproveComment'] = $_SESSION['ApproveComment'];
            }
            if ($_SESSION['AddBanners']== 1) {
                $data['AddBanners'] = $_SESSION['AddBanners'];
            }
            if ($_SESSION['ModifyStaticPage']== 1) {
                $data['ModifyStaticPage'] = $_SESSION['ModifyStaticPage'];
            }
            if ($_SESSION['AddArticlesToBlog']== 1) {
                $data['AddArticlesToBlog'] = $_SESSION['AddArticlesToBlog'];
            }
            if ($_SESSION['CheckDailyReports']== 1) {
                $data['CheckDailyReports'] = $_SESSION['CheckDailyReports'];
            }
            if ($_SESSION['AddMobileOperator']== 1) {
                $data['AddMobileOperator'] = $_SESSION['AddMobileOperator'];
            }
            if ($_SESSION['CreateEvents']== 1) {
                $data['CreateEvents'] = $_SESSION['CreateEvents'];
            }
            ###############################
            
            if ($_SESSION['companyname']) {
                $data['companyname'] = $_SESSION['companyname'];
            }
            if ($_SESSION['companyemail']) {
                $data['companyemail'] = $_SESSION['companyemail'];
            }
            if ($_SESSION['companyphone']) {
                $data['companyphone'] = $_SESSION['companyphone'];
            }
            if ($_SESSION['website']) {
                $data['website'] = $_SESSION['website'];
            }
            if ($_SESSION['companylogo']) {
                $data['companylogo'] = $_SESSION['companylogo'];
            }
            if ($_SESSION['RefreshDuration']) {
                $data['RefreshDuration'] = $_SESSION['RefreshDuration'];
            }
            if ($_SESSION['default_network']) {
                $data['default_network'] = $_SESSION['default_network'];
            }
            if ($_SESSION['no_of_videos_per_day']) {
                $data['no_of_videos_per_day'] = $_SESSION['no_of_videos_per_day'];
            }
            if ($_SESSION['google_shortener_api']) {
                $data['google_shortener_api'] = $_SESSION['google_shortener_api'];
            }
            if ($_SESSION['jw_api_key']) {
                $data['jw_api_key'] = $_SESSION['jw_api_key'];
            }
            if ($_SESSION['jw_api_secret']) {
                $data['jw_api_secret'] = $_SESSION['jw_api_secret'];
            }
            if ($_SESSION['jw_player_id']) {
                $data['jw_player_id'] = $_SESSION['jw_player_id'];
            }
            if ($_SESSION['emergency_emails']) {
                $data['emergency_emails'] = $_SESSION['emergency_emails'];
            }
            if ($_SESSION['emergency_no']) {
                $data['emergency_no'] = $_SESSION['emergency_no'];
            }
            if ($_SESSION['sms_url']) {
                $data['sms_url'] = $_SESSION['sms_url'];
            }
            if ($_SESSION['sms_username']) {
                $data['sms_username'] = $_SESSION['sms_username'];
            }
            if ($_SESSION['sms_password']) {
                $data['sms_password'] = $_SESSION['sms_password'];
            }
            if ($_SESSION['input_bucket']) {
                $data['input_bucket'] = $_SESSION['input_bucket'];
            }
            if ($_SESSION['output_bucket']) {
                $data['output_bucket'] = $_SESSION['output_bucket'];
            }
            if ($_SESSION['thumbs_bucket']) {
                $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
            }
            if ($_SESSION['aws_key']) {
                $data['aws_key'] = $_SESSION['aws_key'];
            }
            if ($_SESSION['aws_secret']) {
                $data['aws_secret'] = $_SESSION['aws_secret'];
            }
            
            if ($_SESSION['distribution_Id']) {
                $data['distribution_Id'] = $_SESSION['distribution_Id'];
            }
            if ($_SESSION['domain_name']) {
                $data['domain_name'] = $_SESSION['domain_name'];
            }
            if ($_SESSION['origin']) {
                $data['origin'] = $_SESSION['origin'];
            }
        }
        return $data;
    }
    function checkIfAuthenticated()
    {
        if(!array_key_exists("username",$_SESSION)){
            redirect('Adminlogin');
        }
    }