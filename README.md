# Установка

### 1. Клонируйте репозиторий к себе на компьютер и перейдите в него:
```bash
git clone https://github.com/Asparagusssa/solargy.git
```
```bash
cd solargy
```
### 2. Установите все необходимые зависимости:
```bash
composer install
npm install
```
### 3. Настройка базы данных:
Переименуйте файл ```.env.example``` в ```.env```.

Подключите и настройте свою базу данных в файле ```.env```
### 4. Сгенерируйте ключ приложения:
```bash
php artisan key:generate
```
### 5. Выполните миграции для создания таблиц в базе данных:
```bash
php artisan migrate --seed
``` 
### 6. Запустите проект на вашем локальном компьютере (Вводите в разных окнах терминала):
```bash
php artisan serve
```
```bash
npm run dev
```
