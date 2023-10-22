<?php

namespace App\Helpers;

class MojSkin
{
    public static function randomFileName($length = 20, $path='', $prefix='', $postfix='', $ext = '', $returnExt=true, $fullPath=false, $maxTry=5000)
    {
        $characters1 = '0123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ-';
        $characters2 = 'abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters1);
        $randomString = $characters2[rand(0, 25)];
        $try = 1;
        if (substr($path, strlen($path)-1, 1)!='/') {
            $path.='/';
        }
        if (!empty($ext)) {
            $ext = '.'.$ext;
        }
        while ($try<=$maxTry) {
            for ($i = 0; $i < $length - 2; $i++) {
                $randomString .= $characters1[rand(0, $charactersLength - 1)];
            }

            $randomString .= $characters2[rand(0, 25)];

            $result = $prefix.$randomString.$postfix;

            $checkName = $path.$result;

            $checkName =$checkName.$ext;

            if (!file_exists($checkName)) {
                if ($returnExt==true and !empty($ext)) {
                    $result = $prefix.$randomString.$postfix.$ext;
                } else {
                    $result = $prefix.$randomString.$postfix;
                }
                if ($fullPath) {
                    $result = $path.$result;
                }
                break;
            }
            $try++;
        }

        return $result;
    }

    public static function makeDir($path, $permission=0755, $recursive=true)
    {
        $directory = false;
        if (!is_dir($path)) {
            $directory = mkdir($path, $permission, $recursive);
        }

        return $directory;
    }
    public static function replaceDigits(string $str, $to='fa', $from='en')
    {
        $numbers = [
            'en' => ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"],
            'fa' => ["۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "۰"],
            'ar' => ["١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩", "٠"],
        ];

        for ($i = 0; $i < count($numbers[$from]); $i++) {
            $str = str_replace(
                $numbers[$from][$i],
                $numbers[$to][$i],
                $str);
        }
        return $str;
    }

    public static function unlinkFile($pathToDelete)
    {
        if (!is_dir($pathToDelete) and file_exists($pathToDelete)) {
            unlink($pathToDelete);
            return true;
        }

        return false;
    }

    public static function check_mobile($mobile) {
        if ($mobile === null) {
            return null;
        }

        $mobile_pattern = '/(\+98|0|98|0098|)[9](0|1|2|3|4|5|9)\d{8}/';
        if (preg_match($mobile_pattern, $mobile)) {
            return substr($mobile, strlen($mobile) - 10, 10);
        }

        return false;
    }

}
