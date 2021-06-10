<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function testGetProductList()
    {
        $query = '{
            products(page:2, page_size:3, filter_name:"p", filter_category:"i") {
                id,
                name,
                category {
                    id,
                    name,
                    sort_order
                  }
                price,
                sort_order
            }
        }';

        $expected = [
            'data' => [
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'category' => [
                            '*' => [
                                'id',
                                'name',
                                'sort_order',
                            ],
                        ],
                        'price',
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

    public function testProductDetail()
    {
        $productId = DB::table('products')->whereNull('deleted_at')->first()->id;
        $query = '{
          product(id:' . $productId . ') {
                id,
                name,
                category {
                    id,
                    name,
                    sort_order
                  }
                price,
                sort_order
            }
        }';

        $expected = [
            'data' => [
                'product' => [
                    'id',
                    'name',
                    'category' => [
                        '*' => [
                            'id',
                            'name',
                            'sort_order',
                        ],
                    ],
                    'price',
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

    public function testAddProduct()
    {
        $query = 'mutation {
          createProduct(
            name:"aaaaaaaa",
            category_ids:[3,5,7],
            price:55.55,
            sort_order:666
          ) {id,name,price,sort_order}
        }';

        $expected = [
            'data' => [
                'createProduct' => [
                    'id',
                    'name',
                    'price',
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

    public function testEditProduct()
    {
        $productId = DB::table('products')->whereNull('deleted_at')->first()->id;
        $query = 'mutation {
          updateProduct(
            id:'.$productId.',
            name:"bbbbbbb",
            category_ids:[20,22,24],
            price:30.5,
            sort_order:125
          )
        }';
        $response = $this->graphql($query);

        $this->assertEquals(
            "Update success",
            $response->json("data.updateProduct")
        );
    }

    public function testDeleteProduct()
    {
        $productId = DB::table('products')->whereNull('deleted_at')->first()->id;
        $query = 'mutation {
          deleteProduct(
            id:'.$productId.'
          )
        }';
        $response = $this->graphql($query);

        $this->assertEquals(
            "Delete success",
            $response->json("data.deleteProduct")
        );
    }
}
