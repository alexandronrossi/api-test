<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Testing\Fluent\AssertableJson;

class BooksControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Tests para o controler de books
     */
    public function test_books_get_endpoint(): void
    {
        $books = Book::factory(3)->create();
        
        $response = $this->getJson('/api/books');
        
        $response->assertJsonCount(3);
        
        $response->assertStatus(200);
        
        $response->assertJson(function (AssertableJson $json) use ($books) {
            $json->hasAll([
                '0.id',
                '0.title',
                '0.isbn'
            ]);

            $json->whereAllType([
                '0.id'    => 'integer',
                '0.title' => 'string',
                '0.isbn'  => 'string'
            ]);

            $book = $books->first();
            $json->whereAll([
                '0.id'    => $book->id,
                '0.title' => $book->title,
                '0.isbn' => $book->isbn,
            ]);
        });
    }
}
