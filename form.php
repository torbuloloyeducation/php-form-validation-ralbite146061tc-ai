<?php

$name = $email = $gender = $comment = $website = $phone = $password = $confirmPassword = "";
$nameErr = $emailErr = $genderErr = $websiteErr = $phoneErr = $passwordErr = $confirmPasswordErr = $termsErr = "";
$submissionCount = 0;
$formSubmitted = false;


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $submissionCount = isset($_POST["submission_count"]) ? (int)$_POST["submission_count"] + 1 : 1;


    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    if (!empty($_POST["comment"])) {
        $comment = test_input($_POST["comment"]);
    }

    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
        }
    }

    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match('/^\[+]?[0-9 \-]{7,15}$/', $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"]; 
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long";
        }
    }

    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "Please confirm your password";
    } else {
        $confirmPassword = $_POST["confirm_password"];
        if ($confirmPassword !== $password && empty($passwordErr)) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }

    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }

    if (
        empty($nameErr) && empty($emailErr) && empty($genderErr) &&
        empty($websiteErr) && empty($phoneErr) &&
        empty($passwordErr) && empty($confirmPasswordErr) &&
        empty($termsErr)
    ) {
        $formSubmitted = true;
    }
}

function test_input($data) {
    $data = trim($data);          
    $data = stripslashes($data);  
    $data = htmlspecialchars($data); 
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Form Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
            background: #f5f5f5;
        }
        h2 { color: #333; }
        .form-group {
            margin-bottom: 16px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 4px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="url"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .error {
            color: red;
            font-size: 0.875em;
            margin-top: 4px;
        }
        .success-box {
            background: #e6f4ea;
            border: 1px solid #34a853;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 24px;
        }
        .success-box h3 { margin-top: 0; color: #2d7a3a; }
        .success-box p { margin: 4px 0; }
        .counter {
            font-size: 0.85em;
            color: #666;
            margin-bottom: 12px;
            font-style: italic;
        }
        input[type="submit"] {
            background: #4285f4;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        input[type="submit"]:hover { background: #2a6dd9; }
        .required { color: red; }
        fieldset {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px 16px;
            margin-bottom: 16px;
        }
        legend { font-weight: bold; }
    </style>
</head>
<body>

<h2>PHP Form Validation</h2>

<?php if ($submissionCount > 0): ?>
    <p class="counter">Submission attempt: <?= $submissionCount ?></p>
<?php endif; ?>

<?php if ($formSubmitted): ?>
    <div class="success-box">
        <h3>&#10003; Form Submitted Successfully</h3>
        <p><strong>Name:</strong> <?= $name ?></p>
        <p><strong>Email:</strong> <?= $email ?></p>
        <p><strong>Phone:</strong> <?= $phone ?></p>
        <p><strong>Gender:</strong> <?= $gender ?></p>
        <?php if (!empty($website)): ?>
            <p><strong>Website:</strong> <?= $website ?></p>
        <?php endif; ?>
        <?php if (!empty($comment)): ?>
            <p><strong>Comment:</strong> <?= $comment ?></p>
        <?php endif; ?>

    </div>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">

    <input type="hidden" name="submission_count" value="<?= $submissionCount ?>">

    <div class="form-group">
        <label>Name <span class="required">*</span></label>
        <input type="text" name="name" value="<?= $name ?>">
        <?php if (!empty($nameErr)): ?>
            <div class="error"><?= $nameErr ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Email <span class="required">*</span></label>
        <input type="text" name="email" value="<?= $email ?>">
        <?php if (!empty($emailErr)): ?>
            <div class="error"><?= $emailErr ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Phone Number <span class="required">*</span></label>
        <input type="text" name="phone" value="<?= $phone ?>" placeholder="e.g. +1 555-123-4567">
        <?php if (!empty($phoneErr)): ?>
            <div class="error"><?= $phoneErr ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Website <em>(optional)</em></label>
        <input type="text" name="website" value="<?= $website ?>" placeholder="https://example.com">
        <?php if (!empty($websiteErr)): ?>
            <div class="error"><?= $websiteErr ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Gender <span class="required">*</span></label>
        <input type="radio" name="gender" value="female" <?= ($gender == "female") ? "checked" : "" ?>> Female
        <input type="radio" name="gender" value="male"   <?= ($gender == "male")   ? "checked" : "" ?>> Male
        <input type="radio" name="gender" value="other"  <?= ($gender == "other")  ? "checked" : "" ?>> Other
        <?php if (!empty($genderErr)): ?>
            <div class="error"><?= $genderErr ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Comment <em>(optional)</em></label>
        <textarea name="comment" rows="4"><?= $comment ?></textarea>
    </div>

    <fieldset>
        <legend>Password</legend>
        <div class="form-group">
            <label>Password <span class="required">*</span></label>
            <input type="password" name="password" placeholder="At least 8 characters">
            <?php if (!empty($passwordErr)): ?>
                <div class="error"><?= $passwordErr ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label>Confirm Password <span class="required">*</span></label>
            <input type="password" name="confirm_password">
            <?php if (!empty($confirmPasswordErr)): ?>
                <div class="error"><?= $confirmPasswordErr ?></div>
            <?php endif; ?>
        </div>
    </fieldset>

    <div class="form-group">
        <label>
            <input type="checkbox" name="terms" <?= isset($_POST["terms"]) ? "checked" : "" ?>>
            I agree to the Terms and Conditions <span class="required">*</span>
        </label>
        <?php if (!empty($termsErr)): ?>
            <div class="error"><?= $termsErr ?></div>
        <?php endif; ?>
    </div>

    <input type="submit" value="Submit">

</form>

</body>
</html>
