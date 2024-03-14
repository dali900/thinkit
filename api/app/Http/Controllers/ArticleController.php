<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::all();
        return $this->responseSuccess(ArticleResource::collection($articles));
    }
    
    /**
     * Show all articles belonging to the logged in user
     */
    public function getUserAticles()
    {
        $articles = Article::with('reviews')
            ->whereRelation('authors', 'user_id', auth()->id())
            ->get();
        return $this->responseSuccess(ArticleResource::collection($articles));
    }

    public function getArticlesForReview()
    {
        $articles = Article::with(['authors', 'reviews'])
            ->whereRelation('reviews', 'reviews.user_id', '!=', auth()->id())
            ->get();
        return $this->responseSuccess(ArticleResource::collection($articles));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::find($id);
        if (!$article) {
            return $this->responseNotFound();
        }

        return $this->responseSuccess(ArticleResource::make($article));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateArticleRequest $request)
    {
        $validatedData = $request->validated();
        $user = auth()->user();
        if (!in_array($user->id, $validatedData['authors'])) {
            $validatedData['authors'][] = $user->id;
        }
        $article = Article::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
        ]);
        // Attach authors to article
        $article->authors()->attach($validatedData['authors']);
        
        return $this->responseCreated(ArticleResource::make($article));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, $id)
    {
        $article = Article::find($id);
        if (!$article) {
            return $this->responseNotFound();
        }
        $validatedData = $request->validated();
        $user = auth()->user();
        if (!in_array($user->id, $validatedData['authors'])) {
            $validatedData['authors'][] = $user->id;
        }
        $article->update($request->validated());
        $article->authors()->sync($validatedData['authors']);

        return $this->responseSuccess(ArticleResource::make($article));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::find($id);
        if (!$article) {
            return $this->responseNotFound();
        }
        $article->delete();

        return $this->responseNoContent();
    }
}
