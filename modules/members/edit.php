<?php
require_once '../../config/database.php';
include '../../includes/header.php';

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: index.php");
        exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch member data
$query = "SELECT * FROM library_members WHERE id = $id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
        header("Location: index.php");
        exit();
}

$member = mysqli_fetch_assoc($result);

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $full_name = $first_name . ' ' . $last_name;
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $membership_type = $_POST['membership_type'];
        $membership_start_date = $_POST['membership_start_date'];
        $membership_end_date = !empty($_POST['membership_end_date']) ? "'" . $_POST['membership_end_date'] . "'" : "NULL";
        $status = $_POST['status'];

        // Check if email already exists for other members
        $check_email = "SELECT id FROM library_members WHERE email = '$email' AND id != $id";
        $email_result = mysqli_query($conn, $check_email);

        if(mysqli_num_rows($email_result) > 0) {
                $error_message = "Email already exists! Please use a different email address.";
        } else {
                $update_query = "UPDATE library_members SET
                                                first_name = '$first_name',
                                                last_name = '$last_name',
                                                full_name = '$full_name',
                                                email = '$email',
                                                phone = '$phone',
                                                membership_type = '$membership_type',
                                                membership_start_date = '$membership_start_date',
                                                membership_end_date = $membership_end_date,
                                                status = '$status'
                                                WHERE id = $id";

                if(mysqli_query($conn, $update_query)) {
                        $success_message = "Member updated successfully!";
                        // Refresh member data
                        $result = mysqli_query($conn, "SELECT * FROM library_members WHERE id = $id");
                        $member = mysqli_fetch_assoc($result);
                } else {
                        $error_message = "Error: " . mysqli_error($conn);
                }
        }
}
?>

