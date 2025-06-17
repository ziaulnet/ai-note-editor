# 🧠 AI-Enhanced Note Editor

An AI-powered note-taking application built with **Laravel**, **React (Inertia.js)**, **Google OAuth**, and **OpenAI integration**. Users can create, edit, and enhance notes with AI suggestions like summaries. A **raw PHP component** is also included.

---

## ✅ Features Implemented

### 🔐 Authentication
- Google OAuth using Laravel Socialite
- Authenticated routes and user sessions
- Stores basic user profile info in the database

### 📝 Note Management
- Create, view, edit, delete notes
- Auto-save feature (updates note as you type)
- Fully secured via Laravel Policies

### 🤖 AI Enhancements (OpenAI GPT-4.1)
- Summarize notes using OpenAI
- Enhance feature includes real-time AI feedback

### 🔧 Raw PHP Component
- AI tag generation built using raw PHP (outside Laravel)
- Independent endpoint: `public/raw/tags.php`
- Accepts JSON content and returns AI-generated tags

### 💻 Tech Stack
- Backend: Laravel 10
- Frontend: React + Inertia.js + Tailwind CSS
- Database: MySQL
- Auth: Google OAuth
- AI: OpenAI API (`gpt-4.1-nano-2025-04-14`)

---

## 🚀 Installation Instructions

### 1. Clone the Repo

```bash
git clone https://github.com/ziaulnet/ai-note-editor.git
cd ai-note-editor
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Create `.env` and Generate Key

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure `.env` Values

Set the following:

```env
APP_URL=http://localhost:8000
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=
OPENAI_API_KEY=your_openai_key_here

GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/callback
```

### 5. Migrate Database

```bash
php artisan migrate
```

### 6. Start Development Servers

```bash
php artisan serve
npm run dev
```

Then visit: `http://localhost:8000`

---

## 🧪 How to Test the App

- **Login** with Google
- **Create** or edit notes via Dashboard
- **Auto-save** triggers after 2 seconds of pause
- Use the **"Enhance with AI"** button to summarize note
- Use the **"Delete"** icon in the Dashboard to remove a note

---

## 🧩 Raw PHP Component (Non-Laravel)

This demonstrates a standalone AI-enhanced feature built using **pure PHP**:

- 📁 Location: `public/raw/tags.php`
- 📥 Input: JSON `{"content": "note body here"}`
- 📤 Output: JSON with tags

### Example usage via cURL:

```bash
curl -X POST http://localhost:8000/raw/tags.php \
  -H "Content-Type: application/json" \
  -d '{"content":"Laravel is a PHP framework..."}'
```

Returns:

```json
{
  "tags": ["Laravel", "PHP", "Web Development", "Backend", "Framework"]
}
```

---

## 📦 Deployment Notes

- Set `.env` variables on production server
- Use `php artisan config:cache`
- Use `npm run build` to build production assets

---

**Built with ❤️ and GPT-4 integration**
