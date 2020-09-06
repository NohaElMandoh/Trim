<?php

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Http;

if (!function_exists('upload_image')) {
    function upload_image(Request $request, $name, $width, $height)
    {
        $image = $request->$name;
        $filename  = mt_rand(1000, 10000) . time() . uniqid() . '.' . $image->getClientOriginalExtension();
        $path = storage_path('app/public/' . $filename);
        Image::make($image->getRealPath())->resize($width, $height)->save($path);
        return $filename;
    }
}

if (!function_exists('upload_file')) {
    function upload_file(Request $request, $name)
    {
        $image = $request->$name;
        $filename  = mt_rand(1000, 10000) . time() . uniqid() . '.' . $image->getClientOriginalExtension();
        $request->file($name)->storeAs(
            'public',
            $filename
        );
        return $filename;
    }
}

if (!function_exists('validate_trans')) {
    function validate_trans(Request $request, $params)
    {
        $validate = [];
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            foreach ($params as $param) {
                $validate["$localeCode.$param[0]"]  = "$param[1]";
            }
        }
        $request->validate($validate);
    }
}


if (!function_exists('response_api')) {
    function response_api($data, $success = true, $message = '')
    {
        return response()->json(array_merge(['success' => $success, 'message' => $message], $data));
    }
}

if (!function_exists('send_notif')) {
    function send_notif($title = "", $sentData = null, $body = "", $tokens)
    {
        $chunks = $tokens->chunk(100);
        $chunks->toArray();
        foreach ($chunks as $chunk) {
            $response = Http::post('https://exp.host/--/api/v2/push/send', [
                'to' => $chunk,
                'sound' => 'default',
                'title' => $title,
                'body' => $body ?? '',
                'data' => $sentData,
                "priority" => "high",
                "channelId" => "notification",
                "android" => [
                    "channelId" => "notification"
                ]
            ]);
        }
    }
}
