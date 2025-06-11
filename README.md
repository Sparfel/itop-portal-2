# iTop-Portal-2, Custom Web Portal for iTop Helpdesk

## Description
This project provides a modern, Laravel-based web portal designed to enhance the user experience for clients interacting with iTop Helpdesk. It offers a streamlined interface for opening and managing incidents, with improved customization options and responsive design for better usability.

## Key Features
- **Intuitive Interface**: User-friendly and easy-to-navigate design for clients.
- **Seamless Integration**: Connects directly to iTop's backend using the REST API.
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices.
- **Customizable Architecture**: Built with Laravel for easy extension and modification.
- **Multilingual Support**: Adaptable to various languages to suit a global audience.
- **Extended Functionalities**: Additional features beyond the default iTop portal.

## Why Use This Portal?
The default iTop user portal, while functional, may not meet the needs of organizations looking for advanced design and extensibility. This custom solution addresses these limitations, providing a more modern and flexible tool to improve client interactions with your IT service management platform.

## Links
- **Documentation**: Explore the full setup guide and user manual at <a href="https://portal-doc.hennebont-kerroch.fr/" target="_blank">https://portal-doc.hennebont-kerroch.fr/</a>.
- **Demo Portal**: Try the iTop Portal live at <a href="https://portal-itop.hennebont-kerroch.fr/" target="_blank">https://portal-itop.hennebont-kerroch.fr/</a> (credentials: my.email@foo.org/password).
- **Demo iTop Instance**: Access the iTop Helpdesk backend used in the demo at <a href="https://itop.hennebont-kerroch.fr/" target="_blank">https://itop.hennebont-kerroch.fr/</a> (credentials: demo/itopDem0$).

## Installation

### Prerequisites
- PHP 8.1 or higher
- Laravel 10 or higher
- A running instance of iTop with REST API enabled
- MySQL or compatible database

### Steps
1. Clone the repository:
   ```bash
    git clone https://github.com/Sparfel/itop-portal-2.git
    cd itop-portal-2

2. Install dependencies:
    ```bash
   composer install
   ./setup.sh

3. Set up the environment file:
    ```bash
   cp .env.example .env

Update the .env file with your database and iTop API credentials.

4. Generate an application key:
    ```bash
    php artisan key:generate

5. Run migrations and seeders:
    ```bash
    php artisan migrate --seed
   
6. Create an admin user from iTop
    ```bash
   php artisan itop:setup-admin --instance=0
