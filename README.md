
# Open AI Chat Assistant

Welcome to the Open AI Chat Assistant project! This web application integrates with the OpenAI API to provide a customer support chat interface. Users can ask questions, and the assistant will respond based on pre-defined FAQs and guidelines.

## Table of Contents

- [Features](#features)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Usage](#usage)
- [File Structure](#file-structure)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Interactive Chat Interface**: Users can communicate with an AI assistant in real-time.
- **FAQs Section**: Provides answers to frequently asked questions.
- **Content Guidelines**: Ensures users understand content policies.

## Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP version 7.0 or higher
- Composer (for dependency management)
- OpenAI API key (replace the placeholder in `api/chatbot_backend.php`)

## Installation

To set up the project locally, follow these steps:

1. **Clone the repository**:
    ```bash
    git clone https://gitlab.com/yourusername/open-ai-chat-assistant.git
    cd open-ai-chat-assistant
    ```

2. **Install dependencies** (if applicable):
    ```bash
    composer install
    ```

3. **Set up the environment**:
    - Open `api/chatbot_backend.php` and replace the placeholder values for `$apiKey` and `$assistantId` with your actual OpenAI API credentials.

4. **Start a local server**:
    You can use PHP's built-in server for local testing:
    ```bash
    php -S localhost:8000
    ```

5. **Access the application**:
    Open your web browser and go to `http://localhost:8000`.

## Usage

- Navigate to the homepage to view the support section and FAQs.
- Use the chat interface to interact with the AI assistant.
- For additional assistance, follow the prompts within the application.

## File Structure

```
/open-ai-chat-assistant
│
├── index.php               # Main entry point for the application
├── api/
│   └── chatbot_backend.php  # Backend script for handling chat requests
├── assets/
│   ├── css/
│   │   └── style.css       # Custom styles for the application
│   └── sounds/
│       └── reply.m4a      # Notification sound for chat replies
└── data/
    └── data.php            # Data source for FAQs and guidelines
```

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any improvements or bug fixes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.


### Instructions for Use
- Replace any placeholder text, especially in the installation section, with the actual information relevant to your project.
- Adjust paths, filenames, and any additional setup instructions based on your specific project structure and requirements.
- Include any additional sections that may be relevant to your project.
