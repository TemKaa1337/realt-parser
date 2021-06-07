<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisment extends Model
{
    use HasFactory;
    protected $fillable = ['*'];

    public function getAllAdvertisments(int $skip) : array
    {
        $result = [];
        $skip = $skip * 50;

        $advertisments = self::orderBy('id')->skip($skip ?? 0)->take(100)->get();

        foreach ($advertisments as $advertisment) {
            $result[] = [
                'id' => $advertisment->id,
                'image_url' => $advertisment->image_path,
                'link' => $advertisment->link,
                'header' => $advertisment->header,
                'byn' => $advertisment->byn_price,
                'usd' => $advertisment->usd_price,
                'phones' => json_decode($advertisment->phones, true),
                'emails' => json_decode($advertisment->emails, true),
                'location' => $advertisment->location,
                'room_count' => $advertisment->room_count,
                'description' => $advertisment->description,
                'posted_at' => $advertisment->posted_at,
            ];
        }

        return $result;
    }
}
