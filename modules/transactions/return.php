<?php
require_once '../../config/database.php';
include '../../includes/header.php';

// Get issued books
$issued_books = mysqli_query($conn, "
    SELECT t.id, t.transaction_id, t.due_date,
           m.member_id, m.full_name,
           b.book_id, b.title
    FROM transactions t
    JOIN library_members m ON t.member_id = m.id
    JOIN books b ON t.book_id = b.id
    WHERE t.transaction_type = 'Issue' AND t.status = 'Issued'
    ORDER BY t.transaction_date DESC
");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_id = $_POST['transaction_id'];
    $return_date = $_POST['return_date'];

    // Update transaction
    $query = "UPDATE transactions SET return_date = '$return_date', status = 'Returned' WHERE id = $transaction_id";

    if(mysqli_query($conn, $query)) {
        $success_message = "Book returned successfully!";
        header("Location: index.php?success=1");
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Return Book</h1>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Transactions
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Issued Book *</label>
                            <select name="transaction_id" class="form-control" required>
                                <option value="">Choose Transaction...</option>
                                <?php while($transaction = mysqli_fetch_assoc($issued_books)): ?>
                                <option value="<?php echo $transaction['id']; ?>">
                                    <?php echo $transaction['transaction_id'] . ' - ' .
                                             $transaction['full_name'] . ' - ' .
                                             $transaction['title']; ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Return Date *</label>
                            <input type="date" name="return_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-undo-alt"></i> Return Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>