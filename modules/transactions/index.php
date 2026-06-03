<?php
require_once '../../config/database.php';
include '../../includes/header.php';

$transactions = mysqli_query($conn, "
    SELECT t.*, m.first_name, m.last_name, m.member_id, b.title, b.book_id
    FROM transactions t
    JOIN library_members m ON t.member_id = m.id
    JOIN books b ON t.book_id = b.id
    ORDER BY t.created_at DESC
");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Transaction History</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Member</th>
                            <th>Book</th>
                            <th>Type</th>
                            <th>Transaction Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($trans = mysqli_fetch_assoc($transactions)): ?>
                        <tr>
                            <td><?php echo $trans['transaction_id']; ?></td>
                            <td><?php echo $trans['first_name'] . ' ' . $trans['last_name']; ?></td>
                            <td><?php echo $trans['title']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $trans['transaction_type'] == 'Issue' ? 'warning' : 'success'; ?>">
                                    <?php echo $trans['transaction_type']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y', strtotime($trans['transaction_date'])); ?></td>
                            <td><?php echo $trans['due_date'] ? date('d M Y', strtotime($trans['due_date'])) : '-'; ?></td>
                            <td><?php echo $trans['return_date'] ? date('d M Y', strtotime($trans['return_date'])) : '-'; ?></td>
                            <td>
                                <span class="badge badge-<?php
                                    echo $trans['status'] == 'Issued' ? 'warning' :
                                        ($trans['status'] == 'Returned' ? 'success' : 'danger');
                                ?>">
                                    <?php echo $trans['status']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>