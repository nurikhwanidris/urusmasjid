# Urus Masjid

**Urus Masjid** is a microSaaS designed to help mosques and suraus manage their activities efficiently. It provides tools for event scheduling, donation tracking, volunteer management, and more.

## Features

- 🕌 **Event Management** – Schedule and manage mosque activities, including prayers, classes, and community events.  
- 💰 **Donation Tracking** – Record and manage donations, with transparency reports for the community.  
- 🤝 **Volunteer Coordination** – Assign and track volunteer duties for different mosque programs.  
- 📊 **Financial Management** – Keep track of mosque expenses and financial reports.  
- 📢 **Announcements & Notifications** – Send important updates to congregants via email or SMS.  

## Tech Stack

- **Backend:** Laravel  
- **Frontend:** Vue.js  
- **Database:** MySQL  
- **Hosting:** Nginx  

## Installation

1. Clone the repository:  
   ```sh
   git clone https://github.com/nurikhwanidris/urus-masjid.git
   ```

2. Navigate to the project directory:
   ```sh
   cd urus-masjid
   ```

3. Install dependencies:
   ```sh
   composer install
   npm install
   ```

4. Set up environment variables:
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure database in .env and run migrations:
   ```sh
   php artisan migrate --seed
   ```

6. Start the development server:
   ```sh
   php artisan serve
   npm run dev
   ```

## License

This project is licensed under the **Creative Commons Attribution-NonCommercial 4.0 International (CC BY-NC 4.0)**. This means you are free to use, modify, and share the project **for non-commercial purposes only**. You must give appropriate credit. Full license details can be found [here](LICENSE).

## Contributing

Contributions are welcome! Feel free to fork this repo, submit issues, or create pull requests.

---

Built with ❤️ for the community.

