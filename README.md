# Laravel AI CRM — Smart Customer Relationship Management
==============================================

## Overview

A production-ready CRM platform built with Laravel 11, featuring AI-powered lead scoring, automated email drafting, sentiment analysis, and churn prediction. Demonstrates modern Laravel architecture, queue workers, real-time features, and AI integration.

## Architecture

### High-Level Components

*   **Frontend**: Built with Vue.js and Inertia.js for a seamless user experience.
*   **Backend**: Powered by Laravel 11, utilizing Sanctum for authentication and Horizon for queue workers.
*   **Database**: Utilizes MongoDB for efficient data storage and retrieval.
*   **AI Services**: Integrates with OpenAI for AI-powered features, such as lead scoring and sentiment analysis.

### Key Features

*   **Lead Scoring**: AI-powered lead scoring system to prioritize leads based on their potential value.
*   **Automated Email Drafting**: Automated email drafting feature to streamline communication with leads and customers.
*   **Sentiment Analysis**: Sentiment analysis feature to gauge customer satisfaction and sentiment.
*   **Churn Prediction**: Churn prediction feature to identify potential customer churn and take proactive measures.

## Setup Guide

### Prerequisites

*   PHP 8.1 or higher
*   Composer 2.3 or higher
*   Node.js 16 or higher
*   MongoDB 5.0 or higher

### Installation

1.  Clone the repository: `git clone https://github.com/your-username/laravel-ai-crm.git`
2.  Install dependencies: `composer install`
3.  Install frontend dependencies: `npm install` or `yarn install`
4.  Generate application key: `php artisan key:generate`
5.  Run database migrations: `php artisan migrate`
6.  Seed database with sample data: `php artisan db:seed`
7.  Start queue workers: `php artisan horizon:start`
8.  Start frontend development server: `npm run dev` or `yarn dev`

### Configuration

*   Update `.env` file with your MongoDB connection details.
*   Update `config/services.php` file with your OpenAI API key.

## AI Features Documentation

### Lead Scoring

*   The lead scoring system uses OpenAI's AI to analyze lead data and assign a score based on their potential value.
*   The score is calculated based on factors such as lead source, lead behavior, and lead demographics.

### Automated Email Drafting

*   The automated email drafting feature uses OpenAI's AI to generate email drafts based on lead data and customer interactions.
*   The email drafts are customizable and can be edited before sending.

### Sentiment Analysis

*   The sentiment analysis feature uses OpenAI's AI to analyze customer feedback and sentiment.
*   The sentiment analysis provides insights into customer satisfaction and sentiment.

### Churn Prediction

*   The churn prediction feature uses OpenAI's AI to analyze customer behavior and predict potential churn.
*   The churn prediction provides insights into customer loyalty and retention.

## Screenshots

### Dashboard

![Dashboard](screenshots/dashboard.png)

### Lead Details

![Lead Details](screenshots/lead-details.png)

### Email Drafting

![Email Drafting](screenshots/email-drafting.png)

### Sentiment Analysis

![Sentiment Analysis](screenshots/sentiment-analysis.png)

### Churn Prediction

![Churn Prediction](screenshots/churn-prediction.png)

## Contributing

Contributions are welcome and appreciated. Please submit a pull request with your changes and a brief description of the changes.

## License

This project is licensed under the MIT License.

## Acknowledgments

This project was built with the following dependencies:

*   Laravel 11
*   Sanctum
*   Horizon
*   Inertia.js
*   OpenAI
*   MongoDB
*   Vue.js
*   Tailwind CSS
*   Heroicons
*   Recharts
*   Axios
*   Day.js