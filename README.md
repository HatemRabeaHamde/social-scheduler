# Social Scheduler

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A simplified content scheduling application that lets users create and schedule posts across multiple social platforms.  
This challenge tests your PHP/Laravel skills along with frontend development, focusing on code quality, performance, and attention to detail.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Backend Requirements](#backend-requirements)
- [Frontend Requirements](#frontend-requirements)
- [Creative Challenges](#creative-challenges)
- [Primary Evaluation Criteria](#primary-evaluation-criteria)
- [Installation](#installation)
- [Usage](#usage)
- [Submission Guidelines](#submission-guidelines)
- [License](#license)
- [Author](#author)

---

## Project Overview

Create a simplified content scheduling application enabling users to create and schedule posts across multiple social media platforms such as Twitter, Instagram, LinkedIn, and others.  
The application demonstrates mastery of Laravel/PHP backend development and frontend UI skills, with emphasis on code quality, performance, and UX.

---

## Backend Requirements

### Models & Database

- **Post**: `id`, `title`, `content`, `image_url` (optional), `scheduled_time`, `status` (draft, scheduled, published), `user_id`
- **Platform**: `id`, `name`, `type` (e.g., twitter, instagram, linkedin)
- **PostPlatform** (pivot): `post_id`, `platform_id`, `platform_status`
- **User**: `id`, `name`, `email`, `password`

### API Endpoints

- **Authentication**
  - User registration and login using Laravel Sanctum
  - Basic user profile management

- **Posts**
  - Create a new post with platform selection
  - Retrieve user's posts with filters (status, date)
  - Update a scheduled post
  - Delete a post

- **Platforms**
  - List available platforms
  - Toggle active platforms per user

### Core Features

- Schedule posts for future publication
- Laravel command/job to process due posts and mock publishing
- Validation for platform-specific requirements (e.g., character limits)

---

## Frontend Requirements

### Key Screens

- **Post Editor:** Form with title, content, image upload, platform selector, date/time picker, and character counter
- **Dashboard:** Calendar view of scheduled posts, list view with filters, status indicators
- **Settings:** Platform management page

---

## Creative Challenges

- **Post Analytics:** Display posts per platform, publishing success rate, scheduled vs. published counts
- **Rate Limiting:** Restrict users to a maximum of 10 scheduled posts per day
- **Activity Logging:** Panel to show user action logs
- **Custom Feature:** Freedom to add any innovative feature that enhances the platform

---

## Primary Evaluation Criteria

- **Code Quality (40%)**  
  Clean code adhering to SOLID principles, naming conventions, and thorough documentation

- **Performance Optimization (20%)**  
  Efficient database queries, caching, and optimized API responses

- **Attention to Detail (25%)**  
  Handling edge cases, validation, security, and UI consistency

- **Technical Implementation (15%)**  
  Feature completeness, innovation, scalability, and testing

---

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/HatemRabeaHamde/social-scheduler.git
   cd social-scheduler
