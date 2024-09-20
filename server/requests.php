<?php
// error_reporting(0);
session_start();
include("../common/db.php");
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];

    $user = $conn->prepare("Insert into `users`
    (`id`,`username`,`email`,`password`,`address`)
    values(NULL,'$username','$email','$password','$address');
    ");

    $result = $user->execute();
    $user->insert_id;
    if ($result) {
        
        $_SESSION["user"] = ["username" => $username,"email" => $email,"user_id" => $user->insert_id];
        header("location: /other/discuss");
    } else {
        echo "New user not registred.";
    }
    
} else if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = "";
    $user_id = 0;

     $query="select * from users where email='$email' and password='$password'";    
     $result = $conn->query($query);
     if ($result->num_rows == 1) {
        
        foreach($result as $row) {
            $username = $row['username'];
            $user_id = $row['id'];
        }
        $_SESSION["user"] = ["username" => $username,"email" => $email,"user_id" => $user_id];
        header("location: /other/discuss");
    } else {
        echo "New user not login.";
    }
} else if (isset($_GET['logout'])) {
    session_unset(); //clear all session variables
    header("location: /other/discuss");
} elseif (isset($_POST["ask"])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category'];
    $user_id = $_SESSION['user']['user_id'];

    $question = $conn->prepare("Insert into `questions`
    (`id`,`title`,`description`,`category_id`,`user_id`)
    values(NULL,'$title','$description','$category_id','$user_id');
    ");

    $result = $question->execute();
    $question->insert_id;
    if ($result) {
        header("location: /other/discuss");
    } else {
        echo "Question is not added to website.";
    }
} elseif (isset($_POST["answer"])) {
    $answer = mysqli_real_escape_string($conn, $_POST['answer']);
    $question_id = $_POST['question_id'];
    $user_id = $_SESSION['user']['user_id'];

    $query = $conn->prepare("Insert into `answers`
    (`id`,`answer`,`question_id`,`user_id`)
    values(NULL,'$answer','$question_id','$user_id');
    ");

    $result = $query->execute();
    if ($result) {
        header("location: /other/discuss?q-id=$question_id");
    } else {
        echo "Answer is not submitted.";
    }
} else if (isset($_GET["delete"])) {
    echo $qid = $_GET["delete"];
    $query = $conn->prepare("delete from questions where id = $qid");
    $result = $query->execute();
    if ($result) {
        header("location: /other/discuss");
    } else {
        echo "Questions not deleted.";
    }
}
// 25

?>