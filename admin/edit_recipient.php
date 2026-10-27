<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");


// =====================================================
// CHECK ID
// =====================================================

if (!isset($_GET['id'])) {
    header("Location: recipients.php");
    exit();
}

$id = intval($_GET['id']);


// =====================================================
// GET RECIPIENT
// =====================================================

$result = mysqli_query(
    $conn,
    "SELECT * FROM recipients WHERE id=$id"
);

$recipient = mysqli_fetch_assoc($result);

if (!$recipient) {
    echo "Recipient not found";
    exit();
}


// =====================================================
// UPDATE RECIPIENT
// =====================================================

if (isset($_POST['update'])) {

    $name = mysqli_real_escape_string(
        $conn,
        $_POST['name']
    );

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $phone = mysqli_real_escape_string(
        $conn,
        $_POST['phone']
    );

    $blood_group = mysqli_real_escape_string(
        $conn,
        $_POST['blood_group']
    );

    $city = mysqli_real_escape_string(
        $conn,
        $_POST['city']
    );


    mysqli_query(
        $conn,

        "UPDATE recipients SET

        name='$name',

        email='$email',

        phone='$phone',

        blood_group='$blood_group',

        city='$city'

        WHERE id=$id"
    );


    header("Location: recipients.php");
    exit();
}


include("../includes/header.php");

?>


<style>

/* =====================================================
   EDIT RECIPIENT PAGE
===================================================== */

.recipient-edit-page {

    min-height: calc(100vh - 80px);

    padding: 45px 20px;

    background:
        radial-gradient(
            circle at 10% 10%,
            rgba(13,110,253,0.08),
            transparent 30%
        ),

        radial-gradient(
            circle at 90% 90%,
            rgba(13,110,253,0.08),
            transparent 30%
        ),

        #f5f8fc;
}


/* =====================================================
   MAIN CARD
===================================================== */

.recipient-edit-card {

    max-width: 1000px;

    margin: auto;

    background: #ffffff;

    border: none;

    border-radius: 25px;

    overflow: hidden;

    box-shadow:
        0 20px 60px rgba(0,0,0,0.10);
}


/* =====================================================
   LEFT PANEL
===================================================== */

.recipient-edit-info {

    height: 100%;

    min-height: 560px;

    padding: 45px 35px;

    position: relative;

    overflow: hidden;

    color: white;

    background:
        linear-gradient(
            145deg,
            #0d6efd,
            #084298
        );
}


.recipient-edit-info::before {

    content: "";

    position: absolute;

    width: 280px;
    height: 280px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.08);

    top: -120px;
    right: -100px;
}


.recipient-edit-info::after {

    content: "";

    position: absolute;

    width: 220px;
    height: 220px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.06);

    bottom: -100px;
    left: -100px;
}


.recipient-edit-info-content {

    position: relative;

    z-index: 2;
}


/* =====================================================
   ICON
===================================================== */

.recipient-edit-icon {

    width: 78px;
    height: 78px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 23px;

    background:
        rgba(255,255,255,0.15);

    border:
        1px solid
        rgba(255,255,255,0.25);

    font-size: 32px;

    margin-bottom: 25px;

    box-shadow:
        0 10px 25px
        rgba(0,0,0,0.10);
}


.recipient-edit-info h2 {

    font-weight: 800;

    line-height: 1.2;

    margin-bottom: 15px;
}


.recipient-edit-info p {

    color:
        rgba(255,255,255,0.82);

    line-height: 1.7;

    font-size: 14px;
}


/* =====================================================
   INFO ITEMS
===================================================== */

.edit-info-list {

    margin-top: 35px;
}


.edit-info-item {

    display: flex;

    align-items: center;

    gap: 13px;

    margin-bottom: 20px;

    font-size: 14px;
}


.edit-info-item i {

    width: 36px;
    height: 36px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background:
        rgba(255,255,255,0.14);

    flex-shrink: 0;
}


/* =====================================================
   RIGHT FORM
===================================================== */

.recipient-edit-form {

    padding: 45px;
}


.edit-form-header {

    margin-bottom: 30px;
}


.edit-form-header h2 {

    font-weight: 800;

    color: #222;

    margin-bottom: 6px;
}


.edit-form-header p {

    color: #888;

    margin: 0;

    font-size: 14px;
}


/* =====================================================
   FORM GROUP
===================================================== */

