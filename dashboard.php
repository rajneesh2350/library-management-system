<?php
require_once 'config/database.php';
include 'includes/header.php';

// Get statistics
$stats = [];

// Total members
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM library_members WHERE status = 'Active'");
$stats['members'] = mysqli_fetch_assoc($result)['count'];

// Total books
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM books");
$stats['books'] = mysqli_fetch_assoc($result)['count'];

// Books issued
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM transactions WHERE transaction_type = 'Issue' AND status = 'Issued'");
$stats['issued'] = mysqli_fetch_assoc($result)['count'];

// Books returned this month
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM transactions WHERE transaction_type = 'Return' AND MONTH(transaction_date) = MONTH(CURDATE())");
$stats['returned'] = mysqli_fetch_assoc($result)['count'];

// Recent transactions
$recent_transactions = mysqli_query($conn, "
    SELECT t.*, m.first_name, m.last_name, b.title
    FROM transactions t
    JOIN library_members m ON t.member_id = m.id
    JOIN books b ON t.book_id = b.id
    ORDER BY t.created_at DESC LIMIT 5
");
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small font-weight-bold text-gray-800">Total Members</div>
                            <div class="h2 font-weight-bold text-gray-800"><?php echo $stats['members']; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small font-weight-bold text-gray-800">Total Books</div>
                            <div class="h2 font-weight-bold text-gray-800"><?php echo $stats['books']; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small font-weight-bold text-gray-800">Books Issued</div>
                            <div class="h2 font-weight-bold text-gray-800"><?php echo $stats['issued']; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-hand-holding-heart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-card-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small font-weight-bold text-gray-800">Returned (This Month)</div>
                            <div class="h2 font-weight-bold text-gray-800"><?php echo $stats['returned']; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-undo-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 font-weight-bold text-primary">Recent Transactions</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Member</th>
                                    <th>Book</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($recent_transactions)): ?>
                                <tr>
                                    <td><?php echo $row['transaction_id']; ?></td>
                                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                    <td><?php echo $row['title']; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $row['transaction_type'] == 'Issue' ? 'warning' : 'success'; ?>">
                                            <?php echo $row['transaction_type']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($row['transaction_date'])); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $row['status'] == 'Issued' ? 'warning' : 'success'; ?>">
                                            <?php echo $row['status']; ?>
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
    </div>
</div>

<?php include 'includes/footer.php'; ?>