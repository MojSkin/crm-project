<?php

namespace App\Models;

use App\Helpers\MojSkin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Image extends _BaseModel
{
    use HasFactory;

    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'type',
        'file_name',
        'user',
        'alt',
    ];

    public function imageable(): MorphTo {
        return $this->morphTo();
    }

    /**
     * @throws \Exception
     */
    public static function make($imageable_id, $imageable_type, $image_data, $image_desc = null, $save_path = 'storage/uploads/images', $file_name = null)
    {
        try {
            MojSkin::makeDir(public_path($save_path));
            $image  = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_data));
            $result = \Intervention\Image\Facades\Image::make($image);
            if (gettype($file_name) !== 'string') {
                $file_name = MojSkin::randomFileName(35, public_path($save_path), 'image-', '', 'png', true);
            }
            $result->save(public_path($save_path.'/'.$file_name));
            $result = self::create([
                'imageable_id' => $imageable_id,
                'imageable_type' => $imageable_type,
                'file_name' => $file_name,
                'alt' => $image_desc,
                'user' => Auth::id()
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage().' at Line '.$e->getLine().' at Line '.$e->getFile());
        }
        return $result;
    }

    public function u()
    {
        return $this->belongsTo(User::class, 'user')->with('details');
    }
}
