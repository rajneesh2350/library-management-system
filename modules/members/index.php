<?php
require_once '../../config/database.php';
include '../../includes/header.php';

$members_query = "SELECT * FROM library_members ORDER BY created_at DESC";
$members = mysqli_query($conn, $members_query);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Library Members</h1>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Member
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Member ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Membership Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($member = mysqli_fetch_assoc($members)): ?>
                        <tr>
                            <td><?php echo $member['member_id']; ?></td>
                            <td><?php echo $member['full_name']; ?></td>
                            <td><?php echo $member['email']; ?></td>
                            <td><?php echo $member['phone']; ?></td>
                            <td>
                                <span class="badge badge-<?php
                                    echo $member['membership_type'] == 'VIP' ? 'warning' :
                                        ($member['membership_type'] == 'Premium' ? 'info' : 'secondary');
                                ?>">
                                    <?php echo $member['membership_type']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php
                                    echo $member['status'] == 'Active' ? 'success' :
                                        ($member['status'] == 'Inactive' ? 'secondary' : 'danger');
                                ?>">
                                    <?php echo $member['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete('delete.php?id=<?php echo $member['id']; ?>', '<?php echo $member['full_name']; ?>')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
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