<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

trait ValidateSlug
{
    public function validateSlug($slug)
    {
        $validator = Validator::make(compact('slug'), [
            'slug' => 'max:100|regex:/^[a-z0-9-]+$/',
        ]);

        return $validator->validate();
    }
}
