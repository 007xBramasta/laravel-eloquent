<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Person;
use App\Models\Product;
use Carbon\Carbon;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PersonTest extends TestCase
{
    public function testPerson()
    {
        $person = new Person();
        $person->first_name = "Bram";
        $person->last_name = "Albatio";
        $person->save();

        self::assertEquals("BRAM Albatio", $person->full_name);

        $person->full_name = "BRAMASTA Albatio";
        $person->save();

        self::assertEquals("BRAMASTA", $person->first_name);
        self::assertEquals("Albatio", $person->last_name);
    }

    public function testAttributeCasting()
    {
        $person = new Person();
        $person->first_name = "Bram";
        $person->last_name = "Albatio";
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
    }

    public function testCustomeCasts()
    {
        $person = new Person();
        $person->first_name = "Bram";
        $person->last_name = "Albatio";
        $person->address = new Address("Durenan", "Trenggalek", "Indonesia", "007");
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
        self::assertEquals("Durenan", $person->address->street);
        self::assertEquals("Trenggalek", $person->address->city);
        self::assertEquals("Indonesia", $person->address->country);
        self::assertEquals("007", $person->address->postal_code);
    }

    public function testSerialization()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $products = Product::query()->get();
        self::assertCount(2, $products);

        $json = $products->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }

    public function testSerializationRelation()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);

        $products = Product::query()->get();
        $products->load("category", "image");
        self::assertCount(2, $products);

        $json = $products->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }
}
