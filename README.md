# Camagru

This web project is to create a small web application allowing one to make basic photo and video editing using webcam and some predefined images. App’s users should be able to select an image in a list of superposable images (for instance a picture frame, or other objects), take a picture with his/her webcam and admire the result that should be mixing both pictures. All captured images should be public, likeable and commentable.

## Requirements

- PHP 7
- MAMP : https://bitnami.com/stack/mamp
- Javascript


### How to download the source code

- Navigate to https://github.com/kaysiz/camagru.git, click on clone or download

## How to set up and configure the database
- Download MAMP from the bitnami website
- Open the manager-osx. Go to the Manage servers tabs and make sure mysql database is running. If not press Restart.
- Press configure, this should show details about the port.
- Open a web browser and go to http://localhost:(the port)/phpmyadmin
- navigate to http://localhost/camagru/config/setup.php. This will create the relevant tables for the project to run


### How to run the program

- Start the web server
- navigate to http://localhost/camagru to access to platform

## Code Breakdown
- Back end technologies
    - PHP

- Front-end technologies
    - HTML
    - CSS
    
- Database management systems
    - mysql
    - phpmyadmin

- Break down of app folder structure
    - config
        - has 3 files, setup.php ( to create tables when installing the application), database.php (to instantiate the PDO connection to the databse) and uninstall.php (to drop) all tables when uninstalling.
    - Dashboard
        * has all the files for user profile edit, upload of images
## Testing

https://github.com/wethinkcode-students/corrections_42_curriculum/blob/master/camagru.markingsheet.pdf
