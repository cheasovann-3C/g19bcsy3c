<?php
require_once 'init/init.php';

$userId = $_SESSION['user_id'];
$message = '';
$messageType = '';


// PHOTO UPLOAD

if(isset($_POST['uploadPhoto'])){

    if(empty($_FILES['photo']['name'])){
        $message = "Please select a photo to upload.";
        $messageType = "danger";
    } else {

        $file = $_FILES['photo'];
        $allowed = ['image/jpeg','image/png'];

        if(!in_array($file['type'],$allowed)){
            $message = "Only JPG and PNG are allowed.";
            $messageType = "danger";
        } else {

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = "assets/images/" . uniqid("PI-") . "." . $ext;

            move_uploaded_file($file['tmp_name'],$newName);

            $old = $db->query("SELECT photo FROM tbl_users WHERE id = $userId")->fetch_assoc();
            if(!empty($old['photo']) && file_exists($old['photo'])){
                unlink($old['photo']);
            }

            $stmt = $db->prepare("UPDATE tbl_users SET photo=? WHERE id=?");
            $stmt->bind_param("si",$newName,$userId);
            $stmt->execute();

            $message = "Photo uploaded successfully.";
            $messageType = "success";
        }
    }
}



// DELETE PHOTO

if(isset($_POST['deletePhoto'])){

    $old = $db->query("SELECT photo FROM tbl_users WHERE id = $userId")->fetch_assoc();

    if(empty($old['photo'])){
        $message = "No photo available to delete.";
        $messageType = "warning";
    } else {

        if(file_exists($old['photo'])){
            unlink($old['photo']);
        }

        $stmt = $db->prepare("UPDATE tbl_users SET photo=NULL WHERE id=?");
        $stmt->bind_param("i",$userId);
        $stmt->execute();

        $message = "Photo deleted successfully.";
        $messageType = "success";
    }
}


// ==========================
// PASSWORD CHANGE 
// ==========================

$oldPasswd = $newPasswd = $confirmNewPasswd = '';
$oldPasswdErr = $newPasswdErr  = '';

if (isset($_POST['changePasswd'], $_POST['oldPasswd'], $_POST['newPasswd'], $_POST['confirmNewPasswd'])) {
    $oldPasswd = trim($_POST['oldPasswd']);
    $newPasswd = trim($_POST['newPasswd']);
    $confirmNewPasswd = trim($_POST['confirmNewPasswd']);
    if (empty($oldPasswd)) {
        $oldPasswdErr = 'please input your old password';
    }
    if (empty($newPasswd)) {
        $newPasswdErr = 'please input your new password';
    }
    if ($newPasswd !== $confirmNewPasswd) {
        $newPasswdErr = 'password does not match';
    }
     if (!isUserHasPassword($oldPasswd)) {
        $oldPasswdErr = 'password is incorrect';
    }
    if (empty($oldPasswdErr) && empty($newPasswdErr)) {
        if (setUserNewPassowrd($newPasswd)) {
            header('Location: ./?page=logout');
            exit;
        } else {
            echo '<div class="alert alert-danger">Try again.</div>';
        }
    }
}

// Get current photo
$currentUser = $db->query("SELECT photo FROM tbl_users WHERE id = $userId")->fetch_assoc();
$photoPath = !empty($currentUser['photo']) ? $currentUser['photo'] : './assets/images/emptyuser.png';
?>

<?php if(!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
    <?php echo $message; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-6">

        <form id="photoForm" method="post" action="./?page=profile" enctype="multipart/form-data">

            <div class="d-flex justify-content-center">
                <input name="photo" type="file" id="profileUpload" hidden accept="image/png, image/jpeg,">
                <label role="button" for="profileUpload">
                    <img src="<?php echo $photoPath; ?>" class="rounded" width="150" id="previewImage">
                </label>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <button type="button" class="btn btn-danger me-2"
                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                        Delete
                </button>

                <button type="submit" name="uploadPhoto" class="btn btn-success">
                        Upload
                </button>
            </div>

        </form>
    </div>

    <div class="col-6">
        <form method="post" action="./?page=profile" class="col-md-8 col-lg-6 mx-auto">
            <h3>Change Password</h3>

            <div class="mb-3">
                <label class="form-label">Old Password</label>
                <input value="<?php echo $oldPasswd ?>" name="oldPasswd" type="password"
                       class="form-control <?php echo empty($oldPasswdErr) ? '' : 'is-invalid' ?>">
                <div class="invalid-feedback">
                    <?php echo $oldPasswdErr ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input name="newPasswd" type="password"
                       class="form-control <?php echo empty($newPasswdErr) ? '' : 'is-invalid' ?>">
                <div class="invalid-feedback">
                    <?php echo $newPasswdErr ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input name="confirmNewPasswd" type="password" class="form-control">
            </div>

            <button type="submit" name="changePasswd" class="btn btn-primary">
                Change Password
            </button>
        </form>
    </div>
</div>

<!-- DELETE CONFIRM MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this photo?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="photoForm" name="deletePhoto" class="btn btn-danger">
            Yes, Delete
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById("profileUpload").addEventListener("change", function(e){
    const file = e.target.files[0];

    if(!file){
        return;
    }

    if(!["image/jpeg","image/png"].includes(file.type)){
        alert("Only JPG and PNG allowed!");
        e.target.value = "";
        return;
    }

    const reader = new FileReader();
    reader.onload = function(event){
        document.getElementById("previewImage").src = event.target.result;
    };
    reader.readAsDataURL(file);
});
</script>