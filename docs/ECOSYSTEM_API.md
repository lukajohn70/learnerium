# Learnerium ↔ ZAVORA Ecosystem Commerce API Specification (v1)

This document specifies the secure inter-service API implemented in **Learnerium LMS** (`learnerium.jlm.com.ng`) to power course discovery, purchasing, and student provisioning from the **ZAVORA Marketplace** (`zavora.jlm.com.ng`).

---

## 1. Authentication & Security

All v1 ecosystem endpoints are secured via the `VerifyEcosystemApiKey` middleware. Requests must include the shared ecosystem secret via either:

- **Header (Recommended):** `Authorization: Bearer <ECOSYSTEM_API_SECRET>`
- **Custom Header:** `X-Ecosystem-Key: <ECOSYSTEM_API_SECRET>`
- **Query Parameter (Development only):** `?api_key=<ECOSYSTEM_API_SECRET>`

If the secret is invalid or missing, the API returns:
```json
HTTP 401 Unauthorized
{
  "success": false,
  "error": "unauthorized",
  "message": "Unauthorized. A valid Ecosystem API key is required."
}
```

---

## 2. Endpoints Overview

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/health` | Health & connectivity check for ZAVORA Integration Monitor |
| `GET` | `/api/v1/courses/catalogue` | Paginated listing of available published & pre-order courses |
| `GET` | `/api/v1/courses/{identifier}` | Complete course metadata and curriculum outline |
| `POST` | `/api/v1/enrolments/provision` | Idempotent student creation & course enrolment provisioning |
| `GET` | `/api/v1/enrolments/status/{ref}` | Check enrollment status using payment reference |

---

## 3. Endpoint Reference

### 3.1 Health Check
**`GET /api/v1/health`**

Used by ZAVORA's admin dashboard to monitor the status of the LMS connection.

**Response (200 OK):**
```json
{
  "success": true,
  "status": "ok",
  "service": "Learnerium LMS",
  "environment": "production",
  "laravel_version": "8.83.29",
  "database": "connected",
  "courses_available": 14,
  "students_count": 210,
  "timestamp": "2026-11-07T12:00:00+00:00"
}
```

---

### 3.2 Course Catalogue
**`GET /api/v1/courses/catalogue`**

Fetches published and pre-order courses for display on the ZAVORA storefront and Academy section.

**Query Parameters:**
- `page` (integer, default `1`)
- `per_page` (integer, default `24`, max `100`)
- `category` (string, optional)
- `level` (string, optional: `Beginner`, `Intermediate`, `Advanced`)
- `search` (string, optional: search title and description)
- `exclude_preorders` (boolean, optional)

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 5,
      "title": "Mastering Full-Stack Web Development",
      "slug": "mastering-full-stack-web-development",
      "description": "Complete guide to modern web development with PHP, Laravel, and JavaScript.",
      "price": 35000.00,
      "effective_price": 35000.00,
      "currency": "NGN",
      "is_preorder": false,
      "preorder_price": null,
      "preorder_ends_at": null,
      "level": "Intermediate",
      "category": "Technology",
      "duration_minutes": 1440,
      "duration_formatted": "24 hours",
      "thumbnail_url": "https://learnerium.jlm.com.ng/uploads/thumbnails/course_5.jpg",
      "modules_count": 8,
      "lessons_count": 48,
      "instructor": {
        "id": 1,
        "name": "John Luka",
        "avatar_url": "https://learnerium.jlm.com.ng/uploads/avatars/john.png"
      },
      "lms_url": "https://learnerium.jlm.com.ng/courses/mastering-full-stack-web-development",
      "published_at": "2026-08-25T10:00:00+00:00"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 24,
    "total": 1,
    "has_more": false
  }
}
```

---

### 3.3 Course Details & Curriculum
**`GET /api/v1/courses/{identifier}`**

Parameter `{identifier}` can be the course `id` (e.g., `5`) or `slug` (e.g., `mastering-full-stack-web-development`).

Returns the full course information plus the curriculum structure (module and lesson titles). Does **not** expose protected video streaming URLs or private lesson documents.

---

### 3.4 Provision Enrolment (Idempotent)
**`POST /api/v1/enrolments/provision`**

Called by ZAVORA after Paystack or Flutterwave payment verification is completed.

**Request Headers:**
```
Content-Type: application/json
Authorization: Bearer <ECOSYSTEM_API_SECRET>
```

**Request Payload:**
```json
{
  "student_email": "customer@example.com",
  "student_name": "Emeka Obi",
  "course_id": 5,
  "payment_reference": "ZAV-ORD-20261107-98421",
  "amount_paid": 35000.00,
  "currency": "NGN",
  "metadata": {
    "zavora_order_id": 1042,
    "customer_phone": "+2348012345678"
  }
}
```

**What Learnerium does:**
1. **Idempotency check:** Checks if `payment_reference` was already processed. If so, returns HTTP 200 with `idempotent: true` and the existing enrolment record.
2. **Account resolution:** Checks if a user with `student_email` exists.
   - If user exists: links the enrolment to their account.
   - If user is new: automatically generates a student account, generates a secure random password, verifies email, and flags `is_new_user: true`.
3. **Revenue share calculation:** Computes instructor share (e.g. 70%) and platform share (e.g. 30%) using live `PlatformSetting` values.
4. **Enrolment record:** Creates or activates the `Enrollment` with `payment_status: 'paid'` (or `'preorder'`).
5. **Notifications:** Dispatches native in-app and email notifications to both the student and the instructor.

**Response (201 Created — New Enrolment):**
```json
{
  "success": true,
  "idempotent": false,
  "message": "Student enrolment successfully provisioned.",
  "data": {
    "enrolment_id": 84,
    "payment_reference": "ZAV-ORD-20261107-98421",
    "payment_status": "paid",
    "amount_paid": 35000.00,
    "is_new_user": true,
    "temporary_password": "generated_password_here",
    "student": {
      "id": 92,
      "name": "Emeka Obi",
      "email": "customer@example.com"
    },
    "course": {
      "id": 5,
      "title": "Mastering Full-Stack Web Development",
      "slug": "mastering-full-stack-web-development"
    },
    "learning_url": "https://learnerium.jlm.com.ng/courses/mastering-full-stack-web-development",
    "login_url": "https://learnerium.jlm.com.ng/login/student",
    "enrolled_at": "2026-11-07T12:30:00+00:00"
  }
}
```

---

### 3.5 Check Enrolment Status
**`GET /api/v1/enrolments/status/{reference}`**

Enables ZAVORA to query learner status, progress percentage, and completion date at any time.

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "enrolment_id": 84,
    "payment_reference": "ZAV-ORD-20261107-98421",
    "payment_status": "paid",
    "amount_paid": 35000.00,
    "progress_percentage": 45,
    "completion_date": null,
    "created_at": "2026-11-07T12:30:00+00:00",
    "student": {
      "id": 92,
      "name": "Emeka Obi",
      "email": "customer@example.com"
    },
    "course": {
      "id": 5,
      "title": "Mastering Full-Stack Web Development",
      "slug": "mastering-full-stack-web-development",
      "thumbnail_url": "https://..."
    },
    "learning_url": "https://learnerium.jlm.com.ng/courses/mastering-full-stack-web-development"
  }
}
```
