<?php

namespace Jokoli\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jokoli\Category\Http\Requests\CategoryRequest;
use Jokoli\Category\Models\Category;
use Jokoli\Category\Repository\CategoryRepository;
use Jokoli\Common\Responses\AjaxResponses;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $this->authorize('index', Category::class);
        $categories = $this->categoryRepository->all(true);
        return view('Category::index', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $this->categoryRepository->store($request);
        return redirect()->route('categories.index');
    }

    public function edit($category)
    {
        $this->authorize('edit', Category::class);
        $category=$this->categoryRepository->findById($category);
        $categories = $this->categoryRepository->allExceptById($category->id);
        return view('Category::edit', compact('category', 'categories'));
    }

    public function update(CategoryRequest $request, $category)
    {
        $category=$this->categoryRepository->findById($category);
        $this->categoryRepository->update($category, $request);
        return redirect()->route('categories.index');
    }

    public function destroy($category)
    {
        $this->authorize('destroy', Category::class);
        $category=$this->categoryRepository->findById($category);
        try {
            $this->categoryRepository->destroy($category);
            return AjaxResponses::successResponse();
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }
}
