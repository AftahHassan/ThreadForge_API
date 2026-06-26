# 🚀 ThreadForge API

ThreadForge API est une API REST développée avec **Laravel 13** permettant de transformer automatiquement un contenu brut en publications optimisées pour les réseaux sociaux grâce à l'intelligence artificielle.

Le projet permet également d'améliorer les publications générées à l'aide d'un **Ghostwriter Assistant** conversationnel alimenté par **Groq AI**, tout en conservant l'historique des échanges.

---

# ✨ Fonctionnalités

## 🔐 Authentification

* Inscription d'un utilisateur
* Connexion avec Laravel Sanctum
* Authentification par Bearer Token
* Déconnexion
* Profil de l'utilisateur connecté

---

## 📝 Campaign Blueprint Management

Chaque utilisateur peut créer plusieurs Campaign Blueprints contenant :

* Audience cible
* Ton de communication
* Nombre maximal de caractères
* Nombre maximal de hashtags
* Règles de style

Fonctionnalités :

* Création
* Consultation
* Modification
* Suppression

---

## 🤖 AI Repurposing Engine

Transformation automatique d'un contenu brut en publication optimisée grâce à Groq.

Workflow :

Raw Content

↓

Queue Laravel

↓

Groq AI

↓

Generated Post

Le contenu généré comprend :

* Hook
* Body Points
* Technical Readability Score
* Suggested Hashtags
* Tone Compliance Justification

---

## 💬 Ghostwriter Assistant

Le Ghostwriter permet d'améliorer un post déjà généré.

L'utilisateur peut discuter avec l'IA afin de :

* améliorer le hook
* modifier le ton
* raccourcir le texte
* traduire
* ajouter des emojis
* améliorer l'engagement

Toutes les conversations sont sauvegardées.

---

# 🏗 Architecture

```text
User
 │
 ├── Campaign
 │      │
 │      └── GeneratedPost
 │               ▲
 │               │
 │         RawContent
 │
 └── Conversation
         │
         └── Message
```

---

# 🗄 Database Model

```
User 1,N Campaign

Campaign 1,N GeneratedPost

RawContent 1,N GeneratedPost

GeneratedPost 1,N Conversation

Conversation 1,N Message
```

---

# ⚙ Technologies

* Laravel 13
* PHP 8.x
* MySQL
* Laravel Sanctum
* Laravel Queue
* Eloquent ORM
* API Resources
* Form Requests
* Groq API
* Postman
* Git
* GitHub
* Jira

---

# 📂 Project Structure

```
app/
 ├── Http/
 │      ├── Controllers/
 │      ├── Requests/
 │      └── Resources/
 │
 ├── Jobs/
 │      └── GeneratePostJob
 │
 ├── Models/
 │
routes/
 └── api.php
```

---

# 🔑 Authentication

Toutes les routes protégées utilisent :

```
auth:sanctum
```

Après le Login, Laravel retourne un Bearer Token.

Toutes les requêtes authentifiées doivent contenir :

```
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

---

# 📡 Main API Endpoints

## Authentication

| Method | Endpoint      |
| ------ | ------------- |
| POST   | /api/register |
| POST   | /api/login    |
| GET    | /api/me       |
| POST   | /api/logout   |

---

## Campaign Blueprint

| Method    | Endpoint            |
| --------- | ------------------- |
| GET       | /api/campaigns      |
| POST      | /api/campaigns      |
| GET       | /api/campaigns/{id} |
| PUT/PATCH | /api/campaigns/{id} |
| DELETE    | /api/campaigns/{id} |

---

## AI Repurposing

| Method | Endpoint               |
| ------ | ---------------------- |
| POST   | /api/content/repurpose |
| GET    | /api/posts             |
| GET    | /api/posts/{id}        |
| PATCH  | /api/posts/{id}/status |

---

## Ghostwriter

| Method | Endpoint             |
| ------ | -------------------- |
| POST   | /api/posts/{id}/chat |

---

## Conversations

| Method | Endpoint                |
| ------ | ----------------------- |
| GET    | /api/conversations      |
| GET    | /api/conversations/{id} |
| PATCH  | /api/conversations/{id} |
| DELETE | /api/conversations/{id} |

---

# 🚀 Installation

Clone the repository

```bash
git clone https://github.com/AftahHassan/ThreadForge_API.git
```

Go to the project

```bash
cd ThreadForge_API
```

Install dependencies

```bash
composer install
```

Copy the environment file

```bash
cp .env.example .env
```

Generate the application key

```bash
php artisan key:generate
```

Configure your MySQL database inside the `.env` file.

Run migrations

```bash
php artisan migrate
```

Create the queue tables

```bash
php artisan queue:table
php artisan migrate
```

Start the application

```bash
php artisan serve
```

Run the queue worker

```bash
php artisan queue:work
```

---

# 🤖 Groq Configuration

Configure your `.env` file

```env
GROQ_API_KEY=YOUR_API_KEY
GROQ_MODEL=llama-3.3-70b-versatile
QUEUE_CONNECTION=database
```

---

# 🧪 End-to-End Workflow

1. Register
2. Login
3. Create Campaign Blueprint
4. Submit Raw Content
5. Queue launches GeneratePostJob
6. Groq generates the post
7. Generated Post is stored
8. Start a Ghostwriter conversation
9. Continue improving the post
10. Logout

---

# 📌 Future Improvements

* API Documentation (Scribe / OpenAPI)
* Docker support
* PHPUnit Feature Tests
* Rate Limiting
* Admin Dashboard
* Multi-provider AI support (OpenAI, xAI, Anthropic)

---

# 👨‍💻 Author

**Hassan Aftah**

GitHub Repository:

https://github.com/AftahHassan/ThreadForge_API


<img width="1536" height="1024" alt="ChatGPT Image 25 juin 2026, 10_33_15" src="https://github.com/user-attachments/assets/295e81d5-92fd-46b1-94df-4d5c311f6da2" />