<div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800">
                        <i class="fas fa-user-edit"></i> Edit Member
                </h1>
                <div>
                        <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="view.php?id=<?php echo $member['id']; ?>" class="btn btn-info">
                                <i class="fas fa-eye"></i> View Details
                        </a>
                </div>
        </div>

        <div class="row">
                <div class="col-md-8">
                        <div class="card">
                                <div class="card-header">
                                        <h6 class="mb-0 font-weight-bold text-primary">
                                                <i class="fas fa-info-circle"></i> Member Information
                                        </h6>
                                </div>
                                <div class="card-body">
                                        <form method="POST" action="" id="memberForm">
                                                <div class="row">
                                                        <div class="col-md-6">
                                                                <div class="form-group">
                                                                        <label>Member ID <span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" value="<?php echo $member['member_id']; ?>" disabled>
                                                                        <small class="text-muted">Auto-generated, cannot be edited</small>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                                <div class="form-group">
                                                                        <label>Status <span class="text-danger">*</span></label>
                                                                        <select name="status" class="form-control" required>
                                                                                <option value="Active" <?php echo $member['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                                                                <option value="Inactive" <?php echo $member['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                                                <option value="Suspended" <?php echo $member['status'] == 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="row">
                                                        <div class="col-md-6">
                                                                <div class="form-group">
                                                                        <label>First Name <span class="text-danger">*</span></label>
                                                                        <input type="text" name="first_name" class="form-control" value="<?php echo $member['first_name']; ?>" required>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                                <div class="form-group">
                                                                        <label>Last Name <span class="text-danger">*</span></label>
                                                                        <input type="text" name="last_name" class="form-control" value="<?php echo $member['last_name']; ?>" required>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="row">
                                                        <div class="col-md-6">
                                                                <div class="form-group">
                                                                        <label>Full Name</label>
                                                                        <input type="text" class="form-control" value="<?php echo $member['full_name']; ?>" disabled>
                                                                        <small class="text-muted">Auto-generated from first and last name</small>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                                <div class="form-group">
                                                                        <label>Phone Number</label>
                                                                        <input type="text" name="phone" class="form-control" value="<?php echo $member['phone']; ?>">
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="form-group">
                                                        <label>Email Address <span class="text-danger">*</span></label>
                                                        <input type="email" name="email" class="form-control" value="<?php echo $member['email']; ?>" required>
                                                        <small class="text-muted">Must be unique across all members</small>
                                                </div>

                                                <div class="row">
                                                        <div class="col-md-4">
                                                                <div class="form-group">
                                                                        <label>Membership Type <span class="text-danger">*</span></label>
                                                                        <select name="membership_type" class="form-control" required>
                                                                                <option value="Standard" <?php echo $member['membership_type'] == 'Standard' ? 'selected' : ''; ?>>Standard</option>
                                                                                <option value="Premium" <?php echo $member['membership_type'] == 'Premium' ? 'selected' : ''; ?>>Premium</option>
                                                                                <option value="VIP" <?php echo $member['membership_type'] == 'VIP' ? 'selected' : ''; ?>>VIP</option>
                                                                        </select>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                                <div class="form-group">
                                                                        <label>Membership Start Date <span class="text-danger">*</span></label>
                                                                        <input type="date" name="membership_start_date" class="form-control" value="<?php echo $member['membership_start_date']; ?>" required>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                                <div class="form-group">
                                                                        <label>Membership End Date</label>
                                                                        <input type="date" name="membership_end_date" class="form-control" value="<?php echo $member['membership_end_date']; ?>">
                                                                        <small class="text-muted">Leave empty if no expiration</small>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="alert alert-info mt-3">
                                                        <i class="fas fa-clock"></i>
                                                        <strong>Member since:</strong> <?php echo date('F d, Y', strtotime($member['created_at'])); ?>
                                                        <br>
                                                        <strong>Last updated:</strong> <?php echo date('F d, Y H:i:s', strtotime($member['updated_at'])); ?>
                                                </div>

                                                <div class="text-right">
                                                        <a href="index.php" class="btn btn-secondary">
                                                                <i class="fas fa-times"></i> Cancel
                                                        </a>
                                                        <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-save"></i> Update Member
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
                                                <i class="fas fa-chart-line"></i> Member Statistics
                                        </h6>
                                </div>
                                <div class="card-body">
                                        <?php
                                        // Get member statistics
                                        $stats_query = "SELECT
                                                                        COUNT(*) as total_books_issued,
                                                                        SUM(CASE WHEN status = 'Issued' THEN 1 ELSE 0 END) as currently_issued
                                                                    FROM transactions
                                                                    WHERE member_id = $id AND transaction_type = 'Issue'";
                                        $stats_result = mysqli_query($conn, $stats_query);
                                        $stats = mysqli_fetch_assoc($stats_result);
                                        ?>

                                        <div class="mb-3">
                                                <small class="text-muted">Total Books Issued</small>
                                                <h3 class="mb-0"><?php echo $stats['total_books_issued'] ?? 0; ?></h3>
                                        </div>

                                        <div class="mb-3">
                                                <small class="text-muted">Currently Issued</small>
                                                <h3 class="mb-0 text-warning"><?php echo $stats['currently_issued'] ?? 0; ?></h3>
                                        </div>

                                        <hr>

                                        <div class="mb-3">
                                                <small class="text-muted">Membership Duration</small>
                                                <?php
                                                $start = new DateTime($member['membership_start_date']);
                                                $end = $member['membership_end_date'] ? new DateTime($member['membership_end_date']) : new DateTime();
                                                $diff = $start->diff($end);
                                                ?>
                                                <h5><?php echo $diff->y . ' years, ' . $diff->m . ' months'; ?></h5>
                                        </div>
                                </div>
                        </div>

                        <div class="card mt-3">
                                <div class="card-header">
                                        <h6 class="mb-0 font-weight-bold text-primary">
                                                <i class="fas fa-book"></i> Currently Issued Books
                                        </h6>
                                </div>
                                <div class="card-body">
                                        <?php
                                        $issued_query = "SELECT t.*, b.title, b.book_id, b.author
                                                                        FROM transactions t
                                                                        JOIN books b ON t.book_id = b.id
                                                                        WHERE t.member_id = $id
                                                                        AND t.transaction_type = 'Issue'
                                                                        AND t.status = 'Issued'
                                                                        ORDER BY t.due_date ASC";
                                        $issued_result = mysqli_query($conn, $issued_query);

                                        if(mysqli_num_rows($issued_result) > 0):
                                        ?>
                                        <div class="list-group">
                                                <?php while($issued = mysqli_fetch_assoc($issued_result)): ?>
                                                <div class="list-group-item">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                        <strong><?php echo $issued['title']; ?></strong>
                                                                        <br>
                                                                        <small class="text-muted">Due: <?php echo date('d M Y', strtotime($issued['due_date'])); ?></small>
                                                                </div>
                                                                <span class="badge badge-warning">Issued</span>
                                                        </div>
                                                </div>
                                                <?php endwhile; ?>
                                        </div>
                                        <?php else: ?>
                                        <p class="text-muted text-center mb-0">No books currently issued</p>
                                        <?php endif; ?>
                                </div>
                        </div>
                </div>
        </div>
</div>

<script>
$(document).ready(function() {
        $('#memberForm').on('submit', function(e) {
                var email = $('input[name="email"]').val();
                if(!validateEmail(email)) {
                        e.preventDefault();
                        showError('Please enter a valid email address');
                }

                var startDate = new Date($('input[name="membership_start_date"]').val());
                var endDate = $('input[name="membership_end_date"]').val();

                if(endDate) {
                        endDate = new Date(endDate);
                        if(endDate < startDate) {
                                e.preventDefault();
                                showError('Membership end date cannot be before start date');
                        }
                }
        });
});
</script>

<?php include '../../includes/footer.php'; ?>