.edit-form-group {

    position: relative;

    margin-bottom: 21px;
}


.edit-form-group label {

    display: block;

    font-size: 13px;

    font-weight: 700;

    color: #444;

    margin-bottom: 7px;
}


.edit-form-group > i {

    position: absolute;

    left: 15px;

    bottom: 14px;

    color: #0d6efd;

    font-size: 14px;

    z-index: 2;
}


/* =====================================================
   INPUT
===================================================== */

.edit-control {

    width: 100%;

    height: 49px;

    border:
        1px solid #e1e5eb;

    border-radius: 12px;

    padding:
        0 15px 0 42px;

    background: #f9fafc;

    outline: none;

    font-size: 14px;

    color: #333;

    transition:
        all 0.25s ease;
}


.edit-control:focus {

    border-color: #0d6efd;

    background: #fff;

    box-shadow:
        0 0 0 4px
        rgba(13,110,253,0.10);
}


/* =====================================================
   SELECT
===================================================== */

select.edit-control {

    cursor: pointer;
}


/* =====================================================
   ACTION BUTTONS
===================================================== */

.edit-actions {

    display: flex;

    gap: 12px;

    margin-top: 10px;
}


.update-recipient-btn {

    flex: 1;

    height: 52px;

    border: none;

    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #0d6efd,
            #084298
        );

    color: white;

    font-weight: 700;

    font-size: 14px;

    box-shadow:
        0 8px 20px
        rgba(13,110,253,0.25);

    transition:
        all 0.3s ease;
}


.update-recipient-btn:hover {

    transform:
        translateY(-2px);

    box-shadow:
        0 12px 25px
        rgba(13,110,253,0.35);

    color: white;
}


.cancel-recipient-btn {

    height: 52px;

    padding: 0 25px;

    border-radius: 13px;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    background: #f1f3f5;

    color: #555;

    text-decoration: none;

    font-weight: 600;

    transition: all 0.25s ease;
}


.cancel-recipient-btn:hover {

    background: #e2e6ea;

    color: #222;
}


/* =====================================================
   RECIPIENT ID
===================================================== */

.recipient-id-badge {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 7px 12px;

    border-radius: 20px;

    background:
        rgba(13,110,253,0.08);

    color: #0d6efd;

    font-size: 12px;

    font-weight: 700;

    margin-bottom: 15px;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width: 991px) {

    .recipient-edit-info {

        min-height: auto;

        padding: 35px 30px;
    }

    .recipient-edit-form {

        padding: 35px 30px;
    }

}


@media(max-width: 576px) {

    .recipient-edit-page {

        padding:
            25px 10px;
    }

    .recipient-edit-card {

        border-radius: 18px;
    }

    .recipient-edit-info {

        padding:
            30px 22px;
    }

    .recipient-edit-form {

        padding:
            30px 18px;
    }

    .recipient-edit-info h2 {

        font-size: 25px;
    }

    .edit-actions {

        flex-direction: column;
    }

    .cancel-recipient-btn {

        width: 100%;
    }

}

</style>


