<?php

session_start();

include("../includes/db.php");

if(!isset($_SESSION['donor_id']))
{
    header("Location:login.php");
    exit();
}

$id = $_SESSION['donor_id'];

$message = "";


// Update Profile

if(isset($_POST['update']))
{

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $city = trim($_POST['city']);
    $address = trim($_POST['address']);
    $availability = $_POST['availability'];
    $last_donation_date = $_POST['last_donation_date'];



    $stmt = $conn->prepare("
        UPDATE donors SET

        name=?,
        phone=?,
        gender=?,
        age=?,
        city=?,
        address=?,
        availability=?,
        last_donation_date=?

        WHERE id=?
    ");



    $stmt->bind_param(
        "sssissssi",
        $name,
        $phone,
        $gender,
        $age,
        $city,
        $address,
        $availability,
        $last_donation_date,
        $id
    );



    if($stmt->execute())
    {

        $_SESSION['donor_name']=$name;


        $message="
        <div class='alert alert-success'>
        Profile Updated Successfully
        </div>";

    }
    else
    {

        $message="
        <div class='alert alert-danger'>
        Profile Update Failed
        </div>";

    }


    $stmt->close();

}



// Fetch Donor Data

$query=$conn->prepare(
"SELECT * FROM donors WHERE id=?"
);


$query->bind_param("i",$id);

$query->execute();


$result=$query->get_result();


$donor=$result->fetch_assoc();



if(!$donor)
{
    die("Donor not found");
}



include("../includes/header.php");
include("../includes/navbar.php");

?>



<div class="container py-5">


<div class="row justify-content-center">


<div class="col-lg-8">


<div class="card shadow border-0">


<div class="card-header bg-danger text-white">

<h3 class="mb-0">
My Donor Profile
</h3>

</div>


<div class="card-body">


<?php

if($message!="")
{
    echo $message;
}

?>



<form method="POST">



<div class="row">


<div class="col-md-6 mb-3">

<label class="form-label">
Name
</label>

<input type="text"
name="name"
class="form-control"
value="<?=htmlspecialchars($donor['name'])?>"
required>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">
Email
</label>

<input type="email"
class="form-control"
value="<?=htmlspecialchars($donor['email'])?>"
readonly>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">
Phone
</label>

<input type="text"
name="phone"
class="form-control"
value="<?=htmlspecialchars($donor['phone'])?>"
required>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">
Blood Group
</label>

<input type="text"
class="form-control"
value="<?=htmlspecialchars($donor['blood_group'])?>"
readonly>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">
Gender
</label>


<select name="gender"
class="form-select">


<option value="Male"
<?=($donor['gender']=="Male")?'selected':''?>>
Male
</option>


<option value="Female"
<?=($donor['gender']=="Female")?'selected':''?>>
Female
</option>


<option value="Other"
<?=($donor['gender']=="Other")?'selected':''?>>
Other
</option>


</select>


</div>



<div class="col-md-6 mb-3">

<label class="form-label">
Age
</label>


<input type="number"
name="age"
class="form-control"
value="<?=htmlspecialchars($donor['age'])?>"
min="18"
max="65"
required>


</div>

<div class="col-md-6 mb-3">

<label class="form-label">
City
</label>

<input type="text"
name="city"
class="form-control"
value="<?=htmlspecialchars($donor['city'])?>"
required>

</div>



<div class="col-12 mb-3">

<label class="form-label">
Address
</label>

<textarea
name="address"
class="form-control"
rows="3"><?=htmlspecialchars($donor['address'])?></textarea>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">
Availability
</label>


<select name="availability"
class="form-select">


<option value="Available"
<?=($donor['availability']=="Available")?'selected':''?>>
Available
</option>


<option value="Unavailable"
<?=($donor['availability']=="Unavailable")?'selected':''?>>
Unavailable
</option>


</select>


</div>




<div class="col-md-6 mb-3">

<label class="form-label">
Last Donation Date
</label>


<input type="date"
name="last_donation_date"
class="form-control"
value="<?=htmlspecialchars($donor['last_donation_date'])?>">

</div>



<div class="col-12 mt-3">


<button type="submit"
name="update"
class="btn btn-danger w-100">

Update Profile

</button>



<a href="dashboard.php"
class="btn btn-secondary w-100 mt-3">

Back to Dashboard

</a>


</div>



</div>


</form>


</div>


</div>


</div>


</div>


</div>



<?php

$query->close();

include("../includes/footer.php");

?>