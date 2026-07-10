# ThreadForge API

> API REST développée avec Laravel 13 permettant de transformer automatiquement un contenu brut en publication optimisée grâce à l'Intelligence Artificielle (Groq).

---

# Architecture du projet

```
Client (Postman / Frontend)
        │
        ▼
Laravel API
        │
        ▼
Controller
        │
        ▼
Validation
        │
        ▼
Database
        │
        ▼
Queue (GeneratePostJob)
        │
        ▼
Groq API
        │
        ▼
Generated Posts
```

---

# Technologies

- Laravel 13
- PHP 8.3
- MySQL
- Sanctum
- Queue
- Jobs
- Groq API
- Pest
- PHPUnit
- GitHub
- GitHub Actions
- Azure VM
- Nginx
- Scribe
- Postman

---

# Fonctionnalités réalisées

## Jour 1 — Authentification

### Fonctionnalités

- Register
- Login
- Logout
- Me

### Technologies

- Laravel Sanctum
- AuthController
- API Token
- Bearer Token

### Routes

POST /api/register

POST /api/login

GET /api/me

POST /api/logout

---

## Jour 2 — Campaign Management

### Fonctionnalités

CRUD complet des Campaigns.

### Routes

GET /api/campaigns

POST /api/campaigns

GET /api/campaigns/{campaign}

PATCH /api/campaigns/{campaign}

DELETE /api/campaigns/{campaign}

---

## Jour 3 — Génération IA

### Fonctionnalités

Transformation d'un contenu brut en publication IA.

### Technologies

- RawContent
- GeneratedPost
- GeneratePostJob
- Queue
- Groq API

### Route

POST /api/content/repurpose

### Consultation

GET /api/posts

GET /api/posts/{generatedPost}

PATCH /api/posts/{generatedPost}/status

---

## Jour 4 — Ghostwriter

### Fonctionnalités

Assistant conversationnel permettant de modifier un post.

### Routes

POST /api/posts/{post}/chat

GET /api/conversations

GET /api/conversations/{conversation}

PATCH /api/conversations/{conversation}

DELETE /api/conversations/{conversation}

---

## Jour 5 — Documentation

### Documentation

- README
- Scribe
- Documentation API
- Tests Postman

---

# Phase 0 — Tests automatiques

## Objectif

Vérifier automatiquement le bon fonctionnement de l'API.

### Tests réalisés

✅ Register

✅ Login

✅ Route protégée

✅ Validation Campaign

✅ Génération asynchrone

Commande :

```bash
php artisan test
```

---

# 📸 IMAGE 1

## Résultat des tests

> Insérer ici une capture de :

```
php artisan test
```

avec tous les tests PASS.

```
<img width="1318" height="336" alt="Screenshot 2026-07-07 095932" src="https://github.com/user-attachments/assets/f654d5f0-3036-4d1d-95d9-3ee61c8d2eb9" />

```

---

# Phase 1 — GitHub Actions

## Objectif

Automatiser les tests après chaque :

```
git push
```

Workflow :

```
git push

↓

GitHub

↓

Workflow YAML

↓

Runner Ubuntu

↓

composer install

↓

php artisan test

↓

✅ Check Vert

ou

❌ Check Rouge
```

---

# 📸 IMAGE 2

## GitHub Actions

Insérer ici une capture de :

- Onglet Actions
- Workflow réussi
- Check vert

```
![Uploading Screenshot 2026-07-10 163908.png…]()


```

---

# Phase 2 — Déploiement Azure

## Objectif

Mettre ThreadForge en ligne.

### Déploiement

- Azure VM Ubuntu
- SSH
- Nginx
- PHP
- Composer
- MySQL
- Queue Worker

L'API est accessible depuis Internet.

Workflow :

```
GitHub

↓

Azure VM

↓

Clone

↓

.env Production

↓

composer install --no-dev

↓

php artisan migrate

↓

php artisan queue:work

↓

API disponible
```

---

# 📸 IMAGE 3

## Déploiement + Test Postman

Insérer ici :

- Capture Postman
- URL Azure
- Réponse JSON

```
<img width="1443" height="791" alt="Screenshot 2026-07-10 162647" src="https://github.com/user-attachments/assets/b425f27d-42a8-414a-8826-74875957ed7a" />

```

---

# Routes principales

## Auth

POST /api/register

POST /api/login

GET /api/me

POST /api/logout

---

## Campaigns

GET /api/campaigns

POST /api/campaigns

GET /api/campaigns/{campaign}

PATCH /api/campaigns/{campaign}

DELETE /api/campaigns/{campaign}

---

## AI

POST /api/content/repurpose

GET /api/posts

GET /api/posts/{generatedPost}

PATCH /api/posts/{generatedPost}/status

---

## Ghostwriter

POST /api/posts/{post}/chat

---

## Conversations

GET /api/conversations

GET /api/conversations/{conversation}

PATCH /api/conversations/{conversation}

DELETE /api/conversations/{conversation}

---

# Ce que j'ai appris

- Laravel 13
- REST API
- Sanctum
- Bearer Token
- Middleware
- Controllers
- Models
- Migrations
- Form Requests
- API Resources
- Queue
- Jobs
- Worker
- Dispatch
- Groq API
- Prompt Engineering
- Tests automatiques avec Pest
- GitHub Actions
- Déploiement Azure
- Nginx
- CI/CD

---

# Objectif pédagogique

Comprendre chaque notion avant d'écrire le code.

Méthode suivie :

1. Contexte
2. Définitions
3. Théorie
4. Workflow
5. Exemple réel
6. Implémentation
7. Tests Postman
8. Tests automatiques
9. Questions d'entretien
10. Erreurs fréquentes
11. Déploiement
12. Documentation
