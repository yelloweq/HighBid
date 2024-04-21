<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Http\Request;

class RatingController extends Controller
{
   public function createOrUpdate(String $type, $model, Request $request): int
   {
      $request->validate([
         'value' => 'required|integer|between:-1,1'
      ]);
      if ($type == 'thread') {
         $model = Thread::find($model);
      } elseif ($type == 'comment') {
         $model = Comment::find($model);
      } else {
         return 0;
      }
      $model->ratings()->where('user_id', $request->user()->id)->updateOrCreate([
         'user_id' => $request->user()->id,
      ], [
         'value' => $request->value,
      ]);
      return $model->getRatingAttribute();
   }
}
