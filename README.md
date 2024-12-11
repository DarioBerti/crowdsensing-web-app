# crowdsensing-web-app
Design e sviluppo di una web app per il crowdsensing della luminosità di una città



## Configurazione e Avvio dell'Applicazione

### Requisiti
- XAMPP installato.
- Porta MySQL configurata su 3306.(alternativamente cambiare la porta in src/db/db_source/db-config.php)

### Passaggi per Avviare l'Applicazione

1. Configurazione del Database
   - Esegui XAMPP come amministratore.
   - Avvia il modulo Apache e attendi che sia attivo, poi avvia il modulo MySQL.
   - Clicca su Admin accanto a MySQL per accedere a phpMyAdmin.
   - Crea il database utilizzando lo script .sql fornito nella cartella principale: **bylights.sql**.

2. Configurazione della Cartella di produzione frontend
   - Crea cartella "tirocinio" dentro htdocs ed all'interno clona tutta la cartella "crowdsensing-web-app"
   - esegui command prompt come amministratore, naviga fino alla cartella del progetto "bylights" e esegui i comandi
     ```
     	npm install -g @vue/cli
     	npm install
     ```
   - Crea cartella vuota 'myapp' in xampp/htdocs
   - All'interno di 'myapp' incolla il contenuto della cartella 'dist' dell'applicazione.

4. Configurazione del File .htaccess
   - Rimpiazza il file .htaccess nella cartella myapp con il seguente contenuto:
   ```
     <IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteBase /myapp/
  	RewriteRule ^index\.html$ - [L]
	RewriteCond %{REQUEST_FILENAME} !-f
  	RewriteCond %{REQUEST_FILENAME} !-d
  	RewriteRule . /myapp/index.html [L]
     </IfModule>
   ```


### L'app è configurata! per eseguirla:
1. Apri il browser e accedi a http://localhost/myapp.


Se riscontri problemi, verifica che Apache, MySQL e i file di configurazione siano attivi e corretti.
