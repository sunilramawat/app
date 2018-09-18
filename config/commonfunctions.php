<?php

function format_date($date){
    if(!empty($date)){
        $new_format_date = date("d F Y",strtotime($date));
        return $new_format_date;
    }else{
        return false;
    }
}

function format_date_time($date){
    if(!empty($date)){
        $new_format_date = date("m-d-Y H:i",strtotime($date));
        return $new_format_date;
    }else{
        return false;
    }
}

function format_normal_date($date){
    if(!empty($date)){
        $new_format_date = date("m-d-Y",strtotime($date));
        return $new_format_date;
    }else{
        return false;
    }
}

function format_post_date($date){
    if(!empty($date)){
        $new_format_date = date("j  F Y, g:i a",strtotime($date));
        return $new_format_date;
    }else{
        return false;
    }
}

function addstrip($data = '') {
  return addslashes($data);
}
function strip($data = '') {
  return stripslashes($data);
}

function func_crypt($data) {
  $data_len = strlen($data);
  $return_data = ($data_len * $data_len) . base64_encode($data) . '|' . md5($data . rand()) . '-' . ($data_len);
  return $return_data;
}

function func_decrypt($data) {
  $arr_data = split("[|-]", $data);
  $remove_data = $arr_data[2] * $arr_data[2];
   // removable data from main data
  $orig_data = preg_replace('/' . $remove_data . '/', '', $arr_data[0], 1);
  return base64_decode($orig_data);
}

function dateTimeDifference($day_1, $day_2) {
  $diff = strtotime($day_1) - strtotime($day_2);
  $sec = $diff % 60;
  $diff = intval($diff / 60);
  $min = $diff % 60;
  $diff = intval($diff / 60);
  $hours = $diff % 24;
  $days = intval($diff / 24);
  return array('seconds' => $sec, 'minutes' => $min, 'hours' => $hours, 'days' => $days);
}

function getFileExtension($filename = '') {
  $ext = explode(".", $filename);
  return array_pop($ext);
}

function generate_file_name($filename = '') {
  $ext = $this->getFileExtension($filename);
  return time() + rand() . '.' . $ext;
} 



function dateDifference($date1 = NULL) {
  $date2 = date('Y-m-d');
  
  //    $date1 = '2014-01';
  $diff = abs(strtotime($date2) - strtotime($date1));
  
  $ExplodeDOB = explode('-', $date2);
  $ExplodeDOB2 = explode('-', $date1);
  
  if ($ExplodeDOB2[1] == '00' && $ExplodeDOB2[2] == '00') {
        //CHECKING IS CURRENT YEAR
       if ($ExplodeDOB[0] == $ExplodeDOB2[0]) {
            $Diff = __('Today');
            return $Diff;
       }
  }
  
  if ($ExplodeDOB2[1] != '00' && $ExplodeDOB2[2] == '00') {
        //GET MONTH / YEAR DIFFERENCE
       /*$ExplodeDOB[2] = $ExplodeDOB2[2] = '01';//CONVERTING CURR AND RECORD MONTH TO 01*/
       
       $Year = ($ExplodeDOB2[0] * 12);
       $Month = $ExplodeDOB2[1];
       
       $TotSelVal = $Year + $Month;
       
       //CURR CALCULATIONS
       $CurrYr = ($ExplodeDOB[0] * 12);
       $CurrMonth = $ExplodeDOB[1];
       
       $TotCurrVal = $CurrYr + $CurrMonth;
       $OutPut = ($TotCurrVal) - ($TotSelVal);
       
       if ($OutPut > 12) {
            $NewYr = floor($OutPut / 12);
            $NewMnth = $OutPut % 12;
            $YrTxt = __('Year');
            $MnthTxt = __('Month');
            
            if ($NewYr > 1) {
                 $YrTxt = __('Years');
            }
            if ($NewMnth > 1) {
                 $MnthTxt = __('Months');
            }
            $Diff = $NewYr . ' ' . $YrTxt . ' ' . $NewMnth . ' ' . $MnthTxt;
            return $Diff;
       } 
       else {
            if ($OutPut > 1) {
                 $Diff = $OutPut . ' ' . __('Months');
            } 
            else {
                 $Diff = $OutPut . ' ' . __('Month');
            }
            return $Diff;
       }
  }
  
  $years = floor($diff / (365 * 60 * 60 * 24));
  $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
  $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
  $Diff = '';
  if ($years != 0) {
       if ($years > 1) {
            $Diff = $years . ' ' . __('Years') . ' ';
       } 
       else {
            $Diff = $years . ' ' . __('Year') . ' ';
       }
  }
  if ($months != 0) {
       if ($months > 1) {
            $Diff.= $months . ' ' . __('Months') . ' ';
       } 
       else {
            $Diff.= $months . ' ' . __('Month') . ' ';
       }
  }
  if ($days != 0) {
       if ($days > 1) {
            $Diff.= $days . ' ' . __('Days');
       } 
       else {
            $Diff.= $days . ' ' . __('Day');
       }
  }
  
  /* if(empty($years) && empty($months) && empty($days) && empty($Diff)){
  $Diff .= 'Today';
  } */
  
  //prd($Diff);
  return $Diff;
}

 
function show_date($d = null, $m = null, $y = null) {
  if (!empty($y)) {
       return date('d F Y', strtotime($y . '-' . $m . '-' . $d));
  } 
  else {
       return date('d F', strtotime($y . '-' . $m . '-' . $d));
  }
}

