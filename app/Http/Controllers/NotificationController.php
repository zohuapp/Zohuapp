<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\VendorUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Google\Client as Google_Client;
class NotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id = '')
    {

        return view("notification.index")->with('id', $id);
    }

    public function send($id = '')
    {
        return view('notification.send')->with('id', $id);
    }

    public function broadcastnotification(Request $request)
    {
        if (Storage::disk('local')->has('firebase/credentials.json')) {
            $client = new Google_Client();
            $client->setAuthConfig(storage_path('app/firebase/credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $client_token = $client->getAccessToken();
            $access_token = $client_token['access_token'];
            $fcm_token = $request->fcm;
            if (!empty($access_token) && !empty($fcm_token)) {
                $projectId = env('FIREBASE_PROJECT_ID');
                $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
                foreach ($fcm_token as $fcm) {
                    $data = [
                        'message' => [
                            'notification' => [
                                'title' => $request->subject,
                                'body' => $request->message,
                            ],
                            'data' => [
                                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                                'id' => '1',
                                'status' => 'done',
                            ],
                            'token' => $fcm,
                        ],
                    ];
                    $headers = array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $access_token
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    $result = curl_exec($ch);
                    if ($result === FALSE) {
                        die('FCM Send Error: ' . curl_error($ch));
                    }
                    curl_close($ch);
                    $result = json_decode($result);
                    $response = array();
                    $response['success'] = true;
                    $response['message'] = 'Notification successfully sent.';
                    $response['result'] = $result;
                }
            } else {
                $response = array();
                $response['success'] = false;
                $response['message'] = 'Missing role or sender id or token to send notification.';
            }
        }
    }
}


