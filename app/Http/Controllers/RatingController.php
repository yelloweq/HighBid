<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRatingRequest;
use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;

class RatingController extends Controller
{
   public function createOrUpdate(String $type, $model, Request $request)
   {
      if (!Auth::check()) {
         return new HtmxResponseClientRedirect(route('login'));
      }

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
