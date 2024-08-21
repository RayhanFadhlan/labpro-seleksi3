<?php

namespace App\Strategies;

use Illuminate\Http\Request;

interface TokenValidationStrategy
{
    public function validate(Request $request) : array;
}