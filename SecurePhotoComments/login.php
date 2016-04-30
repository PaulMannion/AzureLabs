<?php
	//display error
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

	session_start();
	include("connection.php"); //Establishing connection with our database

	$error = ""; //Variable for storing our errors.


	if(isset($_POST["submit"])) {
        if (empty($_POST["username"]) || empty($_POST["password"])) {
            $error = "Both fields are required.";
        } else {
            // Check Anti-CSRF token
            //checkToken($_REQUEST['user_token'], $_SESSION['session_token'], 'index.php');

            // Sanitise username input
            $user = $_POST['username'];
            $user = stripslashes($user);
            $user = mysqli_real_escape_string($db, $user);

            echo "<p>Has usernaFe been cleaned? <em>{$user}</em></p>";

            // Sanitise password input
            $pass = $_POST['password'];
            $pass = stripslashes($pass);
            $pass = mysqli_real_escape_string($db, $pass);

            echo "<p>Has password been cleaned? <em>{$pass}</em></p>";

            //   $pass = md5($pass);

            // Default values
            date_default_timezone_set('GMT');
            $total_failed_login = 3;
            $lockout_time = 5;
            $account_locked = false;

            // Check the database (Check user information)
            // Create a prepared statement //
            // The following code only executes if a correct user is entered

            $stmt = $db->stmt_init();
            $stmt->prepare("SELECT failed_login, last_login FROM users WHERE username =?");

            /* bind parameters for markers */
            $stmt->bind_param('s', $user);

            /* execute query */
            $stmt->execute();

                    if ($stmt)
                    {
//                        print 'Success! The user query ran OK';
                    } else {
                        print 'Error : (' . $db->errno . ') ' . $db->error;
                            }

                        /* bind variables to prepared statement */
                        $stmt->bind_result($failed_login, $last_login);

                        $stmt->store_result();

                        if ($stmt->num_rows == 1) //check a user was found
                        {
                            /* fetch values */


                           if ($stmt->fetch()){ // fetch contents of row


                                                if ($failed_login >= $total_failed_login)
                                                {
                                                    // User should be locked out unless the password is correct AND unlock time < timenow

                                                    $error = "This account has been locked due to too many incorrect logins.";

                                                    // Calculate when the user would be allowed to login again

                                                    $unlock_time = strtotime("+{$lockout_time} minutes", strtotime($last_login));
                                                    $timenow = time();


 /*                                                   echo "<p> This is the timenow: </p>" . date('D, d M Y H:i:s', $timenow);
                                                    echo "<p> This is the value of timenow: </p>" . $timenow;
                                                    echo "<p> This is the unlock time </p>" . date('D, d M Y H:i:s', $unlock_time);
                                                    echo "<p> this is the value of unlock_time: </p>" . $unlock_time;
                                                    echo "<p> timenow - unlock_time=" . ($timenow - $unlock_time);
*/
                                                    $last_login = strtotime($last_login);
                                                    echo "<p> Last attempted login: </p>" . date('D, d M Y H:i:s', $last_login);



                                                            if ($timenow > $unlock_time) {
                                                                echo "<p> going to set account_locked as 'false' -> Lock Status now: </p>";
                                                                var_dump($account_locked);
                                                                $account_locked = false;
                                                                echo "<p> trying to unlock -> account_locked should equal 'false': </p>";
                                                                var_dump($account_locked);

                                                            } else {
                                                                echo "<p> going to set account_locked as 'true' -> Lock Status now: </p>";
                                                                var_dump($account_locked);
                                                                $account_locked = true;
                                                                echo "<p> trying to lock -> account_locked should equal 'true': </p>";
                                                                var_dump($account_locked);

                                                                echo "<p> Account will be available to try again after: </p>" . date('D, d M Y H:i:s', $unlock_time);
                                                                echo "<p> Last attempted login: </p>" . date('D, d M Y H:i:s', $last_login);

                                                            }

                                                            $unlock_time = strtotime($unlock_time);
                                                            $error = "Account locked: too many incorrect log-ins. Please try again after {$lockout_time} minutes.";

                                                }

                                                else

                                                     {
                                                        echo "<p>wtf!</p>";

 //                                                       $error = "Incorrect username or password.";

                                                        $error = "You have entered an incorrect password {$failed_login} times. You will be locked out after {$total_failed_login} attempts.";

                                                        //increase the failed_login count

                                                        $stmt = $db->stmt_init();
                                                        $stmt = $db->prepare('UPDATE users SET failed_login=failed_login+1 WHERE username=?');
                                                        $stmt->bind_param('s', $user);
                                                        $stmt->execute();

                                                                if ($stmt) {
 //                                                                   print 'Success! failed_login increased by 1 due to incorrect user/password';
                                                                } else {
                                                                    print 'Error : (' . $db->errno . ') ' . $db->error;
                                                                }
                                                         
                                                    }

                                                    // where do we go fromm here?
                             }

                        }

                        else

                             {

                                $error = "Incorrect username or password. (both)";
                                 /* close statement */
//                                 $stmt->close();
                             }




                //  echo "<p>(This will be printed in any case) Number of login attempts: <em>{$failed_login}</em>.<br />Last login attempt was at: {$last_login}</em>.</p>";


                // Check the database (if username matches the password)

                $query = $db->stmt_init();
                $query = $db->prepare("SELECT username, password, last_login, failed_login FROM users where username=? AND password=?");
                $query->bind_param('ss', $user, $pass);
                $query->execute();


                                    if ($query) {
//                                        print 'Success! The User and Password Query ran ok.';
                                    } else {
                                        print 'Error : (' . $db->errno . ') ' . $db->error;
                                    }

//                echo "<p> Is account locked?: </p>";
//                var_dump($account_locked);

                $query->bind_result($username, $password, $last_login, $failed_login);
                $query->store_result();
                if ($query->num_rows == 1) //check a user was found
                {

                    echo "<p>This should only print if you've entered a correct username AND password</p>";

                    if ($query->fetch()) // fetch contents of row
                    {
                        if ($account_locked == false) {

                            // Had the account been locked out since last login?
                            if ($failed_login >= $total_failed_login) {

                                echo "<script>
                                    alert('There are no fields to generate a report');
                                    window.location.href='photos.php';
                                   </script>";


                                echo '<script type="text/javascript">alert("hello!");</script>';
                                echo "<p><em>Warning</em>: Someone might of been brute forcing your account.</p>";
                                echo "<p>Number of login attempts: <em>{$failed_login}</em>.<br />Last login attempt was at: <em>${last_login}</em>.</p>";
                            }

                            // Reset bad login count
                            $query = $db->prepare('UPDATE users SET failed_login = 0 WHERE username=?');
                            $query->bind_param('s', $user);
                            $query->execute();

                            if ($query) {
                                print 'Success! Bad login reset';
                            } else {
                                print 'Login Reset Error : (' . $db->errno . ') ' . $db->error;
                            }

                            // Login successful
                            $_SESSION['username'] = $user; // Initializing Session
                            header("location: photos.php"); // Redirecting To Other Page


                        } else {
                            // Login failed due to account lock
                            sleep(rand(2, 4));

                            // Give the user some feedback

 //                           $unlock_time=strtotime($unlock_time);
 //                           $new_time = date("H:i:s", strtotime('+1 hours', $unlock_time));
                            $error = "Account locked: too many incorrect log-ins. Please try again after {$lockout_time} minutes.";


                            // update the last login time

                            $query = $db->prepare('UPDATE users SET last_login= now() WHERE username=?');
                            $query->bind_param('s', $user);
                            $query->execute();


                            if ($query) {
//                                print 'Success! last_login attempt time was reset ';
                            } else {
                                print 'Login_time was not set ;-( Error : (' . $db->errno . ') ' . $db->error;
                            }

                        }

                        $query->close();
                        $db->close();

                    }

                }else {
                    $error = "Incorrect username or password.";

                    // update the last login time

                    $query = $db->prepare('UPDATE users SET last_login= now() WHERE username=?');
                    $query->bind_param('s', $user);
                    $query->execute();


                    if ($query) {
 //                       print 'Success! last_login attempt time was reset ';
                    } else {
                        print 'Login_time was not set ;-( Error : (' . $db->errno . ') ' . $db->error;
                    }

                    $query->close();
                    $db->close();
                }



        }
    }
/*
		// Generate Anti-CSRF token
		//generateSessionToken();
	}
*/
?>