<div class="recipient-edit-page">

    <div class="container-fluid">

        <div class="recipient-edit-card">

            <div class="row g-0">


                <!-- =================================================
                     LEFT INFORMATION PANEL
                ================================================= -->

                <div class="col-lg-5">

                    <div class="recipient-edit-info">

                        <div class="recipient-edit-info-content">


                            <div class="recipient-edit-icon">

                                <i class="fa-solid fa-user-pen"></i>

                            </div>


                            <div class="recipient-id-badge">

                                <i class="fa-solid fa-id-card"></i>

                                Recipient #<?= (int)$recipient['id']; ?>

                            </div>


                            <h2>

                                Update Recipient
                                <br>

                                Information

                            </h2>


                            <p>

                                Keep recipient information
                                accurate and up to date so
                                donors and hospitals can
                                provide the right assistance
                                when needed.

                            </p>


                            <div class="edit-info-list">


                                <div class="edit-info-item">

                                    <i class="fa-solid fa-user"></i>

                                    <span>
                                        Update personal details
                                    </span>

                                </div>


                                <div class="edit-info-item">

                                    <i class="fa-solid fa-droplet"></i>

                                    <span>
                                        Manage required blood group
                                    </span>

                                </div>


                                <div class="edit-info-item">

                                    <i class="fa-solid fa-phone"></i>

                                    <span>
                                        Keep contact information current
                                    </span>

                                </div>


                                <div class="edit-info-item">

                                    <i class="fa-solid fa-location-dot"></i>

                                    <span>
                                        Update recipient city
                                    </span>

                                </div>


                                <div class="edit-info-item">

                                    <i class="fa-solid fa-shield-heart"></i>

                                    <span>
                                        Information is securely managed
                                    </span>

                                </div>


                            </div>


                        </div>

                    </div>

                </div>



                <!-- =================================================
                     RIGHT FORM
                ================================================= -->

                <div class="col-lg-7">

                    <div class="recipient-edit-form">


                        <div class="edit-form-header">

                            <h2>

                                Edit Recipient

                            </h2>

                            <p>

                                Update the details below and
                                save the changes.

                            </p>

                        </div>



                        <form method="POST">


                            <div class="row">


                                <!-- NAME -->

                                <div class="col-md-6">

                                    <div class="edit-form-group">

                                        <label>
                                            Full Name
                                        </label>

                                        <i class="fa-solid fa-user"></i>

                                        <input
                                            type="text"
                                            name="name"
                                            class="edit-control"
                                            placeholder="Enter full name"
                                            value="<?= htmlspecialchars($recipient['name']); ?>"
                                            required
                                        >

                                    </div>

                                </div>



                                <!-- EMAIL -->

                                <div class="col-md-6">

                                    <div class="edit-form-group">

                                        <label>
                                            Email Address
                                        </label>

                                        <i class="fa-solid fa-envelope"></i>

                                        <input
                                            type="email"
                                            name="email"
                                            class="edit-control"
                                            placeholder="Enter email"
                                            value="<?= htmlspecialchars($recipient['email']); ?>"
                                            required
                                        >

                                    </div>

                                </div>



                                <!-- PHONE -->

                                <div class="col-md-6">

                                    <div class="edit-form-group">

                                        <label>
                                            Phone Number
                                        </label>

                                        <i class="fa-solid fa-phone"></i>

                                        <input
                                            type="text"
                                            name="phone"
                                            class="edit-control"
                                            placeholder="Enter phone number"
                                            maxlength="10"
                                            pattern="[0-9]{10}"
                                            value="<?= htmlspecialchars($recipient['phone']); ?>"
                                            required
                                        >

                                    </div>

                                </div>



                                <!-- BLOOD GROUP -->

                                <div class="col-md-6">

                                    <div class="edit-form-group">

                                        <label>
                                            Blood Group Required
                                        </label>

                                        <i class="fa-solid fa-droplet"></i>

                                        <select
                                            name="blood_group"
                                            class="edit-control"
                                            required
                                        >

                                            <option value="">
                                                Select Blood Group
                                            </option>


                                            <?php

                                            $groups = [
                                                "A+",
                                                "A-",
                                                "B+",
                                                "B-",
                                                "AB+",
                                                "AB-",
                                                "O+",
                                                "O-"
                                            ];


                                            foreach ($groups as $group):

                                            ?>

                                                <option
                                                    value="<?= htmlspecialchars($group); ?>"
                                                    <?= ($recipient['blood_group'] == $group) ? 'selected' : ''; ?>
                                                >

                                                    <?= htmlspecialchars($group); ?>

                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>

                                </div>



                                <!-- CITY -->

                                <div class="col-md-6">

                                    <div class="edit-form-group">

                                        <label>
                                            City
                                        </label>

                                        <i class="fa-solid fa-location-dot"></i>

                                        <input
                                            type="text"
                                            name="city"
                                            class="edit-control"
                                            placeholder="Enter city"
                                            value="<?= htmlspecialchars($recipient['city']); ?>"
                                            required
                                        >

                                    </div>

                                </div>


                            </div>



                            <!-- =================================================
                                 ACTIONS
                            ================================================= -->

                            <div class="edit-actions">


                                <button
                                    type="submit"
                                    name="update"
                                    class="update-recipient-btn"
                                >

                                    <i class="fa-solid fa-floppy-disk me-2"></i>

                                    Save Changes

                                </button>


                                <a
                                    href="recipients.php"
                                    class="cancel-recipient-btn"
                                >

                                    <i class="fa-solid fa-xmark"></i>

                                    Cancel

                                </a>


                            </div>


                        </form>


                    </div>

                </div>


            </div>

        </div>

    </div>

</div>


<?php include("../includes/footer.php"); ?>