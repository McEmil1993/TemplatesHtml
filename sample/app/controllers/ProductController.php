<?php
class ProductController {
    public function index() {
        echo "Product management page";
    }

    public function create() {
        echo "Create product page";
    }

    public function edit($id) {
        echo "Edit product page for product ID: " . $id;
    }

    public function delete($id) {
        echo "Delete product logic for product ID: " . $id;
    }
}