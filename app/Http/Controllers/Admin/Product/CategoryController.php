<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\Product\CategoryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(Request $request)
    {
        $res = $this->categoryService->getList($request->all());

        if(!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Get category list fail');
        }

        $data = $res['data'];

        return $this->json(Response::HTTP_OK, null, $data, Arr::get($res, 'meta', null));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function store(Request $request)
    {
        $post = $request->all();
        $errors = $this->formValidate($post);
        if (empty($errors) == false) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Category form validate fail', ['errors' => $errors]);
        }
        $res = $this->categoryService->add($post);
        if(!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Fail to add new category');
        }

        return $this->json(Response::HTTP_OK, 'Success to add new category');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function show($id)
    {
        $res = $this->categoryService->getById($id);
        if(!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Get category info fail');
        }

        if(empty($res['data'])) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'this category not exist');
        }
        return $this->json(Response::HTTP_OK, null, $res['data']);
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
            return $this->json(Response::HTTP_BAD_REQUEST, 'Category form validate fail', ['errors' => $errors]);
        }
        $res = $this->categoryService->updateById($id, $post);

        if(!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Fail to update category');
        }

        return $this->json(Response::HTTP_OK, 'Success to update category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function destroy($id)
    {
        $res = $this->categoryService->deleteById($id);

        if (!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Fail to delete category');
        }

        return $this->json(Response::HTTP_OK, 'Success to delete category');
    }

    /**
     * Category autocomplete
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function autocomplete(Request $request)
    {
        $searchParams = $request->all();

        $res = $this->categoryService->getList($searchParams);
        if(!$res['success']) {
            return $this->json(Response::HTTP_BAD_REQUEST, 'Get category list fail');
        }

        $categories = $res['data'];
        $list = [];
        if (is_array($categories)) {
            foreach ($categories as $item) {
                $list[] = [
                    'value' => $item['id'],
                    'label' => $item['name'],
                ];
            }
        }

        return $this->json(Response::HTTP_OK, null, $list, Arr::get($res, 'meta', null));
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
        ]);
        return $validator->errors()->messages();
    }
}
