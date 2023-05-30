<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Book;

class BooksController extends Controller
{

    public function __construct(protected Book $book)
    {}

    public function index() 
    {
        return response()->json($this->book->all());
    }

    public function show($id) 
    {
        return response()->json($this->book->find($id));
    }

    public function store(Request $request) 
    {  
        return response()->json($this->book->create($request->all()), 201);
    }

}
