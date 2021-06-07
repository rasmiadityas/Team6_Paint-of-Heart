<?php
ob_start();
session_start();
include 'classes/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <?php include_once 'components/boot.php'; ?>
    <title>Contact - Paint Of Heart</title>
    <style>
        #lead {
            margin-bottom: 15%;
            text-shadow: 2px 2px 5px #f13c20 ;
        }
        #send-btn {
            background-color: #4045a1 !important;
            margin-bottom: 2%;
        }
    </style>
    <link rel="stylesheet" href="components/style.css">
</head>
<body>
    <header>
        <?php
        require_once 'classes/autoload.php';
        include_once 'components/navbar.php';
        ?>
    </header>
    <div class="d-flex justify-content-center align-items-center" style="background-image: url(https://images.pexels.com/photos/406014/pexels-photo-406014.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940); height: 60vh; background-size: cover; background-repeat: no-repeat; background-position: 50% 50%;">
        <h1 id="lead" class="text-center text-white">Help us<br>improve for you!</h1>
    </div>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="well well-sm">
                        <form class="form-horizontal" action="" method="post">
                            <fieldset>

                                <!-- Email input-->
                                <div class="form-group">
                                    <label class="col-md-3 control-label mt-3" for="email">Your E-mail</label>
                                    <div class="col-md-9">
                                        <input id="email" name="email" type="email" placeholder="name@example.com" class="form-control">
                                    </div>
                                </div>

                                <!-- Message body -->
                                <div class="form-group">
                                    <label class="col-md-3 control-label mt-3" for="message">Your message</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="message" name="message" placeholder="What's on your mind?" rows="5"></textarea>
                                    </div>
                                </div>
                                <?php
                                if ($_SERVER["REQUEST_METHOD"] == 'POST') { // Check if the User coming from a request
                                    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL); // simple validation if you insert an email 
                                    $msg = filter_var($_POST["message"], FILTER_SANITIZE_STRING); // simple validation if you insert a string

                                    // mail function in php look like this  (mail(To, subject, Message, Headers, Parameters))
                                    $headers = "FROM : " . $email . "\r\n";
                                    $myEmail = "rasmiadityas@gmail.com";
                                    if (mail($myEmail, "message coming from the contact form", $msg, $headers)) {
                                        echo "<div class='alert alert-success my-2'>Sent successfully</div>";
                                    } else {
                                        echo "<div class=alert alert-danger my-2'>Error, please try again later</div>";
                                    }
                                }
                                ?>
                                <!-- Form actions -->
                                <div class="form-group">
                                    <div class="col-md-12 text-right mt-3">
                                        <button type="submit" class= "btn btn-primary btn-small home-btn">Send</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="d-flex flex-column justify-content-center align-items-center col-md-6">
                    <h4>We would love to hear from you</h4>
                    <p>Every customer feedback is taken seriously and will help us improve our service quality. <br>So don't be shy and hit us with your questions, remarks or concerns!</p>
                </div>
            </div> 
        </div>
        <h2 id="where">Where to find us</h2>
            <div id="map"></div> 
    </main>
    <footer>
        <?php
        include_once 'components/footer.php';
        ?>
    </footer>
    <!-- Bootstrap 5 JS bundle  -->
    <?php include_once 'components/bootjs.php'; ?>
    <script>
        let map;
        function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 48.20849, lng: 16.37208},
        zoom: 8,
        });
        var pinpoint = new google.maps.Marker({
        position: { lat: 48.20849, lng: 16.37208},
        map: map
        });
        }
        </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtjaD-saUZQ47PbxigOg25cvuO6_SuX3M&callback=initMap" async defer></script>
</body>
</html>