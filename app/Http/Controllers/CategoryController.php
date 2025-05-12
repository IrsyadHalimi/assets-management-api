<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::select('id', 'name')->get());
    }
}
