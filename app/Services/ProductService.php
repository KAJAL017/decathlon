<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get filtered products for shop page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getShopProducts(Request $request)
    {
        return $this->productRepository->filterProducts($request->all());
    }

    /**
     * Get product details for product page
     *
     * @param string $slug
     * @return \App\Models\Product
     */
    public function getProductBySlug($slug)
    {
        return $this->productRepository->findBySlug($slug);
    }
}
