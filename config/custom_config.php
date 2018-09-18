<?php

use Cake\Core\Configure;



//Global Setting Edit
Configure::write('globalsetting.label', array(
    '1'=> __('Global Setting title'),//Title 
    '2'=> __('Email'),//Email 
    '3'=> __('Facebook URL'),//Fb URL
    '4'=> __('Twitter URL'),//tw URL
    '5'=> __('Google Url'), //Google page Link
    '6'=> __('Phone Number'), //Google page Link
    '7'=> __('Address'), // Address
    '8'=> __('Task Default Cancel Hour'), //task_default_cancel_hour: If task for product representative is not agreed by the other party within "specifed" hour period then the task is automatically cancelled and the funds returned to the vendors E-bank
    '9'=> __('Select Default Country'), // Default Country
));
//Global Setting field Name
Configure::write('globalsetting.field_name', array(
    '1'=> 'gs_title',//Title 
    '2'=> 'gs_email', //Email
    '3'=> 'gs_fb_url', //Facebook URL
    '4'=> 'gs_tw_url', //Twitter URL
    '5'=> 'gs_google_url', //Google URL
    '6'=> 'gs_phone_no', //Phone Number
    '7'=> 'gs_address', // Address
    '8'=> 'gs_task_default_cancel_hour', //Default Cancel Hour
    '9'=> 'gs_default_country_id', // Default Country - Admin can select one default country, so when users will open site SWARE.com than selected country or related content will be shown

));
//Global Setting Type
Configure::write('globalsetting.input_type', array(
    '1'=> 'textbox',//Title 
    '2'=> 'email',//Email 
    '3'=> 'textbox', //Facebook URL
    '4'=> 'textbox', //Twitter URL
    '5'=> 'textbox', //Google URL
    '6'=> 'textbox', //Phone number
    '7'=> 'textbox', //Address
    '8'=> 'textbox', //DEfault Cancel 
    '9'=> 'select', //Address
));



           
//Face book
Configure::write('fbAppId','1761516007411179');//cgtechnosoft2015@gmail.com
Configure::write('fbSecret','0400fd9fb8e34f1c3d6bd87f65bb6cc8');



//google
Configure::write('gpAppId','398340747034-4h3k3o416di3l99fmfjtlejqiiiitokc.apps.googleusercontent.com');
Configure::write('gpSecret','g0os1mdze32hR2WhkN2hu1-u'); //b4u606@yahoo.com


//google captcha
Configure::write('gc-sitekey','6LdBxyQUAAAAAK6pzwp_P0tgrrDXfm8mxrMBWKDB'); // local
// online =>6LdBxyQUAAAAAC4iwSLz7ItBcG9LtoD3lJRSZKgm

//fb-login
Configure::write('fb_appid','260717400964945');

//gp-login
Configure::write('gp_client_id','85215865572-msbk5jboroiu8ig85jg17aocvkltb0e7.apps.googleusercontent.com');
Configure::write('gp_app_key','AIzaSyBLmNJ8xsaTPhLckh5WBNkl8X97PyoGdq4');



