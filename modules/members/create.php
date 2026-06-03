<?php
require_once '../../config/database.php';
include '../../includes/header.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $full_name = $first_name . ' ' . $last_name;
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $membership_type = $_POST['membership_type'];
        $membership_start_date = $_POST['membership_start_date'];
        $status = $_POST['status'];

        // Generate member_id
        $result = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(member_id, 9) AS UNSIGNED)) as max_id FROM library_members");
        $row = mysqli_fetch_assoc($result);
        $next_id = str_pad(($row['max_id'] + 1), 5, '0', STR_PAD_LEFT);
        $member_id = "LIB-MEM-" . $next_id;

        $query = "INSERT INTO library_members (member_id, first_name, last_name, full_name, email, phone, membership_type, membership_start_date, status)
                            VALUES ('$member_id', '$first_name', '$last_name', '$full_name', '$email', '$phone', '$membership_type', '$membership_start_date', '$status')";

        if(mysqli_query($conn, $query)) {
                $success_message = "Member created successfully! Member ID: " . $member_id;
                header("Location: index.php?success=1");
                exit();
        } else {
                $error_message = "Error: " . mysqli_error($conn);
        }
}
?>

<div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800">Add New Member</h1>
                <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                </a>
        </div>

        <div class="card">
                <div class="card-body">
                        <form method="POST" action="" id="memberForm">
                                <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>First Name *</label>
                                                        <input type="text" name="first_name" class="form-control" required>
                                                </div>
                                        </div>
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>Last Name *</label>
                                                        <input type="text" name="last_name" class="form-control" required>
                                                </div>
                                        </div>
                                </div>

                                <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>Email *</label>
                                                        <input type="email" name="email" class="form-control" required>
                                                </div>
                                        </div>
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" name="phone" class="form-control">
                                                </div>
                                        </div>
                                </div>

                                <div class="row">
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                        <label>Membership Type *</label>
                                                        <select name="membership_type" class="form-control" required>
                                                                <option value="Standard">Standard</option>
                                                                <option value="Premium">Premium</option>
                                                                <option value="VIP">VIP</option>
                                                        </select>
                                                </div>
                                        </div>
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                        <label>Membership Start Date *</label>
                                                        <input type="date" name="membership_start_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                                </div>
                                        </div>
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                        <label>Status *</label>
                                                        <select name="status" class="form-control" required>
                                                                <option value="Active">Active</option>
                                                                <option value="Inactive">Inactive</option>
                                                                <option value="Suspended">Suspended</option>
                                                        </select>
                                                </div>
                                        </div>
                                </div>

                                <div class="text-right">
                                        <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Create Member
                                        </button>
                                </div>
                        </form>
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
        });
});
</script>

<?php include '../../includes/footer.php'; ?>