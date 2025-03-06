<?php
include_once('../header.php');
?>

<section class="container-lg container-fluid py-5">
    <!-- Page Title -->
    <div class="text-center mb-5">
        <h1 class="fw-bold">Frequently Asked Questions</h1>
        <p class="lead text-muted">Find answers to commonly asked questions below.</p>
    </div>

    <!-- FAQ Section -->
    <div class="accordion" id="faqAccordion">
        <!-- Question 1 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    What services do you offer?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    We offer a wide range of services including web development, digital marketing, and business consulting to help your brand grow.
                </div>
            </div>
        </div>

        <!-- Question 2 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    How can I contact customer support?
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    You can reach our support team via email at support@example.com or call us at (123) 456-7890. Our team is available 24/7 to assist you.
                </div>
            </div>
        </div>

        <!-- Question 3 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Do you offer refunds or cancellations?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Yes, we offer refunds within the first 14 days of purchase. Cancellations can be made anytime through your account settings.
                </div>
            </div>
        </div>

        <!-- Question 4 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    How secure is my personal information?
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    We use industry-standard encryption and security practices to ensure your data remains protected at all times.
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include_once('../footer.php');
?>