// api user token function
function generateToken(){
    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    $token = substr(str_shuffle($str), 0, 10).time().substr(str_shuffle($str), 0, 10);
    return $token;
}

// pr generate discount code
function generateDiscountCode(){
    $codeStr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    $discountCode = substr(str_shuffle($codeStr), 0, 6);
    return $discountCode;
}

// api response message
function responseMsg($msgId){

    $msg = array();
    $msg[200] = 'login successfully';
    $msg[201] = 'country list';
    $msg[202] = 'state list';
    $msg[203] = 'An email has been sent to you with a confirmation link to verify your account and complete the registration process';
    $msg[204] = 'Password changed successfully';
    $msg[205] = 'Please check your email account for new password.';
    $msg[206] = 'Trems and  condition accepeted';


    $msg[207] = 'Group created';
    $msg[208] = 'Post created';
    $msg[209] = 'Post commented';
    $msg[210] = 'Group subscribed';
    $msg[211] = 'Category list';
    $msg[212] = 'Group list';
    $msg[213] = 'Home Page';
    $msg[214] = 'Post List';
    $msg[215] = 'Vote on post';
    $msg[216] = 'Logout Successfully';
    $msg[217] = 'Profile updated successfully';
    $msg[233] = 'Product liked successfully';
    $msg[234] = 'Product unliked successfully';
    $msg[235] = 'Product info';
    $msg[236] = 'Product Detail';
    $msg[262] = 'User dashboard';
    $msg[272] = 'User successfully verified';
    $msg[273] = 'User detail';
    $msg[274] = 'User detail update successfully';
    $msg[275] = 'Please check your email account to verify your account.';
    $msg[279] = 'User successfully registered with us';
    $msg[280] = 'Explore list';
    $msg[281] = 'Post reported';
    $msg[282] = 'Post comment reported';
    $msg[283] = 'Notification list';
    $msg[284] = 'Notification status updated';
    $msg[285] = 'My post list';
    $msg[286] = 'Recent photo list';
    $msg[287] = 'Group detail';
    $msg[288] = 'Page list';
    
    $msg[310] = 'Contact updated successfully';


    $msg[400] = 'Verification code is not valid';
    $msg[401] = 'This user not registered with us';
    $msg[402] = 'Please verify your account. To activate your account please check your email and spam folder';
    $msg[403] = 'user deleted by admin';
    $msg[404] = 'user not verified';
    
    $msg[405] = 'Error in comment post';
    $msg[406] = 'invalid parameter';
    $msg[407] = 'invalid post commented';
    $msg[408] = 'User registration failed';
    $msg[409] = 'valid image only png, jpg and jpeg';
    $msg[410] = 'You already have a youfeed account';
    $msg[411] = 'Invalid login credential';
    $msg[412] = 'Old password is incorrect';
    $msg[413] = 'user token required';
    $msg[414] = 'invalid user token';
    $msg[415] = 'password and confirm password should be same';
    $msg[416] = 'Error in save new password';
    $msg[417] = 'Error in save Group';
    $msg[418] = 'Error in save Post';
    $msg[419] = 'This account isn\'t verify';
    $msg[420] = 'Old password can\'nt as new password';
    $msg[421] = 'This email already registered as product repersentative';
    $msg[422] = 'Invalid code';
    $msg[423] = 'Group already subscribed';
    $msg[424] = 'Error in group subscribe';
    $msg[425] = 'Result not Found';
    $msg[426] = 'this record not exists';
    $msg[427] = 'You have no groups';
    $msg[428] = 'You have no notification';
    $msg[429] = 'You have yet not added any post';
    $msg[430] = 'You have yet not added any photo';
    $msg[445] = 'image not exists';
    $msg[461] = 'Invalid user';
    $msg[464] = 'Invalid tab value';
    $msg[467] = 'This user already followe';
    $msg[468] = 'User verify request failed';
    $msg[469] = 'User detail update request failed';
    $msg[470] = 'Password reset request failed';
    $msg[474] = 'add member request api done';
    $msg[475] = 'this facebook user already registered with us';
    $msg[476] = 'this google user already registered with us';
    $msg[497] = 'Invalid request';
    $msg[498] = 'Sorry! You can\'t add more than seven audio';
    $msg[499] = 'Sorry! You can\'t add more than seven video';
    $msg[500] = 'Sorry! You can\'t add more than seven image';
    $msg[501] = 'Sorry! You can\'t add more than seven word';
    $msg[502] = 'You have already KeyedIn for today, let\'s meet again tomorrow';


    if( isset($msg[$msgId]) ){
        $message = $msg[$msgId];
    }else{
        $message = '';
    }
    return $message;
}

function getPaymentMsg($key) {
    $msg_arr = [
       
    ];
    
    return isset($msg_arr[$key]) ? $msg_arr[$key] : '';
}

?>