<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-table-wrap p-4 mb-4">
            <h3 class="font-serif text-muted small text-uppercase mb-4 italic" style="letter-spacing: 0.1em;">Order Contents</h3>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Asset Details</th>
                            <th>Qty</th>
                            <th class="text-end">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="admin-product-item">
                                        <img src="<?= base_url('uploads/products/' . esc($item['product']['image'] ?? 'default-product.svg')) ?>" 
                                             class="admin-product-img" 
                                             onerror="this.src='https://images.unsplash.com/photo-1603481546238-487240415921?q=80&w=100&auto=format&fit=crop'">
                                        <div>
                                            <div class="text-dark fw-bold text-uppercase" style="font-family: 'Space Grotesk', sans-serif; font-size: 0.85rem;"><?= esc($item['product']['name'] ?? 'Asset Expired') ?></div>
                                            <div class="font-serif text-muted italic" style="font-size: 0.85rem;">Unit: Rp <?= number_format((float) $item['price'], 0, ',', '.') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-dark fw-bold"><?= esc($item['quantity']) ?></td>
                                <td class="text-end text-dark font-serif" style="font-size: 1.1rem; font-style: italic;">
                                    Rp <?= number_format((float) ($item['price'] * $item['quantity']), 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-table-wrap p-4 mb-4">
            <h3 class="font-serif text-muted small text-uppercase mb-4 italic" style="letter-spacing: 0.1em;">Curator Details</h3>
            <div class="mb-4">
                <label class="font-serif text-muted small d-block mb-1 italic">Identity</label>
                <div class="text-dark fw-bold text-uppercase" style="font-family: 'Space Grotesk', sans-serif;"><?= esc($order['shipping_name']) ?></div>
            </div>
            <div class="mb-4">
                <label class="font-serif text-muted small d-block mb-1 italic">Contact</label>
                <div class="text-dark fw-bold"><?= esc($order['shipping_phone']) ?></div>
            </div>
            <div class="mb-4">
                <label class="font-serif text-muted small d-block mb-1 italic">Destination</label>
                <div class="text-dark" style="line-height: 1.6; font-size: 0.9rem;">
                    <?= esc($order['shipping_address']) ?><br>
                    <?= esc($order['shipping_city']) ?>, <?= esc($order['shipping_postal_code']) ?>
                </div>
            </div>
        </div>

        <div class="admin-table-wrap p-4">
            <h3 class="font-serif text-muted small text-uppercase mb-4 italic" style="letter-spacing: 0.1em;">Transaction Summary</h3>
            <div class="d-flex justify-content-between align-items-end mb-2">
                <span class="text-muted small text-uppercase fw-bold" style="letter-spacing: 0.1em;">Final Valuation</span>
                <span class="font-serif text-dark h3 mb-0" style="font-style: italic;">Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></span>
            </div>
            <hr class="border-dark border-opacity-10 my-4">
            <button class="btn btn-dark w-100 py-3 text-uppercase fw-bold rounded-0" style="font-size: 0.8rem; letter-spacing: 0.15em;">
                <i class="bi bi-printer me-2"></i>Generate Manifest
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

