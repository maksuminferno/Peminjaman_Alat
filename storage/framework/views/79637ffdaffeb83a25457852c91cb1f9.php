<?php $__env->startSection('title', 'Daftar Alat - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    /* Tool Card Styles */
    .tools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        padding: 20px 0;
    }

    .tool-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(249, 115, 22, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
    }

    .tool-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 35px rgba(249, 115, 22, 0.2);
        border-color: var(--orange-200);
    }

    .tool-card.selected {
        border-color: var(--orange-500);
        box-shadow: 0 8px 30px rgba(249, 115, 22, 0.25);
    }

    .tool-card.unavailable {
        opacity: 0.7;
        background: #fafafa;
    }

    .tool-card-image {
        height: 160px;
        background: linear-gradient(135deg, var(--orange-100), var(--orange-50));
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .tool-card-image i {
        font-size: 4rem;
        color: var(--orange-500);
        opacity: 0.8;
    }

    .tool-card-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .tool-card-badge.available {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }

    .tool-card-badge.unavailable {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .tool-card-checkbox {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 10;
    }

    .tool-card-checkbox input[type="checkbox"] {
        width: 22px;
        height: 22px;
        cursor: pointer;
        accent-color: var(--orange-500);
    }

    .tool-card-body {
        padding: 20px;
    }

    .tool-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 12px;
        line-height: 1.4;
        min-height: 50px;
    }

    .tool-card-info {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 16px;
    }

    .tool-card-info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .tool-card-info-item i {
        width: 20px;
        color: var(--orange-500);
    }

    .tool-card-stock {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: var(--orange-50);
        border-radius: 8px;
        color: var(--orange-text-soft);
        font-weight: 600;
        font-size: 0.85rem;
    }

    .tool-card-actions {
        display: flex;
        gap: 10px;
        padding-top: 16px;
        border-top: 2px solid var(--orange-50);
    }

    .btn-borrow-card {
        flex: 1;
        background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
        border: none;
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 4px 10px var(--orange-shadow-soft);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-borrow-card:hover {
        background: linear-gradient(135deg, var(--orange-600), var(--orange-700));
        box-shadow: 0 6px 14px var(--orange-shadow-strong);
        color: white;
        text-decoration: none;
    }

    .btn-borrow-card.disabled {
        background: #e5e7eb;
        color: #9ca3af;
        box-shadow: none;
        cursor: not-allowed;
        pointer-events: none;
    }

    .tools-header-card {
        background: linear-gradient(135deg, #ffffff, var(--orange-50));
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(249, 115, 22, 0.1);
        border: 2px solid var(--orange-100);
        margin-bottom: 24px;
    }

    .bulk-action-bar {
        background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.3);
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .bulk-action-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .bulk-count-badge {
        background: white;
        color: var(--orange-600);
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .btn-bulk-borrow {
        background: white;
        color: var(--orange-600);
        border: 2px solid white;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-bulk-borrow:hover {
        background: var(--orange-50);
        color: var(--orange-700);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(249, 115, 22, 0.08);
    }

    .empty-state i {
        font-size: 5rem;
        color: var(--orange-200);
        margin-bottom: 20px;
    }

    .empty-state h4 {
        color: var(--orange-text-soft);
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .tools-grid {
            grid-template-columns: 1fr;
        }

        .bulk-action-bar {
            flex-direction: column;
            gap: 16px;
            text-align: center;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-tools text-orange me-2"></i>Daftar Alat</h2>
                <p class="mb-0 text-muted">Pilih alat yang ingin Anda pinjam</p>
            </div>
        </div>
    </div>

    <!-- Bulk Action Bar (shown when items selected) -->
    <div id="bulkActionBar" style="display: none;" class="mt-4">
        <div class="bulk-action-bar">
            <div class="bulk-action-info">
                <i class="fas fa-shopping-cart fa-lg"></i>
                <span><strong id="bulkCount">0</strong> alat dipilih</span>
            </div>
            <button type="button" class="btn btn-bulk-borrow" onclick="borrowSelected()">
                <i class="fas fa-hand-point-right me-2"></i>Pinjam Alat Dipilih
            </button>
        </div>
    </div>

    <!-- Tools Grid -->
    <?php
        // Grouped data is already prepared in controller
    ?>

    <?php $__empty_1 = true; $__currentLoopData = $groupedAlat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namaAlat => $tools): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            // Calculate total stock for this tool name
            $totalStock = $tools->sum('stok');
            // Get the first tool's details (category, etc.)
            $firstTool = $tools->first();
            // Collect all id_alat for this group
            $toolIds = $tools->pluck('id_alat')->toArray();
        ?>

        <?php if($loop->first): ?>
        <div class="tools-grid" id="toolsGrid">
        <?php endif; ?>

        <div class="tool-card <?php echo e($totalStock > 0 ? '' : 'unavailable'); ?>" data-nama-alat="<?php echo e($namaAlat); ?>">
            <!-- Card Image -->
            <div class="tool-card-image">
                <?php if($totalStock > 0): ?>
                <div class="tool-card-checkbox">
                    <input type="checkbox" class="tool-checkbox"
                        data-nama-alat="<?php echo e($namaAlat); ?>"
                        data-tool-ids='<?php echo json_encode($toolIds, 15, 512) ?>'
                        data-stok="<?php echo e($totalStock); ?>"
                        onchange="updateBorrowButton()">
                </div>
                <?php endif; ?>
                <i class="fas fa-tool-box"></i>
                <span class="tool-card-badge <?php echo e($totalStock > 0 ? 'available' : 'unavailable'); ?>">
                    <?php echo e($totalStock > 0 ? 'Tersedia' : 'Tidak Tersedia'); ?>

                </span>
            </div>

            <!-- Card Body -->
            <div class="tool-card-body">
                <h5 class="tool-card-title"><?php echo e($namaAlat); ?></h5>

                <div class="tool-card-info">
                    <div class="tool-card-info-item">
                        <i class="fas fa-tag"></i>
                        <span><?php echo e($firstTool->kategori->nama_kategori); ?></span>
                    </div>
                    <div class="tool-card-info-item">
                        <i class="fas fa-box"></i>
                        <span class="tool-card-stock">
                            <i class="fas fa-layer-group"></i>
                            Stok: <?php echo e($totalStock); ?> unit
                        </span>
                    </div>
                </div>

                <div class="tool-card-actions">
                    <?php if($totalStock > 0): ?>
                        <a href="<?php echo e(route('peminjam.ajukan.peminjaman', ['id' => $firstTool->id_alat])); ?>"
                           class="btn-borrow-card">
                            <i class="fas fa-hand-holding-hand"></i>
                            Pinjam Alat
                        </a>
                    <?php else: ?>
                        <button class="btn-borrow-card disabled" disabled>
                            <i class="fas fa-times-circle"></i>
                            Tidak Tersedia
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if($loop->last): ?>
        </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <!-- Empty State -->
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h4>Tidak Ada Alat Tersedia</h4>
        <p>Maaf, saat ini belum ada alat yang tersedia untuk dipinjam.</p>
    </div>
    <?php endif; ?>
</div>

<script>
    // Update borrow button state and card styling
    function updateBorrowButton() {
        const checkboxes = document.querySelectorAll('.tool-checkbox:checked');
        const bulkActionBar = document.getElementById('bulkActionBar');
        const bulkCount = document.getElementById('bulkCount');
        const cards = document.querySelectorAll('.tool-card');

        // Update card selection styling
        cards.forEach(function(card) {
            const checkbox = card.querySelector('.tool-checkbox');
            if (checkbox && checkbox.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });

        // Show/hide bulk action bar
        if (checkboxes.length > 0) {
            bulkActionBar.style.display = 'block';
            bulkCount.textContent = checkboxes.length;
        } else {
            bulkActionBar.style.display = 'none';
            bulkCount.textContent = '0';
        }
    }

    // Redirect to borrow form with selected items
    function borrowSelected() {
        const checkboxes = document.querySelectorAll('.tool-checkbox:checked');

        if (checkboxes.length === 0) {
            alert('Pilih minimal 1 alat untuk dipinjam');
            return;
        }

        // Collect all tool IDs from selected groups
        let allToolIds = [];
        checkboxes.forEach(function(checkbox) {
            const toolIds = JSON.parse(checkbox.getAttribute('data-tool-ids'));
            allToolIds = allToolIds.concat(toolIds);
        });

        // Remove duplicates and redirect
        const uniqueIds = [...new Set(allToolIds)];
        window.location.href = '<?php echo e(route("peminjam.ajukan.peminjaman")); ?>?alat=' + uniqueIds.join(',');
    }

    // Add click event to cards for easier selection
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.tool-card:not(.unavailable)');

        cards.forEach(function(card) {
            card.addEventListener('click', function(e) {
                // Don't trigger if clicking on checkbox or button
                if (e.target.closest('.tool-card-checkbox') ||
                    e.target.closest('.btn-borrow-card')) {
                    return;
                }

                const checkbox = card.querySelector('.tool-checkbox');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    updateBorrowButton();
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('peminjam.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/peminjam/tools.blade.php ENDPATH**/ ?>