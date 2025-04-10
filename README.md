Step 1. Open Docker Desktop + Visual Studio Code Application.
Step 2. In Visual Stduio Code, navigate to 'File' > 'Open Folder' and open the downloaded file 'CIS2715-Laravel-main'
Step 3. A popup will appear in the bottom-right requesting 'Reopen folder to develop in container(learn more)'. Select 'Reopen in Container' button.
Step 4. Once loaded, navigate to 'questionnaireApplication' (the website main folder) and right click > open in integrated terminal.
Step 5: Open command prompt in administrator and run > docker exec -it mariadb_container bash > then import the MariaDB with the appropriate directory: mysql -u root -ppassword questionnaire_db < /path/to/questionnaire_db_backup.sql (replace /path/to/ with the actual path of the questionnaire_db_backup.sql folder located in the main directory for 'CIS2715-Laravel-main'
Step 6. In the terminal, run: npm run dev. This will compile the frontend asstes.
Step 7. Open a new terminal by clicking back on 'questionnaireApplication' (the website main folder) and run: php artisan serve. This will run the built-in web server.
Step 8. Once ran 'php artisan serve', either select 'Open in Browser' popup or navigate to Ports > Port 8000, 127.0.0.1:8000 and hover over the web icon symbol and click: Open in Browser.
Step 9: To close both, navigate to the terminal where they were activated and click 'ctrl + c'. This will close the ports making them innactive.
