<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Book;

class BooksControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Tests para o controler de books
     */
    public function test_get_books_endpoint(): void
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

    /**
     * Tests para o controler de books
     */
    public function test_get_book_endpoint(): void
    {
        $book = Book::factory(1)->createOne();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use ($book) {
            $json->hasAll([
                'id',
                'title',
                'isbn'
            ])->etc();

            $json->whereAllType([
                'id'    => 'integer',
                'title' => 'string',
                'isbn'  => 'string'
            ]);

            $json->whereAll([
                'id'    => $book->id,
                'title' => $book->title,
                'isbn'  => $book->isbn,
            ]);
        });
    }

    /**
     * Tests para o controler de books
     */
    public function test_post_book_endpoint(): void
    {
        $book = Book::factory(1)->makeOne()->toArray();

        $response = $this->postJson("/api/books", $book);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) use ($book) {
            $json->hasAll([
                'id',
                'title',
                'isbn'
            ])->etc();

            $json->whereAll([
                'title' => $book['title'],
                'isbn'  => $book['isbn']
            ])->etc();
        });
    }
}
