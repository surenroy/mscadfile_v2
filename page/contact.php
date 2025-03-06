<?php
include_once('../header.php');
?>

<section class="container-lg container-fluid py-5">
    <!-- Page Title -->
    <div class="text-center mb-4">
        <h1 class="fw-bold">Contact Us</h1>
        <p class="lead text-muted">We’d love to hear from you! Fill out the form below.</p>
    </div>

    <!-- Contact Form -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <form action="contact-process.php" method="POST">
                    <!-- Name Field -->
                    <div class="mb-3 w-100">
                        <label for="name" class="form-label w-100">Full Name</label>
                        <input type="text" class="form-control w-100" id="name" name="name" required>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3 w-100">
                        <label for="email" class="form-label w-100">Email Address</label>
                        <input type="email" class="form-control w-100" id="email" name="email" required>
                    </div>

                    <!-- Mobile Field -->
                    <div class="mb-3 w-100">
                        <label for="mobile" class="form-label w-100">Mobile Number</label>
                        <input type="tel" class="form-control w-100" id="mobile" name="mobile" required>
                    </div>

                    <!-- Message Field -->
                    <div class="mb-3 w-100">
                        <label for="message" class="form-label w-100">Your Message</label>
                        <textarea class="form-control w-100" id="message" name="message" rows="4" required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
include_once('../footer.php');
?>
