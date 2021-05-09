<?php

namespace App\Http\Controllers;


use App\Article;
use App\Tag;

use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;




class ArticleController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }
    public function index(Request $request)
    {

        $articles = Article::all()->sortByDesc('created_at')
            ->load(['user', 'likes', 'tags']);

        return view('articles.index', ['articles' => $articles]);
        // return view('articles.index')->with(['articles' => $articles]); withメソッドをつなげて引数にビューファイルにわたす変数の名称とその変数の値を連想配列形式を指定
        // return view('articles.index', compact('articles')); Compact関数を使った結果をわたす。変数を連想配列形式で記述しなくていいのでコードの量が減ってスッキリする
    }



    public function create()
    {
        $allTagNames = Tag::all()->map(function ($tag){
            return['text'=> $tag->name];
        });


        return view('articles.create', [
            'allTagNames' => $allTagNames,
        ]);
    }

    public function store(ArticleRequest $request, Article $article) //-- ArticleクラスのDI(Dependency Injection)を行っている
    {
        // $article = new Article(); //-- storeアクションメソッド内でArticleクラスのインスタンスを生成している
        $article->fill($request->all());  //article.php fillableで不正なPOSTリクエストを防ぐことができる
        $article->user_id = $request->user()->id;
        $article->save();

        $request->tags->each(function($tagName)use($article){  //コレクションのEachメソッドでタグの数だけ繰り返し実行する
            $tag = Tag::firstOrCreate(['name' => $tagName]);//記事と同時にタグを登録する時、tagsテーブルに存在するタグか、新規のタグかをfirstOrCreateメソッドで判別する→＄Tagにタグモデルを代入する
            $article->tags()->attach($tag); //article-tagテーブルへのレコードの保存する
        });

        return redirect()->route('articles.index');
    }

    public function edit(Article $article)
    {
        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames,
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {

        $article->fill($request->all());
        $article->save();

        $article->tags()->detach();
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }

    public function show(Article $article)
    {
        return view('articles.show',['article'=> $article]);
    }

    public function like(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return[
            'id' => $article->id,
            'countLikes'=>$article->count_likes,
        ];
    }
    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);
        return[
            'id' => $article->id,
            'countLikes'=>$article->count_likes,
        ];
    }
}
