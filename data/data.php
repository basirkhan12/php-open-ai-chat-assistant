<?php

require_once( './lib/presedown.php');




$faqjson = '{
  "faqs": [
    {
      "question": "How do I sign up for an account?",
      "answer": "To sign up:\n\n- Visit the registration page.\n- Fill out the required information.\n- Submit the form and confirm your email."
    },
    {
      "question": "How can I reset my password?",
      "answer": "To reset your password:\n\n- Click on the **Forgot Password** link on the login page.\n- Enter your registered email and follow the instructions sent to your inbox."
    },
    {
      "question": "What subscription plans are available?",
      "answer": "We offer a Free plan and various paid plans with additional features. Details are provided during the sign-up process."
    },
    {
      "question": "What is your privacy policy?",
      "answer": "Our privacy policy explains how we handle data collection, usage, and security. Refer to the policy page for more details."
    },
    {
      "question": "What are your terms and conditions?",
      "answer": "By using our service, you agree to our terms and conditions, which cover site usage, content rules, and subscription policies."
    },
    {
      "question": "How do I report an issue?",
      "answer": "To report an issue:\n\n1. Visit the relevant section.\n2. Click the “Report” button.\n3. Fill out the form and submit your report."
    },
    {
      "question": "How can I delete my account?",
      "answer": "To delete your account, go to your account settings and select **Delete Account**. Follow the instructions to confirm the deletion."
    }
  ]
}';



$faq_array = json_decode($faqjson, true);

$presedown=new Parsedown();


$content_guidlins_json = '{
  "faqs": [
    {
      "question": "What steps do I take to create a new account?",
      "answer": "To create an account:\n\n- Navigate to the signup page.\n- Provide the necessary details.\n- Submit the registration form and verify your email address."
    },
    {
      "question": "How do I change my password?",
      "answer": "To change your password:\n\n- Click the **Reset Password** option on the login page.\n- Enter your registered email address and follow the instructions sent to you."
    },
    {
      "question": "What types of subscription options do you provide?",
      "answer": "We provide a Free tier and several premium subscription options with enhanced features. Further details are available during registration."
    },
    {
      "question": "Where can I find your privacy policy?",
      "answer": "Our privacy policy outlines our practices regarding data collection and security. Please visit the policy page for comprehensive information."
    },
    {
      "question": "What are the conditions for using your service?",
      "answer": "By accessing our service, you agree to our terms and conditions, which detail site usage, content guidelines, and subscription rules."
    },
    {
      "question": "How should I report a problem?",
      "answer": "To report a problem:\n\n1. Go to the appropriate section.\n2. Click the “Report Issue” button.\n3. Complete the form and send your report."
    },
    {
      "question": "What is the process to permanently delete my account?",
      "answer": "To permanently delete your account, access your account settings and choose **Delete Account**. Follow the provided steps to finalize the deletion."
    }
  ]
}';


$faq_content_guidlies = json_decode($content_guidlins_json, true);