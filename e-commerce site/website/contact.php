<?php
session_name("customer_session");
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require 'vendor/autoload.php';

// Define a class for handling the contact form
class ContactForm {
    private $name;
    private $email;
    private $mobile;
    private $message;

    // Constructor to initialize form data
    public function __construct($name, $email, $mobile, $message) {
        $this->name = $this->clean_input($name);
        $this->email = $this->clean_input($email);
        $this->mobile = $this->clean_input($mobile);
        $this->message = $this->clean_input($message);
    }

    // Function to sanitize user inputs
    private function clean_input($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Function to validate user inputs
    public function validate() {
        if (empty($this->name) || empty($this->email) || empty($this->mobile) || empty($this->message)) {
            throw new Exception("All fields are required.");
        }
        if (!preg_match("/^[a-zA-Z-' ]*$/", $this->name)) {
            throw new Exception("Invalid name format.");
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please enter a valid email address.");
        }
        if (!preg_match("/^[0-9]{10}$/", $this->mobile)) {
            throw new Exception("Invalid phone number. Must be 10 digits.");
        }
        return true;
    }

    // Function to send an email using PHPMailer
    public function sendEmail() {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'claimorgiftshop@gmail.com';  // Business email
            $mail->Password = 'jeyu mgdv gotj hvjo'; // Gmail App password (Make sure to use an App Password for security)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender and receiver
            $mail->setFrom($this->email, $this->name); // User's email as sender
            $mail->addReplyTo($this->email, $this->name);
            $mail->addAddress('claimorgiftshop@gmail.com'); // Business email as receiver

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body = "
                <h2>Contact Request from {$this->name}</h2>
                <p><strong>Name:</strong> {$this->name}</p>
                <p><strong>Email:</strong> {$this->email}</p>
                <p><strong>Mobile:</strong> {$this->mobile}</p>
                <p><strong>Message:</strong><br>{$this->message}</p>
            ";

            // Send email
            if ($mail->send()) {
                return "Your message has been sent successfully!";
            } else {
                throw new Exception("Email could not be sent. Please try again later.");
            }
        } catch (Exception $e) {
            throw new Exception("Mailer Error: " . $mail->ErrorInfo);
        }
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    try {
        $contactForm = new ContactForm($_POST['name'], $_POST['email'], $_POST['mobile'], $_POST['message']);
        $contactForm->validate();
        $successMessage = $contactForm->sendEmail();
        
        $_SESSION['modal_message'] = ['type' => 'success', 'message' => $successMessage];
    } catch (Exception $e) {
        $_SESSION['modal_message'] = ['type' => 'error', 'message' => $e->getMessage()];
    }

    header("Location: index.php"); // Redirect back to the main page
    exit();
}
?>
