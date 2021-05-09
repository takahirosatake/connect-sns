<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Article extends Model
{
    protected $fillable = [
        'title',
        'body',
    ];

    public function user():BelongsTo //BelongsToクラス以外の型を返そうととした場合の例外処理TypeErrorを処理
    {
        return $this->belongsTo('App\User');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    public function isLikedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->likes->where('id', $user->id)->count()
            : false;
        //Countメソッドで＄Userがいれば１かそれより大きい数値が返る。いなければ０が返る
        //(bool)は型キャストと呼ばれるPHPの機能変数の前に記述しその変数を括弧内に指定した型に変換する
        //＄Userがいれば、（True）いいねを取得するいなければ、（False）を返す
    }
    public function getCountLikesAttribute(): int
    {
        return $this->likes->count();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }
}
