# MINK TEST - Laravel & Vue.Js

## Presentation

A family farm wants a platform to sell its livestock to individuals.<br/><br/>

They need a page to clearly display the animals they want to sell.  
Users can view, filter and sort the different animals on sale.<br/><br/>

They also want to have access to a back office allowing them to manage their animals for sale.
  
## Getting Started

### Install

1. Clone this project.
2. Run `npm install`.

#### Environment variables

In your .env file indicate the environment variables in particular:

* APP NAME
* DATABASE CREDENTIALS

* optionally : MAILER CREDENTIALS


### Database

1. Create the folder `animalsPictures` in `storage/app/public/`.
2. Retrieve the photos present in this [drive](https://drive.google.com/drive/folders/1c1zhB1Pd0CAhAARPOEOk0DNPJjAw3XBd?usp=sharing) <br /> And drop them in the folder previously created
3. Run `php artisan migrate`

### User creation

To create a user you must run the a `command`.
This command requires three parameters: name, email and password.  
Here is the command you need to type:
`php artisan user:create name email password`

## Launch the application

1. Run `php artisan storage:link`
2. Run `npm run build`
3. Run `php artisan serve`
