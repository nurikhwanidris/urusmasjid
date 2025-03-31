# Urus Masjid

**Urus Masjid** is a microSaaS designed to help mosques and suraus manage their activities efficiently. It provides tools for event scheduling, donation tracking, volunteer management, and more.

## Features

- 🕌 **Event Management** – Schedule and manage mosque activities, including prayers, classes, and community events.  
- 💰 [Incoming]**Donation Tracking** – Record and manage donations, with transparency reports for the community.  
- 🤝 **Volunteer Coordination** – Assign and track volunteer duties for different mosque programs.  
- 📊 [Incoming]**Financial Management** – Keep track of mosque expenses and generate financial reports.  
- 📢 **Announcements & Notifications** – Send important updates to congregants via email or SMS.  
- 📅 **Prayer Timetable** – Display and manage prayer times based on the mosque's location.  
- 📑 [Incoming]**Document Management** – Store and manage mosque-related documents securely.  

## Tech Stack

- **Backend:** Laravel  
- **Frontend:** Vue.js  
- **Database:** MySQL  
- **Authentication:** Laravel Sanctum  
- **Storage:** Supabase (optional for file storage)  
- **Hosting:** Nginx  
- **Deployment:** Docker (Optional)  

## Installation

Follow these steps to set up the project on your local machine.

### 1. Clone the Repository  
```sh
git clone https://github.com/nurikhwanidris/urus-masjid.git
cd urus-masjid
```

### 2. Install Dependencies  
```sh
composer install
npm install
```

### 3. Set Up Environment Variables  
```sh
cp .env.example .env
php artisan key:generate
```
Edit the `.env` file to configure your database settings.

### 4. Configure the Database and Run Migrations  
```sh
php artisan migrate --seed
```

### 5. Start the Development Server  
```sh
php artisan serve
npm run dev
```

## API Endpoints

| Method | Endpoint                | Description                    |
|--------|-------------------------|--------------------------------|
| GET    | `/api/events`           | Get all mosque events         |
| POST   | `/api/donations`        | Record a new donation         |
| GET    | `/api/volunteers`       | List volunteer activities     |
| POST   | `/api/announcements`    | Send a new announcement       |

For a full API reference, check the documentation folder.

## Deployment

### Docker (Optional)  
You can run the project using Docker by setting up a `docker-compose.yml` file.

### Manual Deployment  
For manual deployment:  
1. Set up an Nginx server with a Laravel configuration.  
2. Set proper permissions for storage:  
   ```sh
   chmod -R 775 storage bootstrap/cache
   ```
3. Configure your `.env` file for production.  
4. Use a process manager like Supervisor to keep the Laravel queue running.

## Contributing

Contributions are welcome! To contribute:  
1. Fork the repository.  
2. Create a new feature branch:  
   ```sh
   git checkout -b feature-branch
   ```
3. Make your changes and commit them.  
4. Push to your fork and submit a pull request.  

## License

This project is licensed under the **MIT License**.

---

### Built with ❤️ for the community.
