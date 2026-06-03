<?php
require_once '../../config/database.php';
include '../../includes/header.php';

// Get active members
$members = mysqli_query($conn, "SELECT id, member_id, full_name FROM library_members WHERE status = 'Active' ORDER BY full_name");

// Get available books
$books = mysqli_query($conn, "SELECT id, book_id, title, author, available_copies FROM books WHERE available_copies > 0 AND status = 'Available' ORDER BY title");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $member_id = $_POST['member_id'];
        $book_id = $_POST['book_id'];
        $transaction_date = $_POST['transaction_date'];
        $due_date = $_POST['due_date'];

        // Generate transaction_id
        $result = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(transaction_id, 5) AS UNSIGNED)) as max_id FROM transactions");
        $row = mysqli_fetch_assoc($result);
        $next_id = str_pad(($row['max_id'] + 1), 5, '0', STR_PAD_LEFT);
        $transaction_id = "TXN-" . $next_id;

        $query = "INSERT INTO transactions (transaction_id, member_id, book_id, transaction_type, transaction_date, due_date, status)
                            VALUES ('$transaction_id', '$member_id', '$book_id', 'Issue', '$transaction_date', '$due_date', 'Issued')";

        if(mysqli_query($conn, $query)) {
                $success_message = "Book issued successfully! Transaction ID: " . $transaction_id;
                header("Location: index.php?success=1");
                exit();
        } else {
                $error_message = "Error: " . mysqli_error($conn);
        }
}
?>

<div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800">Issue Book</h1>
                <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Transactions
                </a>
        </div>

        <div class="card">
                <div class="card-body">
                        <form method="POST" action="" id="issueForm">
                                <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>Select Member *</label>
                                                        <select name="member_id" class="form-control" required>
                                                                <option value="">Choose Member...</option>
                                                                <?php while($member = mysqli_fetch_assoc($members)): ?>
                                                                <option value="<?php echo $member['id']; ?>">
                                                                        <?php echo $member['member_id'] . ' - ' . $member['full_name']; ?>
                                                                </option>
                                                                <?php endwhile; ?>
                                                        </select>
                                                </div>
                                        </div>
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>Select Book *</label>
                                                        <select name="book_id" class="form-control" required>
                                                                <option value="">Choose Book...</option>
                                                                <?php while($book = mysqli_fetch_assoc($books)): ?>
                                                                <option value="<?php echo $book['id']; ?>" data-available="<?php echo $book['available_copies']; ?>">
                                                                        <?php echo $book['book_id'] . ' - ' . $book['title'] . ' (' . $book['available_copies'] . ' available)'; ?>
                                                                </option>
                                                                <?php endwhile; ?>
                                                        </select>
                                                </div>
                                        </div>
                                </div>

                                <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>Transaction Date *</label>
                                                        <input type="date" name="transaction_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                                </div>
                                        </div>
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>Due Date *</label>
                                                        <input type="date" name="due_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" required>
                                                </div>
                                        </div>
                                </div>

                                <div class="text-right">
                                        <button type="submit" class="btn btn-success">
                                                <i class="fas fa-hand-holding-heart"></i> Issue Book
                                        </button>
                                </div>
                        </form>
                </div>
        </div>
</div>

<script>
$(document).ready(function() {
        $('#issueForm').on('submit', function(e) {
                var dueDate = new Date($('input[name="due_date"]').val());
                var transDate = new Date($('input[name="transaction_date"]').val());

                if(dueDate < transDate) {
                        e.preventDefault();
                        showError('Due date cannot be before transaction date');
                }
        });
});
</script>

<?php include '../../includes/footer.php'; ?>