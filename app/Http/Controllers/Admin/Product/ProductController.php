<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\Product\ProductService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $res = $this->productService->getList($params);

        if(!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Get product list fail');
        }

        return $this->json(Response::HTTP_OK, null, $res['data'], Arr::get($res, 'meta', null));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function show($id)
    {
        $res = $this->productService->getById($id, ['Category']);
        if(!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Get product info fail');
        }

        return $this->json(Response::HTTP_OK, null, $res['data']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function store(Request $request)
    {
        $post = $request->all();
        $errors = $this->formValidate($post);
        if (empty($errors) == false) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Product form validate fail', ['errors' => $errors]);
        }
        $res = $this->productService->add($post);
        if(!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Fail to add new product');
        }


        return $this->json(Response::HTTP_OK, 'Success to add new product');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $post = $request->all();
        $errors = $this->formValidate($post);
        if (empty($errors) == false) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Product form validate fail', ['errors' => $errors]);
        }
        $res = $this->productService->updateById($id, $post);

        if(!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Fail to update product');
        }

        return $this->json(Response::HTTP_OK, 'Success to update product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function destroy($id)
    {
        $res = $this->productService->deleteById($id);

        if (!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Fail to delete product');
        }

        return $this->json(Response::HTTP_OK, 'Success to delete product');
    }

    /**
     * Validate Form.
     *
     * @param  array $data
     * @return array
     */
    private function formValidate($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|min:2|max:50',
            'price' => 'required'
        ]);
        return $validator->errors()->messages();
    }
}
