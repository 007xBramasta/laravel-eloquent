<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function testCreateComments()
    {
        $comment = new Comment();
        $comment->email = "bramasta@gmail.com";
        $comment->title = "Sample Title";
        $comment->comment = "Sample Comment";
        $comment->commentable_id = '1';
        $comment->commentable_type = 'product';

        $comment->save();
        self::assertNotNull($comment->id);
    }

    public function testDefaultAttributeValues()
    {
        $comment = new Comment();
        $comment->email = "bramasta@gmail.com";
        $comment->commentable_id = '1';
        $comment->commentable_type = 'product';

        $comment->save();
        self::assertNotNull($comment->id);
        self::assertNotNull($comment->title);
        self::assertNotNull($comment->comment);
    }

    public function testCreate()
    {
        $req = [
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category"
        ];

        $category = new Category($req);
        $category->save();

        self::assertNotNull($category->id); 
    }

    public function testCreateUsingQueryBuilder()
    {
        $req = [
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category"
        ];

        // $category = Category::query()->create($req);
        $category = Category::create($req);

        self::assertNotNull($category->id); 
    }

    public function testUpdateMass() //update massal
    {
        $this->seed(CategorySeeder::class);

        $req = [
            "name" => "Food Updated",
            "description" => "Food Category Updateddd"
        ];

        $category = Category::find("FOOD");
        $category->fill($req);
        $category->save();

        self::assertNotNull($category->id);
    }
}
