<?php
require_once '../../config/database.php';
include '../../includes/header.php';

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch transaction data with joins
$query = "SELECT t.*,
          m.member_id, m.first_name, m.last_name, m.email, m.phone,
          b.book_id, b.title, b.author, b.isbn, b.category
          FROM transactions t
          JOIN library_members m ON t.member_id = m.id
          JOIN books b ON t.book_id = b.id
          WHERE t.id = $id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$transaction = mysqli_fetch_assoc($result);

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_date = $_POST['transaction_date'];
    $due_date = !empty($_POST['due_date']) ? "'" . $_POST['due_date'] . "'" : "NULL";
    $return_date = !empty($_POST['return_date']) ? "'" . $_POST['return_date'] . "'" : "NULL";
    $status = $_POST['status'];

    // Validate dates
    $errors = [];

    if($transaction['transaction_type'] == 'Issue') {
        if(empty($_POST['due_date'])) {
            $errors[] = "Due date is required for issue transactions";
        }
    }

    if($transaction['transaction_type'] == 'Return') {
        if(empty($_POST['return_date'])) {
            $errors[] = "Return date is required for return transactions";
        }
    }

    if(!empty($_POST['return_date']) && !empty($_POST['transaction_date'])) {
        if(strtotime($_POST['return_date']) < strtotime($_POST['transaction_date'])) {
            $errors[] = "Return date cannot be before transaction date";
        }
    }

    if(empty($errors)) {
        $update_query = "UPDATE transactions SET
                        transaction_date = '$transaction_date',
                        due_date = $due_date,
                        return_date = $return_date,
                        status = '$status'
                        WHERE id = $id";

        if(mysqli_query($conn, $update_query)) {
            $success_message = "Transaction updated successfully!";
            // Refresh transaction data
            $result = mysqli_query($conn, $query);
            $transaction = mysqli_fetch_assoc($result);
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    } else {
        $error_message = implode("<br>", $errors);
    }
}

// Calculate fine if applicable
$fine = 0;
if($transaction['status'] == 'Overdue' || ($transaction['status'] == 'Issued' && strtotime($transaction['due_date']) < time())) {
    $due = new DateTime($transaction['due_date']);
    $now = new DateTime();
    $diff = $due->diff($now);
    $days_overdue = $diff->days;
    $fine = $days_overdue * 5; // $5 per day
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">
            <i class="fas fa-exchange-alt"></i> Edit Transaction
        </h1>
        <div>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <?php if($transaction['transaction_type'] == 'Issue' && $transaction['status'] == 'Issued'): ?>
            <a href="process_return.php?id=<?php echo $transaction['id']; ?>" class="btn btn-success">
                <i class="fas fa-undo-alt"></i> Process Return
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Transaction Details
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="" id="transactionForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Transaction ID</label>
                                    <input type="text" class="form-control" value="<?php echo $transaction['transaction_id']; ?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Transaction Type</label>
                                    <input type="text" class="form-control" value="<?php echo $transaction['transaction_type']; ?>" disabled>
                                    <small class="text-muted">Transaction type cannot be changed</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Member</label>
                                    <input type="text" class="form-control"
                                           value="<?php echo $transaction['member_id'] . ' - ' . $transaction['first_name'] . ' ' . $transaction['last_name']; ?>"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Book</label>
                                    <input type="text" class="form-control"
                                           value="<?php echo $transaction['book_id'] . ' - ' . $transaction['title']; ?>"
                                           disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Transaction Date <span class="text-danger">*</span></label>
                                    <input type="date" name="transaction_date" class="form-control"
                                           value="<?php echo $transaction['transaction_date']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Due Date <?php echo $transaction['transaction_type'] == 'Issue' ? '<span class="text-danger">*</span>' : ''; ?></label>
                                    <input type="date" name="due_date" class="form-control"
                                           value="<?php echo $transaction['due_date']; ?>"
                                           <?php echo $transaction['transaction_type'] == 'Issue' ? 'required' : ''; ?>>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Return Date <?php echo $transaction['transaction_type'] == 'Return' ? '<span class="text-danger">*</span>' : ''; ?></label>
                                    <input type="date" name="return_date" class="form-control"
                                           value="<?php echo $transaction['return_date']; ?>"
                                           <?php echo $transaction['transaction_type'] == 'Return' ? 'required' : ''; ?>>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label