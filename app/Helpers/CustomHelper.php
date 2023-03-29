<?php

namespace App\Helpers;

use App\Models\CmsModule;
use App\Models\CmsUser;
use App\Models\MailTemplate;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CustomHelper
{
    /**
     * This function is used to get login user
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function currentUser()
    {
        $user = Auth::guard('cms_user')->user();
        $data = CmsUser::getCmsUserByID(isset($user->id) ? $user->id : 0);
        return $data;
    }

    /**
     * This function is used to send email
     * @param string $to
     * @param string $identifier
     * @param array $params
     * @param array $cc_emails
     * @param array $attachment_path
     */
    public static function sendMail($to,$identifier,$params, $cc_emails=[], $attachment_path=[])
    {
        $template = MailTemplate::where('identifier',$identifier)->first();
        if( isset($template->id) )
        {
            $mail_subject   = $template->subject;
            $mail_body      = $template->body;
            $mail_wildcards = explode(',', $template->wildcard);

            $mail_wildcard_values = [];
            foreach($mail_wildcards as $value) {
                $value = str_replace(['[',']'],'', $value);
                $mail_wildcard_values[] = $params[$value];
            }

            $mail_subject = str_replace($mail_wildcards, $mail_wildcard_values, $mail_subject);
            $mail_body    = str_replace($mail_wildcards, $mail_wildcard_values, $mail_body);

            Mail::send('email.default', ['content' => $mail_body], function ($m) use ($to,$mail_subject,$cc_emails,$attachment_path) {
                $m->from(env('MAIL_FROM_ADDRESS'), config('constants.APP_NAME'));
                if( count($cc_emails) ){
                    $m->cc($cc_emails);
                }
                if( count($attachment_path) ){
                    foreach($attachment_path as $attachment){
                        $m->attach($attachment);
                    }
                }
                $m->to($to)->subject($mail_subject);
            });
        }
    }

    /**
     * This function is used to upload single file or multiple files
     * @param string $destination_path
     * @param object|array $file
     * @param null $resize
     * @return bool
     */
    public static function uploadMedia($destination_path,$file,$resize = NULL)
    {
        if(is_array($file)){
            foreach ($file as $value)
            {
                $extension  = $value->extension();
                $fileUrl   = Storage::disk('public')->putFile($destination_path, $value);
                if($extension == 'jpg' || $extension = 'png' || $extension == 'jpeg')
                    self::resize($destination_path,$fileUrl,$resize);

                $filename[] = $fileUrl;
            }
        }else{
            $extension  = $file->extension();
            $filename = Storage::disk('public')->putFile($destination_path,  new File($file));
            if($extension == 'jpg' || $extension = 'png' || $extension == 'jpeg'){}
                //self::resize($destination_path,$filename,$resize);
        }
        return $filename;
    }

    /**
     * This function is used to upload single file or multiple files by path
     * @param string $destination_path
     * @param object|array $file
     * @param null $resize
     * @return bool
     */
    public static function uploadMediaByPath($destination_path,$file,$resize = NULL)
    {
        if(is_array($file)){
            foreach ($file as $value)
            {
                $extension  = $value->extension();
                $fileUrl   = Storage::disk('public')->putFile($destination_path, new File($value));
                if($extension == 'jpg' || $extension = 'png' || $extension == 'jpeg')
                    self::resize($destination_path,$fileUrl,$resize);

                $filename[] = $fileUrl;
            }
        }else{
            $extension  = pathinfo($file,PATHINFO_EXTENSION);
            $filename = Storage::disk('public')->putFile($destination_path, new File($file));
            if($extension == 'jpg' || $extension = 'png' || $extension == 'jpeg')
                self::resize($destination_path,$filename,$resize);
        }
        return $filename;
    }

    /**
     * This function is used to resize upload image
     * @param string $destination_path
     * @param string $file
     * @param string $dimension
     */
    public static function resize($destination_path,$file,$dimension)
    {
        if(!empty($dimension)){
            $getImageDimension = explode('x',strtolower($dimension));
            $resizeWidth       = $getImageDimension[0];
            $resizeHeight      = $getImageDimension[1];
            Image::make(Storage::disk('public')->path($file))
                ->resize($resizeWidth, $resizeHeight)
                ->save( Storage::disk('public')->path($destination_path . '/thumb_' . basename($file)) );
        }

    }

    /**
     * This function is used to optimize upload image
     * @param string $source_path
     * @param string $destination_path
     * @param integer $quality
     * @return mixed
     */
    public static function optimizeImage($source_path, $destination_path, $quality)
    {
        $info = getimagesize($source_path);
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source_path);
        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source_path);
        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source_path);

        //save file
        imagejpeg($image, $destination_path, $quality);

        //return destination file
        return $destination_path;
    }

    /**
     * This function is used to get application by identifier
     * @param string $identifer
     * @param string $meta_key
     * @return array | string
     */
    public static function appSetting(string $identifer, string $meta_key = '')
    {
        $meta_value = '';
        $records = Cache::rememberForever('setting_' . $identifer, function () use ($identifer) {
            return DB::table('application_setting')->where('identifier',$identifer)->get();
        });
        if( count($records) ){
            foreach($records as $record){
                if( !empty($meta_key) && $record->meta_key == $meta_key ){
                    $meta_value = $record->value;
                }
            }
        }
        return $meta_value;
    }

    /**
     * This function is used to get current route privilege
     * @return object $record
     */
    public static function modulePermission()
    {
        return CmsModule::getCurrentRoutePrivilege();
    }
}
