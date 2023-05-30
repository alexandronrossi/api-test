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
    public function test_get_single_books_endpoint(): void
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
    public function test_post_books_endpoint(): void
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

    /**
     * Tests para o controler de books
     */
    public function test_post_books_should_validate__when_try_create_a_invalid_book(): void
    {
        $response = $this->postJson("/api/books", []);

        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors']);

            $json->where('errors.title.0', 'Este campo é obrigatório!');
        });
    }

    /**
     * Tests para o controler de books
     */
    public function test_put_books_endpoint(): void
    {

        $bookCreated = Book::factory(1)->createOne();

        $book = [
            'title' => 'Alterando titulo',
            'isbn'  => '1234567890'
        ];

        $response = $this->putJson("/api/books/{$bookCreated->id}", $book);
        
        
        $response->assertStatus(200);
        
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

    /**
     * Tests para o controler de books
     */
    public function test_patch_books_endpoint(): void
    {

        $bookCreated = Book::factory(1)->createOne();

        $book = [
            'title' => 'Alterando titulo',
        ];

        $response = $this->patchJson("/api/books/{$bookCreated->id}", $book);
        
        
        $response->assertStatus(200);
        
        $response->assertJson(function (AssertableJson $json) use ($book) {
            $json->hasAll([
                'id',
                'title',
                'isbn'
            ])->etc();

            $json->where('title',  $book['title'])->etc();
        });
    }

    /**
     * Tests para o controler de books
     */
    public function test_delete_books_endpoint(): void
    {
        $bookCreated = Book::factory(1)->createOne();
        $response = $this->deleteJson("/api/books/{$bookCreated->id}");

        $response->assertStatus(204);
    }

}
