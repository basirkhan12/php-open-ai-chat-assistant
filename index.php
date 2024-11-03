<?php
require_once( 'data/data.php');


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open AI Chat Assistant</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="glow left"></div>
    <div class="glow right"></div>
    <div class="page-content">
    <section class="support-hero-section py-5 border-bottom" style="color: white;">
        <div class="container-xxl overflow-hidden">
            <div class="row gy-3 gy-lg-0 align-items-lg-center justify-content-lg-between">
                <!-- Left Side: Text Content -->
                <div class="col-12 col-lg-6 order-1 order-lg-0">
                    <h2 class="display-3 fw-bold mb-3">Support</h2>
                    <p class="fs-4 mb-5">Need assistance? We're here to help you 24/7. Whether it's troubleshooting,
                        account management, or general inquiries, our support team is ready to guide you every step of
                        the way.</p>
                </div>
                <!-- Right Side: Image -->
                <div class="col-12 col-lg-5 text-center">
                    <img class="img-fluid" loading="lazy" src="./assets/img/support-hero-img.jpg" alt="Support"
                        style="-webkit-mask-image: url('./assets/img/hero-img-blob-1.svg'); mask-image: url('./assets/img/hero-img-blob-1.svg);">
                </div>
            </div>
        </div>
    </section>


    <!-- Main Container with Sidebar Chatbot and Help Center -->
    <div class="container-xxl py-5 px-3">


        <div class="row main-row my-3">
            <!-- Help Center Content -->
            <div class="col-lg-8">
                <section class="faq-section">
                    <h2>Frequently Asked Questions</h2>

                    <?php
                    // Generate HTML for the FAQ accordion
                    echo '<div class="accordion" id="faqAccordion">';

                    foreach ($faq_array['faqs'] as $index => $faq) {
                        $question = $faq['question'];
                        $answer = $faq['answer'];

                        // Use Markdown to HTML conversion for rendering Markdown in the answer
                        $parsed_answer = $presedown->text($answer); // You can use a more sophisticated markdown parser if needed
                    
                        echo '
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq' . ($index + 1) . '">
                ' . htmlspecialchars($question) . '
            </button>
        </h3>
        <div id="faq' . ($index + 1) . '" class="accordion-collapse collapse">
            <div class="accordion-body">
                ' . $parsed_answer . '
            </div>
        </div>
    </div>';
                    }

                    echo '</div>';
                    ?>

                </section>

        <!---- Content Guidelines FAQs ---->

        <section class="faq-section my-5">
                    <h2>Content Guidelines FAQs</h2>

                    <?php
                    // Generate HTML for the FAQ accordion
                    echo '<div class="accordion" id="faqAccordion">';

                    foreach ($faq_content_guidlies['faqs'] as $index => $faq) {
                        $question = $faq['question'];
                        $answer = $faq['answer'];

                        // Use Markdown to HTML conversion for rendering Markdown in the answer
                        $parsed_answer = $presedown->text($answer); // You can use a more sophisticated markdown parser if needed
                    
                        echo '
    <div class="accordion-item">
        <h3 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqq' . ($index + 1) . '">
                ' . htmlspecialchars($question) . '
            </button>
        </h3>
        <div id="faqq' . ($index + 1) . '" class="accordion-collapse collapse">
            <div class="accordion-body">
                ' . $parsed_answer . '
            </div>
        </div>
    </div>';
                    }

                    echo '</div>';
                    ?>

                </section>

               



                <!-- Contact Section -->
                <section class="contact-section d-block d-md-none">
                    <h2>Need Further Assistance?</h2>
                    <p>If you can't find the answer to your question, our customer service live chat is here to help!</p>
                <!--link scoll to live chat box-->
                    <button href="#chatbot" class="btn btn-light contact-support-button">Customer Support</button>
                </section>
            </div>
            <!-- Chatbot Sidebar -->
            <div class="col-lg-4">
            <h2 style="color: #ffffff00!important;;">chatsupport</h2>
                <div id = "chatbot" class="chat-container">
                    <div class="chat-header">Customer Service Live Chat</div>
                    <div class="chat-window" id="chatWindow">
                        <!-- Messages will be appended here -->
                    </div>
                    <div class="chat-input">
                        <input type="text" id="userInput" placeholder="Type a message..." />
                        <button id="sendButton">Send</button>
                    </div>

                    <audio id="beepSound" src="<?= $baseurl ?>/assets/sounds/reply.m4a" preload="auto"
                        style="display:none"></audio>


                </div>
            </div>
        </div>
    </div>


    
  
    </div>
 

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/markdown-it/dist/markdown-it.min.js"></script>
    <script src="assets/js/requester.js"></script>

</body>

</html>