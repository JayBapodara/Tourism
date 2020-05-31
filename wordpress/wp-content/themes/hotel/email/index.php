		<?php 
			require 'PHPMailerAutoload.php';

			$mail = new PHPMailer;

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'jaybapodara.rao@gmail.com';                 // SMTP username
			$mail->Password = 'Rao@1234';                           // SMTP password
			$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;                                    // TCP port to connect to

			$mail->setFrom('jaybapodara.rao@gmail.com');
			// $mail->addAddress('jaybapodara555@gmail.com');       // Add a recipient
			$mail->addAddress($_GET['user_email']);       // Add a recipient

			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = 'Testing mail';
			$mail->Body    = '<div style="border:2px solid red;">welcome to our tourism project <b>in bold!</b></div>';
			$mail->AltBody = 'Hello This message is only for testing';

			if(!$mail->send()) {
				echo 'Message could not be sent.';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				echo 'Message has been sent';
			}
		?>