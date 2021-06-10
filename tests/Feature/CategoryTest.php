<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testGetCategoryList()
    {
        $query = '{
            categories(page:2, page_size:3, filter_name:"p") {
                id,
                name,
                sort_order
            }
        }';

        $expected = [
            'data' => [
                'categories' => [
                    '*' => [
                        'id',
                        'name',
                        'sort_order',
                    ],
                ],
            ],
        ];

        $this->assertJsonStructure(
            '/graphql',
            [
                'query' => $query,
            ],
            $expected
        );
    }

    public function testCategoryDetail()
    {
        $categoryId = DB::table('categories')->whereNull('deleted_at')->first()->id;
        $query = '{
          category(id:' . $categoryId . ') {
                id,
                name,
                sort_order
            }
        }';

        $expected = [
            'data' => [
                'category' => [
                    'id',
                    'name',
                    'sort_order',
                ],
            ],
        ];

        $this->assertJsonStructure(
            '/graphql',
            [
                'query' => $query,
            ],
            $expected
        );
    }

    public function testAddCategory()
    {
        $query = 'mutation {
          createCategory(
            name:"dddddd",
            sort_order:532
          ) {id,name,sort_order}
        }';

        $expected = [
            'data' => [
                'createCategory' => [
                    'id',
                    'name',
                    'sort_order',
                ],
            ],
        ];

        $this->assertJsonStructure(
            '/graphql',
            [
                'query' => $query,
            ],
            $expected
        );
    }

    public function testEditCategory()
    {
        $categoryId = DB::table('categories')->whereNull('deleted_at')->first()->id;
        $query = 'mutation {
          updateCategory(
            id:'.$categoryId.',
            name:"eeeeee",
            sort_order:125
          )
        }';
        $response = $this->graphql($query);

        $this->assertEquals(
            "Update success",
            $response->json("data.updateCategory")
        );
    }

    public function testDeleteCategory()
    {
        $categoryId = DB::table('categories')->whereNull('deleted_at')->first()->id;
        $query = 'mutation {
          deleteCategory(
            id:'.$categoryId.'
          )
        }';
        $response = $this->graphql($query);

        $this->assertEquals(
            "Delete success",
            $response->json("data.deleteCategory")
        );
    }
}
