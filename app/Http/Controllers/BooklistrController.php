<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookOnloan;

class BooklistrController extends Controller
{
    public function index()
    {
        $books = BookOnloan::all();
        echo $books;
        // eloquantをそのままreturnすると、jsonに変換してくれる。
        return $books;
    }
}
