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
    $msg[203] = 'Registration successful. Please check your email to activate your account.';
    $msg[204] = 'Password changed successfully';
    $msg[205] = 'Please check your email account for new password.';
    $msg[206] = 'Feeling added successfully';
    $msg[207] = 'Feeling yet not added';
    $msg[208] = 'mail send successfully';
    $msg[209] = 'Feeling already added';
    $msg[210] = 'KeySpiration added successfully';
    $msg[211] = 'KeyIn Match';
    $msg[212] = 'Keyspiration List';
    $msg[213] = 'Home Page';
    $msg[214] = 'KeyIn List';
    $msg[215] = 'Keyspiration deleted';
    $msg[216] = 'Logout Successfully';
    $msg[217] = 'User successfully unfollow to this pr';
    $msg[218] = 'Product list';
    $msg[219] = 'PR Profile image updated successfully';
    $msg[220] = 'PR Detail';
    $msg[221] = 'Third Level Category list';
    $msg[222] = 'Fourth Level Category list';
    $msg[223] = 'Fifth Level Category list';
    $msg[224] = 'Assigned Brand list';
    $msg[225] = 'Assigned Product list';
    $msg[226] = 'Wall successfully posted';
    $msg[227] = 'PR Wall liked successfully';
    $msg[228] = 'PR Wall unliked successfully';
    $msg[229] = 'PR Wall liked user list';
    $msg[230] = 'PR Wall comment user list';
    $msg[231] = 'comment successfully post';
    $msg[232] = 'Buz Wall List';
    $msg[233] = 'Product liked successfully';
    $msg[234] = 'Product unliked successfully';
    $msg[235] = 'Product info';
    $msg[236] = 'Product Detail';
    $msg[237] = 'Discount Code valid';
    $msg[238] = 'Bid Added successfully';
    $msg[239] = 'Product Review list';
    $msg[240] = 'Product Review added successfully';
    $msg[241] = 'Search Product list';
    $msg[242] = 'Search User list';
    $msg[243] = 'Product video url';
    $msg[244] = 'album created successfully';
    $msg[245] = 'album list';
    $msg[246] = 'album detail list';
    $msg[247] = 'album image delete successfully';
    $msg[248] = 'album delete successfully';
    $msg[249] = 'Product Biding list';
    $msg[250] = 'Product endrosed request added successfully';
    $msg[251] = 'PR video successfully saved';
    $msg[252] = 'PR video list';
    $msg[253] = 'Album img detail';
    $msg[254] = 'Album img liked successfully';
    $msg[255] = 'Album img unliked successfully';
    $msg[256] = 'Album img comment added successfully';
    $msg[257] = 'Album updated successfully';
    $msg[258] = 'PR video like successfully';
    $msg[259] = 'PR video unlike successfully';
    $msg[260] = 'PR video like user list';
    $msg[261] = 'Album img liked user list';
    $msg[262] = 'User dashboard';
    $msg[263] = 'PR Likes';
    $msg[264] = 'PR Likes updated successfully';
    $msg[265] = 'PR Theme Code update successfully';
    $msg[266] = 'Dashboard product list';
    $msg[267] = 'Dashboard sold product list';
    $msg[268] = 'Followers list';
    $msg[269] = 'Following list';
    $msg[270] = 'User successfully follow';
    $msg[271] = 'User successfully unfollow';
    $msg[272] = 'User successfully verified';
    $msg[273] = 'User detail';
    $msg[274] = 'User detail update successfully';
    $msg[275] = 'Please check your email account to verify your account.';
    $msg[276] = 'Team created successfully';
    $msg[277] = 'Add Member successfully';
    $msg[278] = 'Normal Product Add successfully';
    $msg[279] = 'User successfully registered with us';
    $msg[280] = 'Product added successfully';
    $msg[281] = 'Product added successfully and Product image not saved';
    $msg[282] = 'Search Team list';
    $msg[283] = 'Search PR list';
    $msg[284] = 'Search User list';
    $msg[285] = 'Video deleted successfully';
    $msg[286] = 'Video comment added successfully';
    $msg[287] = 'Child Product successfully added';
    $msg[288] = 'Child Product image saved successfully';
    $msg[289] = 'Child Product image deleted successfully';
    $msg[290] = 'Category option list';
    $msg[291] = 'PR video detail';
    $msg[292] = 'Brand List';
    $msg[293] = 'Team detail';
    $msg[294] = 'Team Dashboard about information';
    $msg[295] = 'Team follower list';
    $msg[296] = 'Team product list';
    $msg[297] = 'Team Wall list';
    $msg[298] = 'Comment deleted successfully';
    $msg[299] = 'Wall updated successfully';
    $msg[300] = 'Wall comment updated successfully';
    $msg[301] = 'Endrosed product list';
    $msg[302] = 'Wall Detail';
    $msg[303] = 'Option value list';
    $msg[304] = 'Pr Product dashboard detail';
    $msg[305] = 'Pr Leave Endrosed successfully';
    $msg[306] = 'Product Variant';
    $msg[307] = 'Offer accepted successfully';
    $msg[308] = 'Offer declined successfully';
    $msg[309] = 'follower list';
    


    $msg[400] = 'Invalid login credential';
    $msg[401] = 'This user not registered with us';
    $msg[402] = 'user is inactive';
    $msg[403] = 'user deleted by admin';
    $msg[404] = 'user not verified';
    $msg[405] = 'Error in add feeling';
    $msg[406] = 'invalid parameter';
    $msg[407] = 'Todays feeling already added';
    $msg[408] = 'User registration failed';
    $msg[409] = 'valid image only png, jpg and jpeg';
    $msg[410] = 'user already register with us';
    $msg[411] = 'Invalid login credential';
    $msg[412] = 'Old password is incorrect';
    $msg[413] = 'user token required';
    $msg[414] = 'invalid user token';
    $msg[415] = 'password and confirm password should be same';
    $msg[416] = 'Error in save new password';
    $msg[417] = 'Error in save KeySpiration';
    $msg[418] = 'Keyin answer not found';
    $msg[419] = 'This account isn\'t verify';
    $msg[420] = 'Old password can\'nt as new password';
    $msg[421] = 'This email already registered as product repersentative';
    $msg[422] = 'Invalid code';
    $msg[423] = 'Yet not added KeySpiration';
    $msg[424] = 'Yet not added KeyIn';
    $msg[425] = 'Result not Found';
    $msg[426] = 'this record not exists';
    $msg[427] = 'PR Profile image update request failed';
    $msg[428] = 'Error in PR detail';
    $msg[429] = 'Error in wall post';
    $msg[430] = 'Some error in wall like request';
    $msg[431] = 'Some error in wall unlike request';
    $msg[432] = 'PR Wall already liked by this user';
    $msg[433] = 'Products already liked';
    $msg[434] = 'Products like request failed';
    $msg[435] = 'Products unlike request failed';
    $msg[436] = 'Discount Code invalid';
    $msg[437] = 'Invalid product id';
    $msg[438] = 'Bid amount greater than to min bid amount';
    $msg[439] = 'Product review request failed';
    $msg[440] = 'User already post bid for this item';
    $msg[441] = 'Bid request failed';
    $msg[442] = 'Error in Product Request Review';
    $msg[443] = 'Error in album create';
    $msg[444] = 'delete album image request failed';
    $msg[445] = 'image not exists';
    $msg[446] = 'album id not exist';
    $msg[447] = 'pr already given offer for product endrosed';
    $msg[448] = 'This product not exists';
    $msg[449] = 'Commission or Discount less than product price';
    $msg[450] = 'Product endrosed request failed';
    $msg[451] = 'PR video saved request failed';
    $msg[452] = 'Album Img id not exists';
    $msg[453] = 'Album Img like request failed';
    $msg[454] = 'Album Img Already liked';
    $msg[455] = 'Album Img unlike request failed';
    $msg[456] = 'Album Img comment add request failed';
    $msg[457] = 'Album update request failed';
    $msg[458] = 'PR video already liked';
    $msg[459] = 'PR video like request failed';
    $msg[460] = 'PR video unlike request failed';
    $msg[461] = 'Invalid user';
    $msg[462] = 'PR like update request failed';
    $msg[463] = 'PR Theme color updated successfully';
    $msg[464] = 'Invalid tab value';
    $msg[465] = 'Follow request failed';
    $msg[466] = 'Unfollow request failed';
    $msg[467] = 'This user already followe';
    $msg[468] = 'User verify request failed';
    $msg[469] = 'User detail update request failed';
    $msg[470] = 'Password reset request failed';
    $msg[471] = 'team create request failed';
    $msg[472] = 'this user already created team';
    $msg[473] = 'this user didn\'t create team';
    $msg[474] = 'add member request api done';
    $msg[475] = 'this facebook user already registered with us';
    $msg[476] = 'this google user already registered with us';
    $msg[477] = 'Add product request failed';
    $msg[478] = '';
    $msg[479] = 'Invalid video id';
    $msg[480] = 'Video delete request failed';
    $msg[481] = 'Video comment add request failed';
    $msg[482] = 'Add child product request failed';
    $msg[483] = 'Invalid video id';
    $msg[484] = 'Invalid team id';
    $msg[485] = 'Comment delete request failed';
    $msg[486] = 'Invalid wall id';
    $msg[487] = 'Wall update request failed';
    $msg[488] = 'Wall comment update request failed';
    $msg[489] = 'Invalid wall id';
    $msg[490] = 'Invalid product id';
    $msg[491] = 'Pr Leave Endrosed Request failed';
    $msg[492] = 'Pr Leave Endrosed not exist';
    $msg[493] = 'Offer accept request failed';
    $msg[494] = 'Offer accept request invalid';
    $msg[495] = 'Offer decline request failed';
    $msg[496] = 'Offer decline request invalid';
    $msg[497] = 'Invalid request';


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