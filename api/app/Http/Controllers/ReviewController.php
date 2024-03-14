<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Requests\CreateReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::all();
        return response()->json($reviews);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $review = Review::find($id);
        if (!$review) {
            return $this->responseNotFound();
        }
        return $this->responseSuccess(ReviewResource::make($review));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateReviewRequest $request)
    {
        $validatedData = $request->validated();
        $reviews = [];

        foreach ($validatedData['reviews'] as $reviewData) {
            $review = Review::create([
                'article_id' => $reviewData['article_id'],
                'user_id' => auth()->id(), 
                'status' => $reviewData['status'],
                'comment' => $reviewData['comment'] ?? null,
            ]);

            $reviews[] = $review;
        }
        return $this->responseCreated(ReviewResource::collection($reviews));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, string $id)
    {
        $user = auth()->user();
        $review = Review::find($id);
        if (!$review) {
            return $this->responseNotFound();
        }
        //The user can only update his own review
        if ($review->user_id !== $user->id) {
            return $this->responseForbidden();
        }
        $review->update($request->validated());

        return $this->responseSuccess(ReviewResource::make($review));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        $review = Review::findOrFail($id);
        if (!$review) {
            return $this->responseNotFound();
        }
        //The user can only delete his own review
        if ($review->user_id !== $user->id) {
            return $this->responseForbidden();
        }

        return $this->responseNoContent();
    }
}
