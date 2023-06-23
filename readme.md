# PeekChat

Welcome to PeekChat! This document will guide you through the installation process.

## Prerequisites

Before getting started, please ensure that you have the following installed on your system:

- PHP (version 8.1.*)
- Composer (version ^2.5.*)
- Node.js (version ^16.*)
- npm (version ^8.*)

## Installation

1. Clone the repository:

 ```shell
 git clone https://github.com/serbansorin/peekchat.git
 ```

2. Change into the project directory:

 ```shell
 cd peekchat
 ```

3. Install PHP dependencies using Composer (ignoring platform requirements):

 ```shell
 composer install # or with --ignore-platform-reqs
 ```

4. Install JavaScript dependencies using npm:

```shell
npm ci # or npm install if no package lock present
```

## Usage

To start using PeekChat, follow these steps:

1. Start the Vite development server:

```shell
npm run dev # this will watch for any changes
```

This command will start the Vite development server for PeekChat.

2. Start the PHP server:

```shell
php artisan serve
```

This command will start the PHP server for PeekChat.

3. Open your web browser and navigate to [http://localhost:8000](http://localhost:8000).

This will open the PeekChat application in your browser.

## Building for Production

To build the project for production, run the following command:

```shell
npm run build # will build all files needed in the public directory
```

This command will generate optimized and minified files in the `dist` directory.

Also this project user inertia-ssr, so you can use that as an SSR
