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
   - vai su "Database" e crea database con nome "bylights", poi importa file **bylights.sql**.

2. Configurazione della Cartella di produzione frontend
   - Crea cartella "tirocinio" dentro htdocs ed all'interno clona tutta la cartella "crowdsensing-web-app"
   - esegui command prompt come amministratore, naviga fino alla cartella del progetto "bylights" e esegui i comandi
     ```
     	npm install -g @vue/cli
     	npm install
     ```
   - Crea cartella vuota 'myapp' in xampp/htdocs
   - All'interno di 'myapp' incolla il **CONTENUTO** della cartella 'dist' dell'applicazione.

### L'app è configurata! per eseguirla:
1. Apri il browser e accedi a http://localhost:80/myapp
2. esiste già un profilo di email: dario@berti e password: dario1

<br />
<br />

### SE SI VUOLE RIFARE LA BUILD DEL PROGETTO:
Configurazione del File .htaccess
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



<br />
<br />

### In caso di errori:
- Dentro XAMPP, il file di Apache httpd.config la riga "LoadModule rewrite_module modules/mod_rewrite.so" non deve essere commentata
  ed inoltre deve avere abilitato AllowOverride così:
```
  <Directory "C:/xampp/cgi-bin">
    AllowOverride All
    Options None
    Require all granted
</Directory>
```
- Svuotare la cache del browser
