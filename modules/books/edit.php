<?php
require_once '../../config/database.php';
include '../../includes/header.php';

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch book data
$query = "SELECT * FROM books WHERE id = $id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$book = mysqli_fetch_assoc($result);

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
    $publication_year = !empty($_POST['publication_year']) ? $_POST['publication_year'] : "NULL";
    $category = $_POST['category'];
    $total_copies = $_POST['total_copies'];

    // Check if ISBN already exists for other books
    if(!empty($isbn)) {
        $check_isbn = "SELECT id FROM books WHERE isbn = '$isbn' AND id != $id";
        $isbn_result = mysqli_query($conn, $check_isbn);

        if(mysqli_num_rows($isbn_result) > 0) {
            $error_message = "ISBN already exists! Please use a different ISBN.";
        }
    }

    if(!isset($error_message)) {
        // Calculate available copies adjustment
        $copy_difference = $total_copies - $book['total_copies'];
        $new_available = $book['available_copies'] + $copy_difference;

        // Ensure available copies doesn't go negative
        if($new_available < 0) {
            $error_message = "Cannot reduce total copies below currently issued copies!";
        } else {
            $status = ($new_available > 0) ? 'Available' : 'Checked Out';
            if($total_copies == 0) $status = 'Maintenance';

            $update_query = "UPDATE books SET
                            title = '$title',
                            author = '$author',
                            isbn = " . ($isbn ? "'$isbn'" : "NULL") . ",
                            publisher = " . ($publisher ? "'$publisher'" : "NULL") . ",
                            publication_year = $publication_year,
                            category = '$category',
                            total_copies = $total_copies,
                            available_copies = $new_available,
                            status = '$status'
                            WHERE id = $id";

            if(mysqli_query($conn, $update_query)) {
                $success_message = "Book updated successfully!";
                // Refresh book data
                $result = mysqli_query($conn, "SELECT * FROM books WHERE id = $id");
                $book = mysqli_fetch_assoc($result);
            } else {
                $error_message = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">
            <i class="fas fa-book"></i> Edit Book
        </h1>
        <div>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="view.php?id=<?php echo $book['id']; ?>" class="btn btn-info">
                <i class="fas fa-eye"></i> View Details
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-edit"></i> Book Information
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="" id="bookForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Book ID</label>
                                    <input type="text" class="form-control" value="<?php echo $book['book_id']; ?>" disabled>
                                    <small class="text-muted">Auto-generated, cannot be edited</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <input type="text" class="form-control" value="<?php echo $book['status']; ?>" disabled>
                                    <small class="text-muted">Auto-updated based on availability</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Book Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="<?php echo $book['title']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Author <span class="text-danger">*</span></label>
                            <input type="text" name="author" class="form-control" value="<?php echo $book['author']; ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ISBN</label>
                                    <input type="text" name="isbn" class="form-control" value="<?php echo $book['isbn']; ?>">
                                    <small class="text-muted">Must be unique if provided</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Publisher</label>
                                    <input type="text" name="publisher" class="form-control" value="<?php echo $book['publisher']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Publication Year</label>
                                    <input type="number" name="publication_year" class="form-control"
                                           value="<?php echo $book['publication_year']; ?>"
                                           min="1000" max="<?php echo date('Y'); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control" required>
                                        <option value="Fiction" <?php echo $book['category'] == 'Fiction' ? 'selected' : ''; ?>>Fiction</option>
                                        <option value="Non-Fiction" <?php echo $book['category'] == 'Non-Fiction' ? 'selected' : ''; ?>>Non-Fiction</option>
                                        <option value="Science" <?php echo $book['category'] == 'Science' ? 'selected' : ''; ?>>Science</option>
                                        <option value="Technology" <?php echo $book['category'] == 'Technology' ? 'selected' : ''; ?>>Technology</option>
                                        <option value="History" <?php echo $book['category'] == 'History' ? 'selected' : ''; ?>>History</option>
                                        <option value="Biography" <?php echo $book['category'] == 'Biography' ? 'selected' : ''; ?>>Biography</option>
                                        <option value="Other" <?php echo $book['category'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total Copies <span class="text-danger">*</span></label>
                                    <input type="number" name="total_copies" id="total_copies" class="form-control"
                                           value="<?php echo $book['total_copies']; ?>" min="0" required>
                                    <small class="text-muted" id="copy_warning"></small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <i class="fas fa-chart-line"></i>
                                    <strong>Current Statistics:</strong><br>
                                    Total Copies: <?php echo $book['total_copies']; ?><br>
                                    Available Copies: <span id="current_available"><?php echo $book['available_copies']; ?></span>
                                </div>
                                <div class="col-md-6">
                                    <i class="fas fa-clock"></i>
                                    <strong>Timestamps:</strong><br>
                                    Added: <?php echo date('d M Y', strtotime($book['created_at'])); ?><br>
                                    Last Updated: <?php echo date('d M Y H:i:s', strtotime($book['updated_at'])); ?>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Book
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie"></i> Inventory Status
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="inventoryChart" height="200"></canvas>
                    <hr>
                    <div class="text-center">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Total Copies</small>
                                <h4 class="mb-0"><?php echo $book['total_copies']; ?></h4>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Issued Copies</small>
                                <h4 class="mb-0 text-warning"><?php echo $book['total_copies'] - $book['available_copies']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Transaction History
                    </h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <?php
                    $trans_query = "SELECT t.*, m.first_name, m.last_name, m.member_id
                                   FROM transactions t
                                   JOIN library_members m ON t.member_id = m.id
                                   WHERE t.book_id = $id
                                   ORDER BY t.created_at DESC LIMIT 10";
                    $trans_result = mysqli_query($conn, $trans_query);

                    if(mysqli_num_rows($trans_result) > 0):
                    ?>
                    <div class="timeline">
                        <?php while($trans = mysqli_fetch_assoc($trans_result)): ?>
                        <div class="mb-3 pb-2 border-bottom">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted"><?php echo date('d M Y', strtotime($trans['transaction_date'])); ?></small>
                                <span class="badge badge-<?php echo $trans['transaction_type'] == 'Issue' ? 'warning' : 'success'; ?>">
                                    <?php echo $trans['transaction_type']; ?>
                                </span>
                            </div>
                            <div class="mt-1">
                                <strong><?php echo $trans['first_name'] . ' ' . $trans['last_name']; ?></strong>
                                <br>
                                <small><?php echo $trans['member_id']; ?></small>
                            </div>
                            <?php if($trans['transaction_type'] == 'Issue'): ?>
                            <div class="mt-1">
                                <small>Due: <?php echo date('d M Y', strtotime($trans['due_date'])); ?></small>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">No transactions yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Inventory Chart
    var ctx = document.getElementById('inventoryChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Issued'],
            datasets: [{
                data: [<?php echo $book['available_copies']; ?>, <?php echo $book['total_copies'] - $book['available_copies']; ?>],
                backgroundColor: ['#1cc88a', '#f6c23e'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Validate total copies
    $('#total_copies').on('change', function() {
        var total = parseInt($(this).val());
        var available = <?php echo $book['available_copies']; ?>;
        var issued = <?php echo $book['total_copies'] - $book['available_copies']; ?>;

        if(total < issued) {
            $('#copy_warning').html('<span class="text-danger">⚠️ Cannot reduce below currently issued copies (' + issued + ')</span>');
            $('button[type="submit"]').prop('disabled', true);
        } else {
            $('#copy_warning').html('<span class="text-success">✓ Valid adjustment</span>');
            $('button[type="submit"]').prop('disabled', false);

            // Update chart preview
            var new_available = available + (total - <?php echo $book['total_copies']; ?>);
            chart.data.datasets[0].data = [new_available, issued];
            chart.update();
            $('#current_available').text(new_available);
        }
    });

    $('#bookForm').on('submit', function(e) {
        var total = parseInt($('#total_copies').val());
        var issued = <?php echo $book['total_copies'] - $book['available_copies']; ?>;

        if(total < issued) {
            e.preventDefault();
            showError('Cannot reduce total copies below currently issued copies!');
        }
    });
});
</script>

<?php include '../../includes/footer.php'; ?>