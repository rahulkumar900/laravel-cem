<?php

namespace App\Http\Controllers;

use App\Models\Compatibility;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // select random profile
    public function getRandomProfile($user_id){
        $user_profiles = array();

        $user_profile_search = Compatibility::where('user_data_id', $user_id)->first(['newlyjoined_userId', 'popular_userId']);
        if (!empty($user_profile_search->newlyjoined_userId) && !empty($user_profile_search->popular_userId)) {
            array_push($user_profiles,$user_profile_search->newlyjoined_userId);
            array_push($user_profiles, $user_profile_search->popular_userId);
        }
        return $user_profiles[array_rand($user_profiles)];
    }

    // web push notification
    public function appPushNotification($message_title, $message, $reg_id)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = env('FIREBASE_API_KEY');
        $fields = array(
            'to' => $reg_id,
            'notification' => array(
                "title" => $message_title,
                "body" => $message,
                "click_action" => "https://hansmatrimony.com/chat?push_notification="
            )
        );

        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            // die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    // app push notifications
    public function webPushNotification($message_title, $message, $reg_id)
    {
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'AAAAGFjQl50:APA91bE_Qjw_SupCUKGY-_lulBUr3ZqBpl-rEuMfAFRa4_ArRLm820c6I0kY3H1ZfjEohoMjn_SU587M9nnxsfKwk2KgJ_8KnX93fTqIhV9-u0pNrywL1-tnMD4nlYLj6uQBMmC_R5sr';

        $fields = array(
            'to' => $reg_id,
            'notification' => array(
                "title" => $message_title,
                "body" => $message,
                "click_action" => 'https://hansmatrimony.com'
            )
        );

        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            // die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    // push notification
    public function shortListPushNotification($user_photo, $fcm_id, $message, $message_title)
    {
        $message_title = $message_title;
        $message = $message;
        $reg_id = $fcm_id;
        $url = 'https://fcm.googleapis.com/fcm/send';
        //  $api_key = env('FIREBASE_API_KEY');

        $api_key = "AAAAGFjQl50:APA91bE_Qjw_SupCUKGY-_lulBUr3ZqBpl-rEuMfAFRa4_ArRLm820c6I0kY3H1ZfjEohoMjn_SU587M9nnxsfKwk2KgJ_8KnX93fTqIhV9-u0pNrywL1-tnMD4nlYLj6uQBMmC_R5sr";

        if ($user_photo) {
            $fields = array(
                'to' => $fcm_id,
                'notification' => array(
                    "title" => $message_title,
                    "body" => $message,
                    "click_action" => "com.twango.me.LIKED_ME",
                    "image" => $user_photo,
                ),
                'data' => array(
                    "title" => $message_title,
                    "click_action" => "LIKED_ME",
                )
            );
        } else {
            $fields = array(
                'to' => $fcm_id,
                'notification' => array(
                    "title" => $message_title,
                    "body" => $message,
                    "click_action" => "com.twango.me.LIKED_ME",
                )
            );
        }


        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            // die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        //   dd($result);
    }


}
