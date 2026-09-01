<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    // $fillable は「クラスのプロパティ」,
    // group() や users() は設定ではなくリレーションというメソッドなので、これは必ず class の中
    // 中＝そのclassが持つもの
    protected $fillable = ['name'];
    public function users() {
        return $this->hasMany(User::class);
    }
}