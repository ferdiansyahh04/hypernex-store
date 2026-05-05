<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    public function index()
    {
        return view('admin/products/index', [
            'title' => 'Admin Products',
            'products' => (new ProductModel())->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('admin/products/form', [
            'title' => 'Create Product',
            'product' => null,
            'action' => site_url('admin/products'),
        ]);
    }

    public function store()
    {
        $data = $this->validatedData();
        if ($data === null) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data['image'] = $this->handleUpload('image');
        $data['image_secondary'] = $this->handleUpload('image_secondary', false);
        
        (new ProductModel())->insert($data);

        return redirect()->to('/admin/products')->with('success', 'Product created.');
    }

    public function edit(int $id)
    {
        $product = (new ProductModel())->find($id);
        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Product not found');
        }

        return view('admin/products/form', [
            'title' => 'Edit Product',
            'product' => $product,
            'action' => site_url('admin/products/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new ProductModel();
        $product = $model->find($id);
        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Product not found');
        }

        $data = $this->validatedData();
        if ($data === null) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle Primary Image
        $image = $this->handleUpload('image', false);
        if ($image !== null) {
            $data['image'] = $image;
            $this->deleteImage($product['image']);
        }

        // Handle Secondary Image
        $imageSecondary = $this->handleUpload('image_secondary', false);
        if ($imageSecondary !== null) {
            $data['image_secondary'] = $imageSecondary;
            $this->deleteImage($product['image_secondary']);
        }

        $model->update($id, $data);

        return redirect()->to('/admin/products')->with('success', 'Product updated.');
    }

    public function delete(int $id)
    {
        $model = new ProductModel();
        $product = $model->find($id);

        if ($product) {
            $this->deleteImage($product['image']);
            $this->deleteImage($product['image_secondary']);
            $model->delete($id);
        }

        return redirect()->to('/admin/products')->with('success', 'Product deleted.');
    }

    private function validatedData(): ?array
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[160]',
            'description' => 'permit_empty|max_length[1200]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock' => 'required|integer|greater_than_equal_to[0]',
            'image' => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
            'image_secondary' => 'permit_empty|is_image[image_secondary]|mime_in[image_secondary,image/jpg,image/jpeg,image/png,image/webp]|max_size[image_secondary,2048]',
        ];

        if (! $this->validate($rules)) {
            return null;
        }

        return [
            'name' => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')),
            'price' => (float) $this->request->getPost('price'),
            'stock' => (int) $this->request->getPost('stock'),
        ];
    }

    private function handleUpload(string $fieldName, bool $useDefault = true): ?string
    {
        $file = $this->request->getFile($fieldName);

        if (! $file || ! $file->isValid()) {
            return $useDefault ? 'default-product.svg' : null;
        }

        $name = $file->getRandomName();
        $file->move(FCPATH . 'uploads/products', $name);

        return $name;
    }

    private function deleteImage(?string $image): void
    {
        if (! $image || $image === 'default-product.svg') {
            return;
        }

        $path = FCPATH . 'uploads/products/' . $image;
        if (is_file($path)) {
            unlink($path);
        }
    }
}
