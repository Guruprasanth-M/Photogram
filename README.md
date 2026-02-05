# 📸 Photogram

A secure photo gallery and user profile management application built with PHP, MySQL, and Bootstrap.

## 📋 Overview

<img width="1920" height="1080" alt="Photogram Application" src="https://github.com/user-attachments/assets/324133fd-7956-4e53-8424-c997ebfcdbca" />

<img width="1919" height="1041" alt="Database & Storage Architecture" src="https://github.com/user-attachments/assets/06e95431-0f51-4dcf-ac03-0f1beb58f5df" />

<img width="1920" height="625" alt="Deployment & Infrastructure" src="https://github.com/user-attachments/assets/7b62d0c8-846b-4aaa-a211-39b3eda31bfe" />

<img width="1919" height="1022" alt="Azure Portal Setup" src="https://github.com/user-attachments/assets/b4ee1b2b-b976-4977-b6d7-711a09fb8c7a" />

## ✨ Features

- **Authentication**: Bcrypt password hashing, token-based sessions with IP/User-Agent validation
- **Profiles**: Custom user profiles with bio, avatar, social links
- **Photo Gallery**: Responsive Bootstrap 5 layout (MongoDB GridFS planned)

## 🛠️ Tech Stack

| Component | Technology |
|-----------|-----------|
| Backend | PHP 7.4+, MySQL 8.0 |
| Frontend | Bootstrap 5, JavaScript |
| Container | Docker & Docker Compose |
| Cloud | Microsoft Azure |
| VPN | WireGuard |
| CI/CD | GitHub Actions |

## 🐳 Docker Setup(docker setup still in development)

### Running with Docker Compose

```bash
docker-compose up -d
```

**Services**:
- **Photogram App**: http://localhost:8120
- **MySQL**: localhost:3307
- **Adminer** (DB UI): http://localhost:8080

### Building Docker Image

```bash
docker build -t photogram:latest .
docker run -d -p 8120:80 --name photogram_app photogram:latest
```

## ☁️ Azure Deployment

### Architecture

```
Internet → Azure App Service (Docker) → Private VNET → MySQL Database
                    ↓
            WireGuard VPN Access
```

### Configuration

1. **Resource Group**: Create `photogram-rg` in Azure Portal
2. **MySQL Database**: Azure Database for MySQL (Flexible Server)
3. **App Service**: Linux + Docker container deployment
4. **Environment Variables**: DB credentials in App Service Configuration
5. **VNET Integration**: Private network for database isolation

## 🔐 WireGuard VPN

The deployment is protected using WireGuard VPN:

- Application accessible only through VPN tunnel
- No public inbound access to application ports
- Database isolated within private network

### Server Configuration

```bash
# /etc/wireguard/wg0.conf
[Interface]
PrivateKey = <server_private_key>
Address = 10.0.0.1/24
ListenPort = 51820

[Peer]
PublicKey = <client_public_key>
AllowedIPs = 10.0.0.2/32
```

```bash
sudo wg-quick up wg0
```

## 🔄 CI/CD Pipeline

### GitHub Actions Workflow

```yaml
name: Deploy to Azure

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v3
    
    - name: PHP Lint
      run: find . -name "*.php" -exec php -l {} \;
    
    - name: Build & Push Docker Image
      run: |
        docker build -t photogramacr.azurecr.io/photogram:latest .
        docker push photogramacr.azurecr.io/photogram:latest
    
    - name: Deploy to Azure App Service
      uses: azure/webapps-deploy@v2
      with:
        app-name: 'photogram-app'
        images: photogramacr.azurecr.io/photogram:latest
```

### Pipeline Flow

1. Push to `main` → GitHub Actions triggers
2. PHP syntax validation
3. Docker image built & pushed to Azure Container Registry
4. Azure App Service deploys new image

## 🚀 Local Development

```bash
git clone https://github.com/Guruprasanth-M/Photogram.git
cd Photogram
php -S 127.0.0.1:8000
```

## 👨‍💻 Author

**Guruprasanth M** — [@Guruprasanth-M](https://github.com/Guruprasanth-M)
