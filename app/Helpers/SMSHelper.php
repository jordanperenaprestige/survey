<?php

namespace App\Helpers;

class SMSHelper 
{
    public function __construct() {
        
    }

    /* getSchedules
	*  return $schedules
    */
    public function sendSMS($Target, $Message, $SenderID = '') {
        // try {

            $ReturnValue = '';



            $URLTarget = 'https://api.m360.com.ph/v3/api/broadcast';


            $URLBodyParams = array(

                'app_key' => 'hpnqOJChhT926MoH',

                'app_secret' => 'gwuVX95iJPxZGfyKEA75NBDNYbhJYSPQ',

                'msisdn' =>  $Target,

                'content' => $Message,

                'shortcode_mask' => 'WorkplacePH',

            );

            $PostFields = '';

            foreach ($URLBodyParams as $Key => $Value)

                $PostFields .= urlencode($Key) . '=' . urlencode($Value) . '&';

            // $ch = curl_init($URLTarget);

            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            // curl_setopt($ch, CURLOPT_POST, TRUE);

            // curl_setopt($ch, CURLOPT_HEADER, FALSE);sssssssssssss

            // curl_setopt($ch, CURLOPT_POSTFIELDS, $PostFields);
            // $CURLReturn = curl_exec($ch);

            // // check if the curl was successful

            // if (curl_errno($ch) != 0)
              
            //     back()->withErrors("CURL ERROR (" . curl_errno($ch) . ") " . curl_error($ch));

            // curl_close($ch);

            // $ReturnValue = $CURLReturn;

            // $request_headers = array(
            //     "Accept: application/json",
            //     "connectapitoken:" . $this->api_key
            // );
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.m360.com.ph/v3/api/broadcast');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $PostFields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
           // $movies = json_decode(curl_exec($ch));
            $movies = curl_exec($ch);
            
            if (curl_errno($ch)) {
                print "Error: " . curl_error($ch);
                exit();
            }
            curl_close($ch);
            return $movies;
            
        // } catch (\Exception $e) {

        //     $ReturnValue = $e->getMessage();
        // }



        return $ReturnValue;
    }
}