<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-table-wrap p-4 p-lg-5">
            <form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label class="font-serif text-muted small text-uppercase mb-2 d-block italic" style="letter-spacing: 0.1em;">Asset Name</label>
                    <input type="text" name="name" class="form-control admin-input" value="<?= old('name', $product['name'] ?? '') ?>" required placeholder="e.g. Nebula K87 Keyboard">
                </div>

                <div class="mb-4">
                    <label class="font-serif text-muted small text-uppercase mb-2 d-block italic" style="letter-spacing: 0.1em;">Detailed Description</label>
                    <textarea name="description" class="form-control admin-input" rows="6" placeholder="Detailed product specifications..."><?= old('description', $product['description'] ?? '') ?></textarea>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="font-serif text-muted small text-uppercase mb-2 d-block italic" style="letter-spacing: 0.1em;">Valuation (IDR)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-dark text-dark">Rp</span>
                            <input type="number" name="price" class="form-control admin-input" value="<?= old('price', $product['price'] ?? '') ?>" required placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="font-serif text-muted small text-uppercase mb-2 d-block italic" style="letter-spacing: 0.1em;">Vault Stock</label>
                        <input type="number" name="stock" class="form-control admin-input" value="<?= old('stock', $product['stock'] ?? '') ?>" required placeholder="0">
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="font-serif text-muted small text-uppercase mb-2 d-block italic" style="letter-spacing: 0.1em;">Primary Visual</label>
                        <div class="p-4 border border-dark border-opacity-10 text-center bg-white">
                            <input type="file" name="image" class="form-control admin-input mb-3" id="productImageInput" accept="image/*">
                            <p class="text-muted mb-0" style="font-size: 0.65rem; font-family: 'Space Grotesk', sans-serif;">MAIN DISPLAY ASSET</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="font-serif text-muted small text-uppercase mb-2 d-block italic" style="letter-spacing: 0.1em;">Hover Visual (Secondary)</label>
                        <div class="p-4 border border-dark border-opacity-10 text-center bg-white">
                            <input type="file" name="image_secondary" class="form-control admin-input mb-3" id="productSecondaryImageInput" accept="image/*">
                            <p class="text-muted mb-0" style="font-size: 0.65rem; font-family: 'Space Grotesk', sans-serif;">INTERACTIVE HOVER ASSET</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-dark px-5 py-3 text-uppercase fw-bold rounded-0" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                        Commit Changes
                    </button>
                    <a href="<?= site_url('/admin/products') ?>" class="btn btn-outline-dark px-4 py-3 text-uppercase fw-bold rounded-0" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                        Discard
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-table-wrap p-4 mb-4">
            <h3 class="font-serif text-muted small text-uppercase mb-4 italic" style="letter-spacing: 0.1em;">Public Preview</h3>
            <div class="product-card border border-dark">
                <div class="product-media-container" style="aspect-ratio: 1/1; background: #fff;">
                    <img src="<?= base_url('uploads/products/' . esc($product['image'] ?? 'default-product.svg')) ?>" 
                         id="previewImage"
                         class="w-100 h-100 object-fit-contain p-4" 
                         alt="Preview"
                         onerror="this.src='https://images.unsplash.com/photo-1603481546238-487240415921?q=80&w=400&auto=format&fit=crop'">
                </div>
                <div class="p-3 border-top border-dark">
                    <div id="previewName" class="text-dark fw-bold mb-1 text-uppercase" style="font-family: 'Space Grotesk', sans-serif; font-size: 0.85rem;"><?= esc($product['name'] ?? 'Asset Name') ?></div>
                    <div id="previewPrice" class="font-serif text-dark" style="font-size: 1rem; font-style: italic;">Rp <?= number_format((float)($product['price'] ?? 0), 0, ',', '.') ?></div>
                </div>
            </div>
        </div>

        <div class="admin-table-wrap p-4">
            <h3 class="font-serif text-muted small text-uppercase mb-4 italic" style="letter-spacing: 0.1em;">Guidelines</h3>
            <ul class="text-dark small ps-3 font-serif italic" style="line-height: 1.8;">
                <li class="mb-2">Ensure high-fidelity imagery (PNG/WEBP).</li>
                <li class="mb-2">Descriptions must be concise yet technical.</li>
                <li class="mb-2">Verify valuation before committing.</li>
                <li>Archive outdated assets to maintain history.</li>
            </ul>
        </div>
    </div>
</div>


<script>
    // Simple live preview
    document.getElementById('productImageInput').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            document.getElementById('previewImage').src = URL.createObjectURL(file);
        }
    }
</script>

<?= $this->endSection() ?>

