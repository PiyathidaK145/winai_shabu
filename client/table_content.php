<table class="table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th class="sortable" data-column="order_number">Order Number ⬆⬇</th>
            <th class="sortable" data-column="table_numbers">Table ⬆⬇</th>
            <th>Menu</th>
            <th>Quantity</th>
            <th class="sortable" data-column="order_date">Order Date ⬆⬇</th>
            <th class="sortable" data-column="status">Status ⬆⬇</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['order_id']) ?></td>
                <td><?= htmlspecialchars($row['order_number']) ?></td>
                <td><?= htmlspecialchars($row['table_numbers'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['menu_item'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['order_date']) ?></td>
                <td>
                    <select class="status-dropdown" data-order-id="<?= $row['order_id'] ?>">
                        <option value="in_progress" <?= $row['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="complete" <?= $row['status'] === 'complete' ? 'selected' : '' ?>>Complete</option>
                    </select>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
