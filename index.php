<?php
$responseMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $message = $_POST['message'] ?? '';

  if ($name && $email && $message) {
    $conn = new mysqli("localhost", "root", "", "elevateweb_db");

    if ($conn->connect_error) {
      $responseMessage = "❌ Database connection failed: " . $conn->connect_error;
    } else {
      $conn->query("
        CREATE TABLE IF NOT EXISTS contacts (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(255),
          email VARCHAR(255),
          message TEXT,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
      ");

      $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $name, $email, $message);

      if ($stmt->execute()) {
        $responseMessage = "✅ Thank you! Your message has been sent.";
      } else {
        $responseMessage = "❌ Failed to send message. Please try again.";
      }

      $stmt->close();
      $conn->close();
    }
  } else {
    $responseMessage = "❌ Please fill in all fields.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="description" content="ElevateWeb builds modern, strategic websites with stunning design and robust development.">
  <title>ElevateWeb - Strategic Website Creators</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Raleway:wght@600;700&family=Roboto&display=swap" rel="stylesheet">
  <style>
    .message {
      margin-top: 20px;
      padding: 10px;
      background-color: #e1f5e1;
      color: #2e7d32;
      border-radius: 5px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>ElevateWeb</h1>
      <p class="subtitle">We create strategic, stunning websites that elevate your brand online.</p>
      <a href="#contact" class="button">Let's Work Together</a>
    </header>

    <section class="section">
      <h2>Our Services</h2>
      <div class="grid">
        <div class="card">
          <h3>UI/UX Design</h3>
          <p>Visually engaging, user-first interface design that converts and delights users across all devices.</p>
        </div>
        <div class="card">
          <h3>Web Development</h3>
          <p>We build robust sites using HTML, CSS, JavaScript, Python, and Django—secure, scalable, and fast.</p>
        </div>
        <div class="card">
          <h3>Responsive Design</h3>
          <p>Fully optimized layouts for mobile, tablet, and desktop with pixel-perfect precision.</p>
        </div>
      </div>
    </section>

    <section class="section">
      <h2>Meet the Team</h2>
      <div class="team-grid">
        <div class="card">
          <h3>Yash Makwana</h3>
          <p>Backend Developer<br><strong>Python | Django | APIs | Security</strong></p>
        </div>
        <div class="card">
          <h3>Keyur Dudhatra</h3>
          <p>UI/UX Designer<br><strong>Figma | Webflow | Prototyping | Branding</strong></p>
        </div>
      </div>
    </section>

    <section class="section" id="contact">
      <h2>Contact Us</h2>
      <form method="POST" action="#contact">
        <label for="name">Name</label>
        <input name="name" type="text" id="name" placeholder="Your Name" required />

        <label for="email">Email</label>
        <input name="email" type="email" id="email" placeholder="Your Email" required />

        <label for="message">Message</label>
        <textarea name="message" id="message" rows="5" placeholder="Tell us about your project..." required></textarea>

        <button type="submit">Send Message</button>
      </form>
      <?php if (!empty($responseMessage)): ?>
        <div class="message"><?php echo $responseMessage; ?></div>
      <?php endif; ?>
    </section>

    <footer>
      &copy; 2025 ElevateWeb. Built with ❤️ by Yash & Keyur.
    </footer>
  </div>
</body>
</html>
