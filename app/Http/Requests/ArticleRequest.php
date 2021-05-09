<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//投稿したユーザのみ編集する権限を与える
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() //バリデーションのルールを設定
    {
        return [
            'title' => 'required | max:50',
            'body' => 'required | max:50',
            'tags' => 'json|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u',
        ];
    }

    public function attributes() //記事投稿専用のエラーメッセージ
    {
        return [
            'title' => 'タイトル',
            'body' => '本文',
            'tags' => 'タグ',
        ];
    }

    public function passedValidation()
    {
        $this->tags = collect(json_decode($this->tags))
          ->slice(0,5)
          ->map(function ($requestTag){
              return $requestTag->text;
          });
    }
}
